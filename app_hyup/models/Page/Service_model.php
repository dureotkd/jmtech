<?php
class service_model extends MY_Model
{
    // ===== business_partner =====
    public function get_business_partner($type, $where = [1], $limit = '')
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.business_partner a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        if (!empty($limit)) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->excute($sql, $type, 'main');
    }
    public function insert_business_partner($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.business_partner', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_business_partner($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.business_partner', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_business_partner($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.business_partner', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    public function get_company($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.company a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }

    public function get_sales_monthly_summary_stat($where = [1])
    {
        $current_month = date('m'); // 현재 월 (예: 12)
        $prev_month = date('m', strtotime('-1 month')); // 11
        $prev_prev_month = date('m', strtotime('-2 months')); // 10
        $prev_3_month = date('m', strtotime('-3 months')); // 09
        $prev_4_month = date('m', strtotime('-4 months')); // 08
        $prev_5_month = date('m', strtotime('-5 months')); // 07

        $sql = sprintf("
        SELECT 
            SUM(a.sales_{$current_month} + a.sales_{$prev_month} + a.sales_{$prev_prev_month}) as sum_recent_3months,
            SUM(a.sales_{$current_month} + a.sales_{$prev_month} + a.sales_{$prev_prev_month} + a.sales_{$prev_3_month} + a.sales_{$prev_4_month} + a.sales_{$prev_5_month}) as sum_recent_6months,
            SUM(a.sales_01 + a.sales_02 + a.sales_03 + a.sales_04 + a.sales_05 + a.sales_06 + a.sales_07 + a.sales_08 + a.sales_09 + a.sales_10 + a.sales_11 + a.sales_12) as sum_current_year
        FROM jmtech.sales_monthly_summary a 
        WHERE %s
    ", join(" AND ", $where));

        return $this->excute($sql, 'row', 'main');
    }

    public function get_sales_monthly_summary($type, $where = [1], $order = 'a.created_at DESC', $limit = '')
    {
        $current_month = date('m'); // 현재 월 (예: 12)
        $prev_month = date('m', strtotime('-1 month')); // 전월 (예: 11)
        $prev_prev_month = date('m', strtotime('-2 months')); // 전전월 (예: 10)

        if ($type == 'one') {
            $select = "COUNT(*)";
        } else {
            $select = "
            a.*,
            a.sales_{$current_month} as current_month_sales,
            a.sales_{$prev_month} as prev_month_sales,
            a.sales_{$prev_prev_month} as prev_prev_month_sales,
            (a.sales_{$current_month} + a.sales_{$prev_month} + a.sales_{$prev_prev_month}) as recent_3months_sales
        ";
        }

        $sql = sprintf("SELECT {$select} FROM jmtech.sales_monthly_summary a WHERE %s ORDER BY {$order} LIMIT {$limit}", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_sales_monthly_summary($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.sales_monthly_summary', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function insert_company($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.company', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_company($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.company', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }


    // ===== estimate =====
    public function get_estimate($type, $where = [1])
    {
        $select = $type == 'one' ?
            "COUNT(*)"
            :
            "* , 
            (SELECT company_name FROM jmtech.business_partner bp WHERE bp.id = a.partner_id) AS partner_name ,
            (SELECT id FROM jmtech.estimate WHERE no = a.no AND sub_type = 'S') AS su_estimate_id ,
            (SELECT status FROM jmtech.estimate WHERE no = a.no AND sub_type = 'G') AS gu_status ,
            (SELECT GROUP_CONCAT(id SEPARATOR ',')
 FROM jmtech.file
 WHERE ref_table = 'estimate'
   AND ref_id = a.id
) AS file_ids,
         (SELECT GROUP_CONCAT(file_name SEPARATOR ',')
 FROM jmtech.file
 WHERE ref_table = 'estimate'
   AND ref_id = a.id
) AS file_names,
            (SELECT status2 FROM jmtech.estimate WHERE no = a.no AND sub_type = 'S') AS su_status ,
            (SELECT id FROM jmtech.estimate WHERE no = a.no AND sub_type = 'G') AS g_estimate_id";
        $sql = sprintf("SELECT {$select} FROM jmtech.estimate a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_estimate($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.estimate', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_estimate($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.estimate', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_estimate($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.estimate', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== transcation_statement =====
    public function get_transcation_statement($type, $where = [1])
    {
        $select = $type == 'one' ?
            "COUNT(*)"
            :
            "* , 
            (SELECT company_name FROM jmtech.business_partner bp WHERE bp.id = a.partner_id) AS partner_name ,
            (SELECT company_num FROM jmtech.business_partner bp WHERE bp.id = a.partner_id) AS partner_company_num ,
            (SELECT address FROM jmtech.business_partner bp WHERE bp.id = a.partner_id) AS partner_address ,
            (SELECT phone_number FROM jmtech.business_partner bp WHERE bp.id = a.partner_id) AS partner_phone_number ,
            (SELECT fax_number FROM jmtech.business_partner bp WHERE bp.id = a.partner_id) AS partner_fax_number ,
            (SELECT id FROM jmtech.transcation_statement WHERE no = a.no AND sub_type = 'S') AS su_transcation_statement_id ,
            (SELECT id FROM jmtech.transcation_statement WHERE no = a.no AND sub_type = 'G') AS g_transcation_statement_id";
        $sql = sprintf("SELECT {$select} FROM jmtech.transcation_statement a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_transcation_statement($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.transcation_statement', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_transcation_statement($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.transcation_statement', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_transcation_statement($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.transcation_statement', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== item =====
    public function get_item($type, $where = [1], $limit = '')
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $res_limit = !empty($limit) ? "LIMIT {$limit}" : "";
        $sql = sprintf("SELECT {$select} FROM jmtech.item a WHERE %s {$res_limit}", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function get_item_custom($type, $where = [1], $order = "")
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf(
            "SELECT {$select} FROM jmtech.item a WHERE %s %s",
            join(" AND ", $where),
            $order
        );
        return $this->excute($sql, $type, 'main');
    }
    public function insert_item($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.item', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_item($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.item', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_item($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.item', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== estimate_sheet =====
    public function get_estimate_sheet($type, $where = [1], $limit = '')
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $res_limit = !empty($limit) ? "LIMIT {$limit}" : "";
        $sql = sprintf("SELECT {$select} FROM jmtech.estimate_sheet a WHERE %s {$res_limit}", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function get_estimate_sheet_custom($type, $where = [1], $order = "")
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf(
            "SELECT {$select} FROM jmtech.estimate_sheet a WHERE %s %s",
            join(" AND ", $where),
            $order
        );
        return $this->excute($sql, $type, 'main');
    }
    public function insert_estimate_sheet($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.estimate_sheet', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_estimate_sheet($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.estimate_sheet', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_estimate_sheet($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.estimate_sheet', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== admin_event_log =====
    public function get_admin_event_log($type, $where = [1], $limit = '')
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $res_limit = !empty($limit) ? "LIMIT {$limit}" : "";
        $sql = sprintf("SELECT {$select} FROM jmtech.admin_event_log a WHERE %s ORDER BY a.created_at DESC {$res_limit}", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function get_admin_event_log_custom($type, $where = [1], $order = "")
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf(
            "SELECT {$select} FROM jmtech.admin_event_log a WHERE %s %s",
            join(" AND ", $where),
            $order
        );
        return $this->excute($sql, $type, 'main');
    }
    public function insert_admin_event_log($data = [])
    {
        $sql = $this->getInsertQuery('jmtech.admin_event_log', $data);
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_admin_event_log($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.admin_event_log', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_admin_event_log($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.admin_event_log', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }


    // ===== community_event =====
    public function get_community_event($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.community_event a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_community_event($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.community_event', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_community_event($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.community_event', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_community_event($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.community_event', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== moneypin_biz_info =====
    public function get_moneypin_biz_info($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.moneypin_biz_info a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_moneypin_biz_info($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.moneypin_biz_info', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_moneypin_biz_info($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.moneypin_biz_info', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_moneypin_biz_info($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.moneypin_biz_info', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== point_request =====
    public function get_point_request($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.point_request a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function get_point_request_join($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "* , a.status AS status , a.id AS request_id";
        $sql = sprintf("SELECT {$select} FROM jmtech.point_request a, jmtech.user b WHERE %s ORDER BY a.requested_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_point_request($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.point_request', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_point_request($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.point_request', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_point_request($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.point_request', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== community_faq =====
    public function get_community_faq($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.community_faq a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_community_faq($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.community_faq', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_community_faq($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.community_faq', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_community_faq($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.community_faq', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== community_notice =====
    public function get_community_notice($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.community_notice a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_community_notice($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.community_notice', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_community_notice($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.community_notice', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_community_notice($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.community_notice', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== barobill_tax_invoice =====
    public function get_barobill_tax_invoice($type, $where = [1], $limit = '')
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.barobill_tax_invoice a WHERE %s ORDER BY a.WriteDate DESC", join(" AND ", $where));
        if (!empty($limit)) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->excute($sql, $type, 'main');
    }
    public function insert_barobill_tax_invoice($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.barobill_tax_invoice', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_barobill_tax_invoice($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.order_detail', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_barobill_tax_invoice($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.barobill_tax_invoice', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== smartro_payment_log =====
    public function get_smartro_payment_log($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.smartro_payment_log a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_smartro_payment_log($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.smartro_payment_log', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_smartro_payment_log($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.smartro_payment_log', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== recipe =====
    public function get_recipe($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.recipe a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_recipe($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.recipe', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_recipe($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.recipe', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_recipe($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.recipe', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }


    // ===== payment =====
    public function get_payment($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.payment a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_payment($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.payment', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_payment($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.payment', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== payment =====
    public function get_site_meta($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.site_meta a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_site_meta($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.site_meta', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_site_meta($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.site_meta', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== payment_log =====
    public function get_payment_log($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.payment_log a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_payment_log($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.payment_log', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_payment_log($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.payment_log', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== product =====
    public function get_product($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "* , a.price AS ori_price";
        $sql = sprintf("SELECT {$select} FROM jmtech.product a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_product($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.product', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_product($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.product', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_product($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.product', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== product_option =====
    public function get_product_option($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.product_option a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_product_option($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.product_option', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_product_option($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.product_option', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== review =====
    public function get_review($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.review a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function get_review_join($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.review a , jmtech.product b WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_review($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.review', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_review($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.review', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_review($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.review', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== product_qna =====
    public function get_product_qna($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.product_qna a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_product_qna($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.product_qna', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_product_qna($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.product_qna', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_product_qna($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.product_qna', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== banners =====
    public function get_banner($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.banner a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_banner($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.banner', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_banner($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.banner', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_banner($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.banner', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== shipment =====
    public function get_shipment($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.shipment a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_shipment($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.shipment', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_shipment($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.shipment', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    public function get_manager($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.manager a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }

    // ===== user =====
    public function get_user($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.user a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_user($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.user', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_user($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.user', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_user($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.user', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== user_account =====
    public function get_user_account($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.user_account a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_user_account($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.user_account', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_user_account($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.user_account', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_user_account($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.user_account', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }


    // ===== non_user =====
    public function get_non_user($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.non_user a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_non_user($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.non_user', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_non_user($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.non_user', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== password_reset_token =====
    public function get_password_reset_token($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.password_reset_token a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_password_reset_token($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.password_reset_token', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_password_reset_token($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.password_reset_token', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== payaction_log =====
    public function get_payaction_log($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.payaction_log a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_payaction_log($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.payaction_log', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_payaction_log($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.payaction_log', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== point_log =====
    public function get_point_log($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.point_log a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));

        return $this->excute($sql, $type, 'main');
    }
    public function insert_point_log($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.point_log', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_point_log($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.point_log', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== product_discount =====
    public function get_product_discount($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM jmtech.product_discount a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_product_discount($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.product_discount', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_product_discount($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.product_discount', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== store_code =====
    public function get_store_code($type, $where = [1], $limit = '')
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $res_limit = !empty($limit) ? "LIMIT {$limit}" : "";
        $sql = sprintf("SELECT {$select} FROM jmtech.store_code a WHERE %s {$res_limit}", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_store_code($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('jmtech.store_code', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_store_code($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('jmtech.store_code', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_store_code($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('jmtech.store_code', $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }


    public function get_file($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";

        $sql = sprintf(
            "SELECT 
                {$select}
            FROM 
                jmtech.file a
            WHERE 
                %s
            ",
            join(" AND ", $where)
        );

        $res = $this->excute($sql, $type, 'main');

        return $res;
    }

    public function insert_file($debug = false, $data = [])
    {

        $sql = $this->getInsertQuery('jmtech.file', $data);

        if ($debug) {

            echo $sql . "<br/>";

            $res = 1;
        } else {

            $res = $this->excute($sql, 'exec', 'main');
        }

        return $res;
    }

    public function update_file($debug = false, $data = [], $where = [])
    {

        $sql = $this->getUpdateQuery('jmtech.file', $data, $where);

        if ($debug) {

            echo $sql . "<br/>";

            $res = 1;
        } else {

            $res = $this->excute($sql, 'exec', 'main');
        }

        return $res;
    }

    public function delete_file($debug = false, $where = [])
    {

        $sql = $this->getDeleteQuery('jmtech.file', $where);

        if ($debug) {

            echo $sql . "<br/>";

            $res = 1;
        } else {

            $res = $this->excute($sql, 'exec', 'main');
        }

        return $res;
    }

    public function exec_sql($type, $sql)
    {

        $res = $this->excute($sql, $type, 'main');

        return $res;
    }
}
