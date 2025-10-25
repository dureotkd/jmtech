<?php
class point
{

    public $MID = "hihome";
    public $API_KEY = "7l2uQiNA1NZ7hvRuEPl9cm2OvuXrymw4OqF4vjNb";


    public function __construct()
    {

        $this->obj = &get_instance();

        $this->obj->load->model("Page/service_model");

        $this->obj->load->library("innopay");
        $this->obj->load->library("member");
        $this->obj->load->library("log_util");
    }

    public function 충전처리($bank_idx)
    {
        $res_array = [];

        foreach ([1] as $process) {

            $tb_bank_row = $this->obj->service_model->get_tb_bank('row', [
                "idx = {$bank_idx}"
            ]);

            if (empty($tb_bank_row)) {
                throw new Error('tb_bank empty');
            }

            $status         = $tb_bank_row['status'];
            $midx           = $tb_bank_row['midx'];
            $amount         = $tb_bank_row['amount'];

            if ($status != 0) {
                throw new Error('입금전 상태가 아닙니다');
            }

            $tb_member_row  = $this->obj->service_model->get_tb_member('row', [
                "idx = {$midx}"
            ]);
            $update_point = (int)$tb_member_row['point'] + (int)$amount;

            $update_tb_bank_res = $this->obj->service_model->update_tb_bank([
                "status"    => 1, // 정상입금
            ], [
                "idx = {$bank_idx}"
            ]);

            if (empty($update_tb_bank_res)) {
                throw new Error('db server error 1');
            }

            $update_tb_member_res = $this->obj->service_model->update_tb_member([
                "point" => $update_point // 마일리지 충전
            ], [
                "idx = {$midx}"
            ]);

            if (empty($update_tb_member_res)) {
                throw new Error('db server error 2');
            }
        }
    }

    public function 출금처리($bank_idx)
    {
        $res_array = [];

        foreach ([1] as $process) {

            # =========================================== 마일리지 감소 ===========================================

            $tb_bank_row = $this->obj->service_model->get_tb_bank('row', [
                "idx = {$bank_idx}"
            ]);

            if (empty($tb_bank_row)) {
                throw new Error('tb_bank empty');
            }

            $status         = $tb_bank_row['status'];
            $midx           = $tb_bank_row['midx'];
            $amount         = $tb_bank_row['amount'];

            if ($status != 3) {
                throw new Error('출금전 상태가 아닙니다');
            }

            $tb_member_row  = $this->obj->service_model->get_tb_member('row', [
                "idx = {$midx}"
            ]);

            $now_point = (int)$tb_member_row['point'];

            if ($now_point < $amount) {
                throw new Error("마일리지가 더 적습니다");
            }

            $update_point = $now_point - (int)$amount;

            $update_tb_bank_res = $this->obj->service_model->update_tb_bank([
                "status"    => 4, // 정상출금
            ], [
                "idx = {$bank_idx}"
            ]);

            if (empty($update_tb_bank_res)) {
                throw new Error('update_tb_bank_res error 1');
            }

            $update_tb_member_res = $this->obj->service_model->update_tb_member([
                "point" => $update_point // 마일리지 감소
            ], [
                "idx = {$midx}"
            ]);

            if (empty($update_tb_member_res)) {
                throw new Error('update_tb_member_res error 2');
            }

            # =========================================== 마일리지 감소 ===========================================

            # =========================================== 회원에게 송금 ===========================================

            $this->obj->innopay->send_money($bank_idx);

            # =========================================== 회원에게 송금 ===========================================

            /**
             * 그다음 출금처리하면 떙..!!
             */
        }
    }

    public function 출금신청처리($amount)
    {

        foreach ([1] as $process) {

            if (empty($amount)) {
                throw new Error('출금금액을 입력해주세요');
            }

            $tb_site_info = $this->obj->service_model->get_tb_site_info('row', ["seq = 1"]);
            $save_withdraw_fee = $tb_site_info['save_withdraw_fee'];
            $min_withdraw_fee = $tb_site_info['min_withdraw_fee'];

            if ($amount <= $save_withdraw_fee) {
                throw new Error('출금수수료보다 적거나 같습니다');
            }

            if ($amount < $min_withdraw_fee) {
                throw new Error("최소 출금액은 {$min_withdraw_fee}원 입니다");
            }

            $login_member = $this->obj->member->get_login_member();

            $account_bank = 은행($login_member['account_bank']);
            $account_no = $login_member['account_no'];
            $account_owner = $login_member['account_owner'];
            $account_info = "{$account_bank} {$account_no} {$account_owner}";

            $insert_tb_bank_res = $this->obj->service_model->insert_tb_bank(DEBUG, [
                "midx"      => $login_member['idx'],
                "status"    => 3, // 출금신청
                "acount_info" => $account_info,
                "payment" => "출금",
                "name"    => $login_member['name'],
                "amount"    => $amount,
                "regdate" => date('Y-m-d H:i:s')
            ]);

            if (empty($insert_tb_bank_res)) {
                throw new Error(DB_ERR_MSG);
            }

            # =========================================== 마일리지 감소 ===========================================

            $update_tb_member_res = $this->obj->service_model->update_tb_member(DEBUG, [
                "point" => (int)$login_member['point'] - $amount // 마일리지 감소
            ], [
                "idx = {$login_member['idx']}"
            ]);

            if (empty($update_tb_member_res)) {
                throw new Error(DB_ERR_MSG);
            }

            $마일리지출금_LOG_RES = $this->obj->log_util->마일리지출금($login_member['idx'], 1, $amount, "출금신청");

            if (empty($마일리지출금_LOG_RES)) {
                throw new Error(DB_ERR_MSG);
            }

            # =========================================== 마일리지 감소 ===========================================

            # =========================================== 회원에게 송금 ===========================================

            // * 100만원 초과금액은 관리자에서 수동으로 처리한다.
            if ((int)$amount > 1000000) {
                return;
            }

            $실제출금액 = (int)$amount - (int)$save_withdraw_fee;

            $send_money_res = $this->obj->innopay->send_money([
                'account_no' => $account_no,
                'bank_code' => $login_member['account_bank'],
                'account_owner' => $account_owner,
                'amount' => $실제출금액
            ]);

            $send_res_code = $send_money_res['resultCode'] ?? '';

            if ($send_res_code != '0000') {
                throw new Error($send_money_res['resultMsg'] ?? "출금처리 실패\n운영자에게 문의주세요");
            }

            # =========================================== 회원에게 송금 ===========================================
        }
    }
}
