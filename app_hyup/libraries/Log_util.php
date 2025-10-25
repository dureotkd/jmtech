<?php
class log_util
{
    public function __construct()
    {

        $this->obj = &get_instance();

        $this->obj->load->model("/Page/service_model");
    }

    public function 프리미엄등록($member_row, $status, $gubun, $premiume, $price)
    {
        $point = $member_row['point'];
        $midx = $member_row['idx'];

        $update_point = (int)$point - (int)$price;

        $this->obj->service_model->insert_tb_cash_log(DEBUG, [
            'midx'         => $midx,
            'oidx'         => 0,
            'bidx'         => 0,
            'gubun'        => $gubun,
            'status'       => $status,
            'title'        => $premiume,
            'price'        => $price,
            'commission'   => 0,
            'cash'         => $update_point,
            'before_cash'  => $point,
            'regdate'      => date('Y-m-d H:i:s'),
        ]);

        $this->obj->service_model->update_tb_member(DEBUG, [
            'point' => $update_point,
        ], [
            "idx = {$midx}"
        ]);
    }

    public function 적립적림금로그($idx, $status, $gubun, $title)
    {
        $data = $this->obj->service_model->get_tb_item_order("row", [
            "idx = {$idx}"
        ]);

        // 팝니다 : buy_midx 삽니다 : midx
        $bidx = $data['gubun'] == 1 ? $data['buy_midx'] : $data['midx'];

        $this->obj->service_model->exec_sql("UPDATE tb_member set sspoint = sspoint + {$data['savings']} where idx = {$bidx} ");

        $tb_member = $this->obj->service_model->get_tb_member('row', [
            "idx = {$bidx}"
        ]);

        $sql = "insert into tb_cash_log set ";
        $sql .= "midx = {$bidx}, ";
        $sql .= "oidx = {$data['idx']},";
        $sql .= "bidx = {$bidx}, ";
        $sql .= "status = {$status}, ";
        $sql .= "gubun = '{$gubun}', ";
        $sql .= "title = '{$title}', ";

        if ($status == 1) {

            $sql .= "ssprice = {$data['savings']}, ";
        }

        $sql .= "price = {$data['savings']}, ";
        $sql .= "commission = 0, "; //
        $sql .= "cash = {$data['savings']}, ";
        $sql .= "before_cash = {$tb_member['sspoint']},";
        $sql .= "regdate = now() ";

        if (DEBUG) {

            printr($data);
            echo $sql;
        } else {

            $this->obj->service_model->exec_sql($sql);
        }
    }

    public function 적립금차감로그($idx, $status, $gubun, $title)
    {
        $data = $this->obj->service_model->get_tb_item_order("row", [
            "idx = {$idx}"
        ]);

        // 팝니다 : buy_midx 삽니다 : midx
        $bidx = $data['gubun'] == 1 ? $data['buy_midx'] : $data['midx'];

        $tb_member = $this->obj->service_model->get_tb_member('row', [
            "idx = {$bidx}"
        ]);

        $sql = "insert into tb_cash_log set ";
        $sql .= "midx = {$bidx}, ";
        $sql .= "oidx = {$data['idx']}, ";
        $sql .= "bidx = {$bidx}, ";
        $sql .= "status = {$status}, ";
        $sql .= "gubun = '{$gubun}', ";
        $sql .= "title = '{$title}', ";

        if ($status == 1) {

            $sql .= "ssprice = {$data['ssprice']}, ";
        }

        $sql .= "price = {$data['ssprice']}, ";
        $sql .= "commission = 0, "; //
        $sql .= "cash = {$data['savings']}, ";
        $sql .= "before_cash = {$tb_member['sspoint']},";
        $sql .= "regdate = now() ";

        if (DEBUG) {

            printr($data);
            echo $sql;
        } else {

            $this->obj->service_model->exec_sql($sql);
        }
    }

    public function 적립금회수로그($idx, $status, $gubun, $title)
    {
        $data = $this->obj->service_model->get_tb_item_order("row", [
            "idx = {$idx}"
        ]);

        // 팝니다 : buy_midx 삽니다 : midx
        $bidx = $data['gubun'] == 1 ? $data['buy_midx'] : $data['midx'];

        $tb_member = $this->obj->service_model->get_tb_member('row', [
            "idx = {$bidx}"
        ]);

        $sql = "insert into tb_cash_log set ";
        $sql .= "midx = {$bidx}, ";
        $sql .= "oidx = {$data['idx']}, ";
        $sql .= "bidx = {$bidx}, ";
        $sql .= "status = {$status}, ";
        $sql .= "gubun = '{$gubun}', ";
        $sql .= "title = '{$title}', ";

        if ($status == 1) {

            $sql .= "ssprice = {$data['ssprice']}, ";
        }

        $sql .= "price = {$data['ssprice']}, ";
        $sql .= "commission = 0, "; //
        $sql .= "cash = {$data['savings']}, ";
        $sql .= "before_cash = {$tb_member['sspoint']},";
        $sql .= "regdate = now() ";

        if (DEBUG) {
            printr($data);
            echo $sql;
        } else {

            $this->obj->service_model->exec_sql($sql);
        }
    }

