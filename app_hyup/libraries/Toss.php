<?php
class toss
{
    public function __construct()
    {
        $this->obj = &get_instance();
        $this->obj->load->library("ajax"); // ajax 클래스 로드
    }

    /**
     * Array
(
    [mId] => tkemmar725j
    [lastTransactionKey] => txrd_a01jr59j1v8jdd42yme4ysewprr
    [paymentKey] => tkemm20250406191502hBXr3
    [orderId] => dureotkd123_20250406191453
    [orderName] => 마일리지 충전
    [taxExemptionAmount] => 0
    [status] => DONE
    [requestedAt] => 2025-04-06T19:15:02+09:00
    [approvedAt] => 2025-04-06T19:15:23+09:00
    [useEscrow] => 
    [cultureExpense] => 
    [card] => Array
        (
            [issuerCode] => 91
            [acquirerCode] => 91
            [number] => 40921675****082*
            [installmentPlanMonths] => 0
            [isInterestFree] => 
            [interestPayer] => 
            [approveNo] => 00000000
            [useCardPoint] => 
            [cardType] => 신용
            [ownerType] => 개인
            [acquireStatus] => READY
            [amount] => 1111
        )

    [virtualAccount] => 
    [transfer] => 
    [mobilePhone] => 
    [giftCertificate] => 
    [cashReceipt] => 
    [cashReceipts] => 
    [discount] => 
    [cancels] => 
    [secret] => ps_EP59LybZ8Bvv1nZd65KG86GYo7pR
    [type] => NORMAL
    [easyPay] => 
    [country] => KR
    [failure] => 
    [isPartialCancelable] => 1
    [receipt] => Array
        (
            [url] => https://dashboard.tosspayments.com/receipt/redirection?transactionId=tkemm20250406191502hBXr3&ref=PX
        )

    [checkout] => Array
        (
            [url] => https://api.tosspayments.com/v1/payments/tkemm20250406191502hBXr3/checkout
        )

    [currency] => KRW
    [totalAmount] => 1111
    [balanceAmount] => 1111
    [suppliedAmount] => 1010
    [vat] => 101
    [taxFreeAmount] => 0
    [method] => 카드
    [version] => 2022-11-16
    [metadata] => 
)
     */
    public function confirm_payment($params)
    {

        try {

            // 토스 시크릿키 Base64 인코딩
            $secretKey = TOSS_API_SECRET_KEY . ":";
            $encodedString = base64_encode($secretKey);

            // 승인 요청 데이터
            $postData = array(
                'paymentKey' => $params['paymentkey'],
                'orderId' => $params['orderid'],
                'amount' => intval($params['amount'])
            );

            // 헤더 구성
            $headers = array(
                "Authorization: Basic " . $encodedString
            );

            $url = "https://api.tosspayments.com/v1/payments/confirm";
            $response = $this->obj->ajax->request($url, $postData, $headers);

            // 응답 체크
            if (isset($response['code']) && $response['code'] !== null) {
                // 에러 응답일 경우
                throw new Exception($response['message'] . " (" . $response['code'] . ")");
            }

            return $response; // 승인 완료 데이터 반환
        } catch (Exception $e) {

            $err_msg = $e->getMessage();

            throw new Exception($err_msg);
        }
    }

    public function get_payment_info($orderid = '')
    {

        try {

            // 토스 시크릿키 Base64 인코딩
            $secretKey = TOSS_API_SECRET_KEY . ":";
            $encodedString = base64_encode($secretKey);

            // 승인 요청 데이터
            $postData = [];

            // 헤더 구성
            $headers = array(
                "Authorization: Basic " . $encodedString
            );

            $url = "https://api.tosspayments.com/v1/payments/orders/" . $orderid; // 주문 ID를 URL에 추가
            $response = $this->obj->ajax->request($url, $postData, $headers, "GET");

            // 응답 체크
            if (isset($response['code']) && $response['code'] !== null) {
                // 에러 응답일 경우
                throw new Exception($response['message'] . " (" . $response['code'] . ")");
            }

            return $response; // 승인 완료 데이터 반환
        } catch (Exception $e) {

            $err_msg = $e->getMessage();

            throw new Exception($err_msg);
        }
    }

    public function set_virtual_account($params)
    {

        try {

            // 토스 시크릿키 Base64 인코딩
            $secretKey = TOSS_API_SECRET_KEY . ":";
            $encodedString = base64_encode($secretKey);

            // 승인 요청 데이터
            $postData = [
                'orderId' => $params['orderid'],
                'amount' => intval($params['amount']),
                'bank' => '011',
                'customerName' => $params['member_name'],
                'orderName' => '게임마켓(머쉬빌리지)'
            ];

            // 헤더 구성
            $headers = array(
                "Authorization: Basic " . $encodedString
            );

            $url = "https://api.tosspayments.com/v1/virtual-accounts"; // 주문 ID를 URL에 추가
            $response = $this->obj->ajax->request($url, $postData, $headers, "POST");

            // 응답 체크
            if (isset($response['code']) && $response['code'] !== null) {
                // 에러 응답일 경우
                throw new Exception($response['message'] . " (" . $response['code'] . ")");
            }

            return $response; // 승인 완료 데이터 반환
        } catch (Exception $e) {

            $err_msg = $e->getMessage();

            throw new Exception($err_msg);
        }
    }
}
