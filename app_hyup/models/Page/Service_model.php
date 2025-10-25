<?php
class service_model extends MY_Model
{
    // ===== order_detail =====
    public function get_order_detail($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM mujungryeok.order_detail a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_order_detail($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.order_detail', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_order_detail($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.order_detail', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    public function get_mobile_pay($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM mujungryeok.mobile_pay a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_mobile_pay($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.mobile_pay', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_mobile_pay($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.mobile_pay', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }


    // ===== order_bundle_items =====
    public function get_order_bundle_items($type, $where = [1])
    {
        $select = $type == 'one' ?
            "COUNT(*)"
            : "* , 
            (SELECT number FROM mujungryeok.product WHERE id = a.product_id) AS number";
        $sql = sprintf("SELECT {$select} FROM mujungryeok.order_bundle_items a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function get_order_bundle_items_purchased($type, $where = [1])
    {
        $select = $type == 'one' ?
            "COUNT(*)"
            : "*";
        $sql = sprintf("SELECT {$select} FROM mujungryeok.order_bundle_items a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_order_bundle_items($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.order_bundle_items', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_order_bundle_items($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.order_bundle_items', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== alarmtalk_log =====
    public function get_alarmtalk_log($type, $where = [1])
    {
        $select = $type == 'one' ?
            "COUNT(*)"
            : "*";
        $sql = sprintf("SELECT {$select} FROM mujungryeok.alarmtalk_log a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_alarmtalk_log($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.alarmtalk_log', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== order_item =====
    public function get_order_item($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM mujungryeok.order_item a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_order_item($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.order_item', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_order_item($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.order_item', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== payaction_order =====
    public function get_payaction_order($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM mujungryeok.payaction_order a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function get_payaction_order_custom($type, $where = [1], $order = "")
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf(
            "SELECT {$select} FROM mujungryeok.payaction_order a WHERE %s %s",
            join(" AND ", $where),
            $order
        );
        return $this->excute($sql, $type, 'main');
    }
    public function insert_payaction_order($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.payaction_order', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_payaction_order($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.payaction_order', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    // ===== community_benefit =====
    public function get_community_benefit($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM mujungryeok.community_benefit a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_community_benefit($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.community_benefit', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_community_benefit($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.community_benefit', $data, $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.community_event a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_community_event($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.community_event', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_community_event($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.community_event', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_community_event($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('mujungryeok.community_event', $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.point_request a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function get_point_request_join($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "* , a.status AS status , a.id AS request_id";
        $sql = sprintf("SELECT {$select} FROM mujungryeok.point_request a, mujungryeok.user b WHERE %s ORDER BY a.requested_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_point_request($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.point_request', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_point_request($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.point_request', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_point_request($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('mujungryeok.point_request', $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.community_faq a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_community_faq($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.community_faq', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_community_faq($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.community_faq', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_community_faq($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('mujungryeok.community_faq', $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.community_notice a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_community_notice($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.community_notice', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_community_notice($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.community_notice', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_community_notice($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('mujungryeok.community_notice', $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.smartro_payment_log a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_smartro_payment_log($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.smartro_payment_log', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_smartro_payment_log($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.smartro_payment_log', $data, $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.recipe a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_recipe($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.recipe', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_recipe($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.recipe', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_recipe($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('mujungryeok.recipe', $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.payment a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_payment($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.payment', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_payment($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.payment', $data, $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.site_meta a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_site_meta($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.site_meta', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_site_meta($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.site_meta', $data, $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.payment_log a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_payment_log($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.payment_log', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_payment_log($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.payment_log', $data, $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.product a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_product($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.product', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_product($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.product', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_product($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('mujungryeok.product', $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.product_option a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_product_option($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.product_option', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_product_option($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.product_option', $data, $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.review a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function get_review_join($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM mujungryeok.review a , mujungryeok.product b WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_review($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.review', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_review($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.review', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_review($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('mujungryeok.review', $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.product_qna a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_product_qna($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.product_qna', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_product_qna($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.product_qna', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_product_qna($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('mujungryeok.product_qna', $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.banner a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_banner($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.banner', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_banner($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.banner', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_banner($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('mujungryeok.banner', $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.shipment a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_shipment($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.shipment', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_shipment($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.shipment', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }

    public function get_manager($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM mujungryeok.manager a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }

    // ===== user =====
    public function get_user($type, $where = [1])
    {
        $select = $type == 'one' ? "COUNT(*)" : "*";
        $sql = sprintf("SELECT {$select} FROM mujungryeok.user a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_user($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.user', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_user($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.user', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_user($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('mujungryeok.user', $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.user_account a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_user_account($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.user_account', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_user_account($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.user_account', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_user_account($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('mujungryeok.user_account', $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.non_user a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_non_user($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.non_user', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_non_user($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.non_user', $data, $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.password_reset_token a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_password_reset_token($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.password_reset_token', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_password_reset_token($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.password_reset_token', $data, $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.payaction_log a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_payaction_log($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.payaction_log', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_payaction_log($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.payaction_log', $data, $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.point_log a WHERE %s ORDER BY a.created_at DESC", join(" AND ", $where));

        return $this->excute($sql, $type, 'main');
    }
    public function insert_point_log($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.point_log', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_point_log($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.point_log', $data, $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.product_discount a WHERE %s", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_product_discount($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.product_discount', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_product_discount($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.product_discount', $data, $where);
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
        $sql = sprintf("SELECT {$select} FROM mujungryeok.store_code a WHERE %s {$res_limit}", join(" AND ", $where));
        return $this->excute($sql, $type, 'main');
    }
    public function insert_store_code($debug = false, $data = [])
    {
        $sql = $this->getInsertQuery('mujungryeok.store_code', $data);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function update_store_code($debug = false, $data = [], $where = [])
    {
        $sql = $this->getUpdateQuery('mujungryeok.store_code', $data, $where);
        if ($debug) {
            echo $sql . "<br/>";
            return 1;
        }
        return $this->excute($sql, 'exec', 'main');
    }
    public function delete_store_code($debug = false, $where = [])
    {
        $sql = $this->getDeleteQuery('mujungryeok.store_code', $where);
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
                mujungryeok.file a
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

        $sql = $this->getInsertQuery('mujungryeok.file', $data);

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

        $sql = $this->getUpdateQuery('mujungryeok.file', $data, $where);

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