    public function 차감캐시로그($idx, $status, $gubun, $title)
    { //거래용
        $data = $this->obj->service_model->get_tb_item_order("row", [
            "idx = {$idx}"
        ]);

        $res_price = $data['safy_price'] > 0 ? $data['price'] + $data['safy_price'] : $data['price'];

        $tb_member = $this->obj->service_model->get_tb_member('row', [
            "idx = {$data['buy_midx']}"
        ]);

        $sql = "insert into tb_cash_log set ";
        $sql .= "midx = {$data['midx']},";
        $sql .= "oidx = {$data['idx']},";
        $sql .= "bidx = {$data['buy_midx']},";
        $sql .= "status = {$status},";
        $sql .= "gubun = '{$gubun}',";
        $sql .= "title = '{$title}',";
        $sql .= "price = {$res_price}, ";
        $sql .= "commission = 0, ";
        $sql .= "cash = {$res_price}, ";
        $sql .= "before_cash = {$tb_member['point']},";
        $sql .= "regdate = now() ";

        $this->obj->service_model->exec_sql($sql);
    }

    public function 삽니다_차감캐시로그($idx, $status, $gubun, $title, $res_price)
    { //거래용

        @session_start();

        $login_member_idx = $_SESSION['midx'] ?? '';

        $tb_member = $this->obj->service_model->get_tb_member('row', [
            "idx = {$login_member_idx}"
        ]);

        $sql = "insert into tb_cash_log set ";
        $sql .= "midx = {$login_member_idx},";
        $sql .= "oidx = {$idx},";
        $sql .= "bidx = {$login_member_idx},";
        $sql .= "status = {$status},";
        $sql .= "gubun = '{$gubun}',";
        $sql .= "title = '{$title}',";
        $sql .= "price = {$res_price}, ";
        $sql .= "commission = 0, ";
        $sql .= "cash = {$res_price}, ";
        $sql .= "before_cash = {$tb_member['point']},";
        $sql .= "regdate = now() ";

        $this->obj->service_model->exec_sql($sql);
    }

    public function 삽니다_차감적립금로그($idx, $status, $gubun, $title, $sspoint)
    { //거래용
        @session_start();

        $login_member_idx = $_SESSION['midx'] ?? '';

        $data = $this->obj->service_model->get_tb_item_order("row", [
            "idx = {$idx}"
        ]);

        $tb_member = $this->obj->service_model->get_tb_member('row', [
            "idx = {$data['midx']}"
        ]);

        $sql = "insert into tb_cash_log set ";
        $sql .= "midx = {$login_member_idx},";
        $sql .= "oidx = {$data['idx']},";
        $sql .= "bidx = {$login_member_idx},";
        $sql .= "status = {$status},";
        $sql .= "gubun = '{$gubun}',";
        $sql .= "title = '{$title}',";
        $sql .= "price = {$sspoint}, ";
        $sql .= "commission = 0, ";
        $sql .= "cash = {$sspoint}, ";
        $sql .= "before_cash = {$tb_member['point']},";
        $sql .= "regdate = now() ";

        $this->obj->service_model->exec_sql($sql);
    }

    function 마일리지출금($midx, $status, $amount, $title)
    { //거래용
        $tb_member = $this->obj->service_model->get_tb_member('row', [
            "idx = {$midx}"
        ]);

        $tb_site_info = $this->obj->service_model->get_tb_site_info('row', ["seq = 1"]);
        $save_withdraw_fee = $tb_site_info['save_withdraw_fee'];

        $sql = "insert into tb_cash_log set ";
        $sql .= "midx = {$midx},";
        $sql .= "oidx = 0,";
        $sql .= "bidx = 0,";
        $sql .= "status = {$status},";
        $sql .= "gubun = '{$title}',";
        $sql .= "title = '{$title}',";
        $sql .= "price = {$amount}, ";
        $sql .= "commission = {$save_withdraw_fee}, ";
        $sql .= "cash = {$amount}, ";
        $sql .= "before_cash = {$tb_member['point']},";
        $sql .= "regdate = now() ";

        if (DEBUG) {
            echo $sql;
        } else {

            return $this->obj->service_model->exec_sql($sql);
        }
    }


