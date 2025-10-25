<?php
class payment extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        @session_start();

        $midx = $_SESSION['midx'] ?? '';

        if (empty($midx)) {
            header('Location:/page/login');
            exit;
        }

        $this->load->library("layout");
        $this->load->library("innopay");
        $this->load->library("toss");
        $this->load->library("log_util");
        $this->load->library("member");

        $this->load->model('/Page/service_model');

        $this->load->helper('encrypt');
    }


    /**
     * * 토스페이먼트 카드 결제 성공시
     *
     * @return void
     */
    public function success_card()
    {
        $paymentkey = $_REQUEST['paymentKey'] ?? ''; // 결제 고유키
        $amount = $_REQUEST['amount'] ?? ''; // 결제 금액
        $orderid = $_REQUEST['orderId'] ?? ''; // 주문번호

        $res_array = [
            'ok' => true,
            'msg' => ''
        ];

        foreach ([1] as $proc) {

            $login_member = $this->member->get_login_member();

            if (empty($paymentkey)) {
                $res_array['ok'] = false;
                $res_array['msg'] = 'paymentkey empty';
                break;
            }
            if (empty($amount)) {
                $res_array['ok'] = false;
                $res_array['msg'] = 'amount empty';
                break;
            }
            if (empty($orderid)) {
                $res_array['ok'] = false;
                $res_array['msg'] = 'orderid empty';
                break;
            }
            if (empty($login_member)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '로그인 후 이용해주세요';
                break;
            }

            $tb_site_info_row = $this->service_model->get_tb_site_info('row');

            if (empty($tb_site_info_row)) {
                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
                break;
            }

            $min_save_fee = $tb_site_info_row['min_save_fee'] ?? 0;

            if ($amount < $min_save_fee) {
                $res_array['ok'] = false;
                $res_array['msg'] = "최소 결제금액은 {$min_save_fee}원 이상입니다";
                break;
            }

            $user = explode("_", $orderid);

            //결제창을 호출했을때 등록된 데이터와 일치하는지 확인
            $tb_card_log_row = $this->service_model->get_tb_card_log('row', [
                "userid = '{$user[0]}'",
                "orderid = '{$orderid}'",
                "amount = '{$amount}'",
                "status = 0",
            ]);

            if (empty($tb_card_log_row)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '결제 정보가 일치하지 않습니다';
                break;
            }

            // 결제 승인 요청

            try {

                $toss_result = $this->toss->confirm_payment([
                    'amount'        => $amount,
                    'paymentkey'    => $paymentkey,
                    'orderid'       => $orderid,
                ]);

                $toss_result_status = $toss_result['status'] ?? '';

                if ($toss_result_status != 'DONE') {
                    $res_array['ok'] = false;
                    $res_array['msg'] = $toss_result['message'] ?? '결제 승인 요청에 실패하였습니다';
                    break;
                }
            } catch (\Throwable $th) {
                $res_array['ok'] = false;
                $res_array['msg'] = $th->getMessage() ?? '결제 승인 요청에 실패하였습니다';
                break;
            }

            $sql1 = "update tb_card_log set status = 1, paymentkey = '{$paymentkey}' where idx = {$tb_card_log_row['idx']} ";
            $res1 = $this->service_model->exec_sql($sql1);

            if (empty($res1)) {
                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
                break;
            }

            $res2 = $this->log_util->적립금충전($tb_card_log_row, 0, '충전', '마일리지 충전(신용카드)'); // 적립0 , 차감1

            if (empty($res2)) {
                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
                break;
            }

            $real_amount = (int)$tb_card_log_row['real_amount'];

            $sql2 = "update tb_member set point = point + {$real_amount} where idx = {$login_member['idx']} ";
            $res2 = $this->service_model->exec_sql($sql2);

            // 21,021,000
            if (empty($res2)) {
                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
                break;
            }

            $충전금액 = number_format($tb_card_log_row['real_amount']);
            $res_array['msg'] = "{$충전금액}원이 충전되었습니다.";
        }

        set_cookie($res_array['ok'] ? 'point_alert' : 'point_fail_alert', rawurlencode($res_array['msg']), 15000);
        header('Location:/page/mile_list');
    }

    /**
     * * 토스페이먼트 휴대폰결제 성공시
     *
     * @return void
     */
    public function success_phone()
    {
        $paymentkey = $_REQUEST['paymentKey'] ?? ''; // 결제 고유키
        $amount = $_REQUEST['amount'] ?? ''; // 결제 금액
        $orderid = $_REQUEST['orderId'] ?? ''; // 주문번호

        $res_array = [
            'ok' => true,
            'msg' => ''
        ];

        foreach ([1] as $proc) {

            $login_member = $this->member->get_login_member();

            if (empty($paymentkey)) {
                $res_array['ok'] = false;
                $res_array['msg'] = 'paymentkey empty';
                break;
            }
            if (empty($amount)) {
                $res_array['ok'] = false;
                $res_array['msg'] = 'amount empty';
                break;
            }
            if (empty($orderid)) {
                $res_array['ok'] = false;
                $res_array['msg'] = 'orderid empty';
                break;
            }
            if (empty($login_member)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '로그인 후 이용해주세요';
                break;
            }

            $tb_site_info_row = $this->service_model->get_tb_site_info('row');

            if (empty($tb_site_info_row)) {
                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
                break;
            }

            $min_save_fee = $tb_site_info_row['min_save_fee'] ?? 0;

            if ($amount < $min_save_fee) {
                $res_array['ok'] = false;
                $res_array['msg'] = "최소 결제금액은 {$min_save_fee}원 이상입니다";
                break;
            }

            $user = explode("_", $orderid);

            //결제창을 호출했을때 등록된 데이터와 일치하는지 확인
            $tb_card_log_row = $this->service_model->get_tb_card_log('row', [
                "userid = '{$user[0]}'",
                "orderid = '{$orderid}'",
                "amount = '{$amount}'",
                "status = 0",
            ]);

            if (empty($tb_card_log_row)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '결제 정보가 일치하지 않습니다';
                break;
            }

            // 결제 승인 요청

            try {

                $toss_result = $this->toss->confirm_payment([
                    'amount'        => $amount,
                    'paymentkey'    => $paymentkey,
                    'orderid'       => $orderid,
                ]);

                $toss_result_status = $toss_result['status'] ?? '';

                if ($toss_result_status != 'DONE') {
                    $res_array['ok'] = false;
                    $res_array['msg'] = $toss_result['message'] ?? '결제 승인 요청에 실패하였습니다';
                    break;
                }
            } catch (\Throwable $th) {
                $res_array['ok'] = false;
                $res_array['msg'] = $th->getMessage() ?? '결제 승인 요청에 실패하였습니다';
                break;
            }

            $sql1 = "update tb_card_log set status = 1, paymentkey = '{$paymentkey}' where idx = {$tb_card_log_row['idx']} ";
            $res1 = $this->service_model->exec_sql($sql1);

            if (empty($res1)) {
                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
                break;
            }

            $res2 = $this->log_util->적립금충전($tb_card_log_row, 0, '충전', '마일리지 충전(휴대폰결제)'); // 적립0 , 차감1

            if (empty($res2)) {
                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
                break;
            }

            $real_amount = (int)$tb_card_log_row['real_amount'];

            $sql2 = "update tb_member set point = point + {$real_amount} where idx = {$login_member['idx']} ";
            $res2 = $this->service_model->exec_sql($sql2);

            // 21,021,000
            if (empty($res2)) {
                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
                break;
            }

            $충전금액 = number_format($tb_card_log_row['real_amount']);
            $res_array['msg'] = "{$충전금액}원이 충전되었습니다.";
        }

        set_cookie($res_array['ok'] ? 'point_alert' : 'point_fail_alert', rawurlencode($res_array['msg']), 15000);
        header('Location:/page/mile_list');
    }

    /**
     * * 토스페이먼트 가상계좌 성공시
     *
     * @return void
     */
    public function success_virtual_account()
    {
        $paymentkey = $_REQUEST['paymentKey'] ?? ''; // 결제 고유키
        $amount = $_REQUEST['amount'] ?? ''; // 결제 금액
        $orderid = $_REQUEST['orderId'] ?? ''; // 주문번호

        $res_array = [
            'ok' => true,
            'msg' => ''
        ];

        foreach ([1] as $proc) {

            if (empty($paymentkey)) {
                $res_array['ok'] = false;
                $res_array['msg'] = 'paymentkey empty';
                break;
            }
            if (empty($amount)) {
                $res_array['ok'] = false;
                $res_array['msg'] = 'amount empty';
                break;
            }
            if (empty($orderid)) {
                $res_array['ok'] = false;
                $res_array['msg'] = 'orderid empty';
                break;
            }

            $login_member = $this->member->get_login_member();

            if (empty($login_member)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '로그인 후 이용해주세요';
                break;
            }

            $tb_site_info_row = $this->service_model->get_tb_site_info('row');

            if (empty($tb_site_info_row)) {
                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
                break;
            }

            $min_save_fee = $tb_site_info_row['min_save_fee'] ?? 0;

            if ($amount < $min_save_fee) {
                $res_array['ok'] = false;
                $res_array['msg'] = "최소 결제금액은 {$min_save_fee}원 이상입니다";
                break;
            }

            $now_date = date('Y-m-d H:i:s');
            $tb_bank_data = [
                "midx"          => $login_member['idx'],
                "amount"        => $amount,
                "orderid"       => $orderid,
                "paymentkey"    => $paymentkey,
                'payment'       => '가상계좌',
                "status"        => 0,
                "regdate"       => $now_date
            ];

            try {

                $virtual_account_res = $this->toss->set_virtual_account([
                    'orderid'       => $orderid,
                    'amount'        => $amount,
                    'paymentkey'    => $paymentkey,
                    'member_name'   => $login_member['name'],
                ]);

                $virtual_account = $virtual_account_res['virtualAccount'] ?? [];
                $virtual_account_status = $virtual_account_res['status'] ?? [];

                if ($virtual_account_status != 'WAITING_FOR_DEPOSIT' || empty($virtual_account)) {
                    $res_array['ok'] = false;
                    $res_array['msg'] = $virtual_account_res['message'] ?? '가상계좌 발급 요청에 실패하였습니다';
                    break;
                }

                $accountinfo = $virtual_account['accountNumber'] ?? ''; // 계좌번호
                $bankcode = $virtual_account['bankCode'] ?? ''; // 은행코드
                $bankname = unserialize(BANK_CODE)["0" . $bankcode] ?? ''; // 은행명
                $tb_bank_data['acount_info'] = "가상계좌 {$accountinfo} ({$bankname})";
            } catch (\Throwable $th) {

                $res_array['ok'] = false;
                $res_array['msg'] = $th->getMessage() ?? '가상계좌 발급 요청에 실패하였습니다';
                break;
            }

            $insert_tb_bank_res = $this->service_model->insert_tb_bank(DEBUG, $tb_bank_data);

            if (empty($insert_tb_bank_res)) {

                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
                break;
            }

            $res_array['msg'] = "가상계좌 결제가 요청되었습니다.";

            /**
             * Array
(
    [paymentType] => NORMAL
    [orderId] => dureotkd123_20250406232322
    [paymentKey] => tgata20250406232327ooRY2
    [amount] => 11111
)
             */

            /**
             * {
  "mId": "tkemmar725j",
  "lastTransactionKey": "A26A98D3494F2B0AF69DDE2ED0FB9BBA",
  "paymentKey": "tkemm20250406232859lQDg2",
  "orderId": "dureotkd123_20250406232841",
  "orderName": "qwdqdwdqwdqw",
  "taxExemptionAmount": 0,
  "status": "WAITING_FOR_DEPOSIT",
  "requestedAt": "2025-04-06T23:28:59+09:00",
  "approvedAt": null,
  "useEscrow": false,
  "cultureExpense": false,
  "card": null,
  "virtualAccount": {
    "accountNumber": "X9018612307559",
    "accountType": "일반",
    "bankCode": "11",
    "customerName": "qwfqfwofwqom",
    "dueDate": "2025-04-13T23:28:59+09:00",
    "expired": false,
    "settlementStatus": "INCOMPLETED",
    "refundStatus": "NONE",
    "refundReceiveAccount": null
  },
  "transfer": null,
  "mobilePhone": null,
  "giftCertificate": null,
  "cashReceipt": null,
  "cashReceipts": null,
  "discount": null,
  "cancels": null,
  "secret": "ps_GjLJoQ1aVZ965EbZMQy13w6KYe2R",
  "type": "NORMAL",
  "easyPay": null,
  "country": "KR",
  "failure": null,
  "isPartialCancelable": true,
  "receipt": {
    "url": "https://dashboard.tosspayments.com/receipt/payment-detail/bank-transfer?transactionId=tkemm20250406232859lQDg2&ref=PX"
  },
  "checkout": {
    "url": "https://api.tosspayments.com/v1/payments/tkemm20250406232859lQDg2/checkout"
  },
  "currency": "KRW",
  "totalAmount": 11111,
  "balanceAmount": 11111,
  "suppliedAmount": 10101,
  "vat": 1010,
  "taxFreeAmount": 0,
  "method": "가상계좌",
  "version": "2022-11-16",
  "metadata": null
}
             */
        }


        set_cookie($res_array['ok'] ? 'point_alert' : 'point_fail_alert', rawurlencode($res_array['msg']), 15000);
        header('Location:/page/mile_inout_list');
    }

    /**
     * * 토스페이먼트 상품권결제 성공시
     *
     * @return void
     */
    public function success_gift()
    {

        printr($_REQUEST);
        exit;
    }

    /**
     * * 토스페이먼트 결제 실패시
     * @return void
     */
    public function fail()
    {
        $code = $_REQUEST['code'] ?? '';
        $message = $_REQUEST['message'] ?? '';



        alert("CODE:{$code} > {$message}");
    }

    /**
     * * 입금신청시
     * @return void
     */
    public function set_in_charge()
    {
        $acount_info = $_REQUEST['acount_info'];
        $payment = $_REQUEST['payment'];
        $amount = $_REQUEST['amount'];

        @session_start();

        $midx = $_SESSION['midx'] ?? '';

        $res_array = [
            'ok' => true,
            'msg' => '입금 신청이 완료 되었습니다'
        ];

        foreach ([1] as $proc) {

            if (empty($payment)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '결제수단을 선택해주세요';
                break;
            }
            if (empty($amount)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '금액을 입력해주세요';
                break;
            }
            if (empty($acount_info)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '계좌번호를 선택해주세요';
                break;
            }
            if (empty($midx)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '로그인 후 이용해주세요';
                break;
            }

            $tb_site_info_row = $this->service_model->get_tb_site_info('row');

            if (empty($tb_site_info_row)) {
                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
                break;
            }

            $min_save_fee = $tb_site_info_row['min_save_fee'] ?? 0;

            if ($amount < $min_save_fee) {
                $res_array['ok'] = false;
                $res_array['msg'] = "최소 결제금액은 {$min_save_fee}원 이상입니다";
                break;
            }

            $now_date = date('Y-m-d H:i:s');

            $insert_tb_bank_res = $this->service_model->insert_tb_bank(DEBUG, [
                "midx"  => $midx,
                "acount_info" => $acount_info,
                "amount" => $amount,
                "payment" => $payment,
                "status" => 0,
                "regdate" => $now_date
            ]);

            if (empty($insert_tb_bank_res)) {

                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
                break;
            }
        }

        echo json_encode($res_array);
    }

    /**
     * 
     * http://127.0.0.3/module/payment/test1
     * 이노페이 가상계좌 테스트..
     *
     * @return void
     */
    public function test1()
    {

        $res_virtaul = $this->innopay->make_virtual_account(100000);
    }

    /**
     * * 출금신청시
     */
    public function set_out_charge() {}

    private function layout_config()
    {

        $this->layout->setLayout("layout/none");
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [];
    }
}