    function 적립캐시로그($idx, $status, $gubun, $title)
    { //거래용

        /**
         * gubun 1 (판매) 
         * midx가 판매해서 midx가 마일리지 적립됌
         * 
         * gubun 2 (구매)
         * buy_midx가 판매해서 buy_midx가 마일리지 적립됌
         */
        $data = $this->obj->service_model->get_tb_item_order("row", [
            "idx = {$idx}"
        ]);

        // 팝니다 : buy_midx 삽니다 : midx
        $bidx = $data['gubun'] == 1 ? $data['midx'] : $data['buy_midx'];

        $sql = "insert into tb_cash_log set ";
        $sql .= "midx = {$bidx},";
        $sql .= "oidx = {$data['idx']},";
        $sql .= "bidx = {$bidx},";
        $sql .= "status = {$status},";
        $sql .= "gubun = '{$gubun}',";
        $sql .= "title = '{$title}',";
        $sql .= "price = {$data['price']}, ";

        if ($gubun == "거래취소") {
            $sql .= "commission = 0, ";
            $sql .= "cash = 0, ";
        } else {
            $sql .= "commission = {$data['commission']}, ";
            $sql .= "cash = {$data['price_after']}, ";
        }
        $sql .= "regdate = now() ";

        if (DEBUG) {
            echo $sql;
        } else {

            return $this->obj->service_model->exec_sql($sql);
        }
    }

    function 마일리지회수로그($idx, $status, $gubun, $title)
    { //거래용

        /**
         * gubun 1 (판매) 
         * midx가 판매해서 midx가 마일리지 적립됌
         * 
         * gubun 2 (구매)
         * buy_midx가 판매해서 buy_midx가 마일리지 적립됌
         */
        $data = $this->obj->service_model->get_tb_item_order("row", [
            "idx = {$idx}"
        ]);

        $bidx = $data['gubun'] == 1 ? $data['buy_midx'] : $data['midx'];

        $sql = "insert into tb_cash_log set ";
        $sql .= "midx = {$bidx},";
        $sql .= "oidx = {$data['idx']},";
        $sql .= "bidx = {$bidx},";
        $sql .= "status = {$status},";
        $sql .= "gubun = '{$gubun}',";
        $sql .= "title = '{$title}',";
        $sql .= "price = {$data['price']}, ";

        if ($gubun == "거래취소") {
            $sql .= "commission = 0, ";
            $sql .= "cash = 0, ";
        } else {
            $sql .= "commission = {$data['commission']}, ";
            $sql .= "cash = {$data['price_after']}, ";
        }
        $sql .= "regdate = now() ";

        if (DEBUG) {
            echo $sql;
        } else {

            return $this->obj->service_model->exec_sql($sql);
        }
    }

    function 적립금충전($data, $status, $gubun, $title)
    {

        $sql = "insert into tb_cash_log set ";
        $sql .= "midx = {$data['midx']},";
        $sql .= "bidx = {$data['midx']},";
        $sql .= "oidx = 0,";
        $sql .= "status = {$status},";
        $sql .= "memo = '현질',";
        $sql .= "gubun = '{$gubun}',";  // 0:충전 1:차감
        $sql .= "title = '{$title}',";
        $sql .= "price = {$data['real_amount']}, ";
        $sql .= "regdate = now() ";

        if (DEBUG) {
            echo $sql;
        } else {

            return $this->obj->service_model->exec_sql($sql);
        }
    }

    function 적립금충전_BY_BANK($data, $status, $gubun, $title)
    {

        $sql = "insert into tb_cash_log set ";
        $sql .= "midx = {$data['midx']},";
        $sql .= "bidx = {$data['midx']},";
        $sql .= "oidx = 0,";
        $sql .= "status = {$status},";
        $sql .= "memo = '현질',";
        $sql .= "gubun = '{$gubun}',";  // 0:충전 1:차감
        $sql .= "title = '{$title}',";
        $sql .= "price = {$data['amount']}, ";
        $sql .= "regdate = now() ";

        if (DEBUG) {
            echo $sql;
        } else {

            return $this->obj->service_model->exec_sql($sql);
        }
    }


    function 회원가입충전($midx, $price, $title)
    {

        $sql = "insert into tb_cash_log set ";
        $sql .= "midx = {$midx},";
        $sql .= "bidx = {$midx},";
        $sql .= "oidx = 0,";
        $sql .= "status = 0,";
        $sql .= "memo = '회원가입-적립금',";
        $sql .= "title = '{$title}',";
        $sql .= "price = '{$price}', ";
        $sql .= "regdate = now() ";

        if (DEBUG) {
            echo $sql;
        } else {

            return $this->obj->service_model->exec_sql($sql);
        }
    }
}
