<?php
class Smartro
{
    // public $MID = PAYUP_DEBUG == true ? "standard_test" : "hihome";
    // public $API_KEY = PAYUP_API_KEY;
    public $MKEY = MKEY;
    public $CANCEL_PW = '646464'; // 취소 비밀번호 설정(필요시 설정, 미설정 시 빈 문자열)

    public function __construct()
    {
        $this->obj = &get_instance();

        $this->obj->load->model('/Page/service_model');
    }

    function approvePay($Tid = null, $TrAuthKey = null)
    {

        if (empty($Tid) || empty($TrAuthKey)) {

            return;
        }

        $json = json_encode([
            'Tid' => $Tid,
            'TrAuthKey' => $TrAuthKey
        ]);

        $url = SMARTRO_API . "/approval/urlCallApproval.do";
        $response = $this->Curl($url, $json, 0);

        /**
         * Array
(
    [CoNm] => 스마트로
    [Tid] => SMTNEW001m01012507251636283998
    [MerchantReserved] => 
    [CardQuota] => 00
    [AppCardCode] => 12
    [AppCardName] => NH농협
    [AcquCardCode] => 12
    [AcquCardName] => NH농협
    [CardMerchantNo] => 114964733
    [CardNum] => 40921675****082*
    [UsePoint] => 000000000
    [BalancePoint] => 000000000
    [CardInterest] => 0
    [ResultCode] => 3001
    [ResultMsg] => 카드 결제 성공
    [AuthDate] => 250725163656
    [AuthCode] => 53119044
    [GoodsName] => 액상 이눌린 스위트린
    [GoodsCnt] => 1
    [Amt] => 500
    [Moid] => Moid1234567890
    [Mid] => SMTNEW001m
    [MallUserId] => 
    [BuyerName] => 홍길동
    [PayMethod] => CARD
    [BuyerEmail] => wGLqAiLyZKeWxxJn0JPUXdJMNeAKwGOVn58cKOSNur0=
    [ParentEmail] => P_$IS_PC=Y&P_$CARD_CL=0
    [SubId] => 
    [MallReserved] => 
    [EdiDate] => 20250725110535
    [SignValue] => twzuMbjsyp+7WOlCiOf41+NWfof5Z7B5GWndn0/xxTI=
    [BuyerAuthNum] => 
    [BuyerTel] => 01012345678
    [EscrowCd] => 0
    [GreenDeposit] => 000000000000
    [Currency] => KRW
    [MerInterestCl] => N
    [CardType] => 0
    [CardOwnerType] => 0
)
         */
        $response = json_decode($response, true);

        if (empty($response['Tid'])) {

            throw new Exception("결제 승인 실패 : " . $response['ResultMsg'] ?? '알 수 없는 오류');
        } else {

            $response['created_at'] = date('Y-m-d H:i:s');

            $insert_res = $this->obj->service_model->insert_smartro_payment_log(DEBUG, $response);

            if (!$insert_res) {

                throw new Exception("결제 승인 실패 : " . DB_ERR_MSG);
            }

            if (strpos($response['ResultMsg'] ?? '', '성공') === false) {

                throw new Exception("결제 승인 실패: " . ($response['ResultMsg'] ?? '알 수 없는 오류'));
            }

            return $insert_res;
        }
    }

    function cancelPay($order_id, $payment_log_id)
    {
        if (empty($order_id) || empty($payment_log_id)) {

            throw new Exception('주문 ID 또는 결제 로그 ID가 비어 있습니다.');
        }

        $smartro_payment_log = $this->obj->service_model->get_smartro_payment_log('row', [
            "id = '{$payment_log_id}'"
        ]);

        if (empty($smartro_payment_log)) {

            throw new Exception('존재하지 않는 결제입니다.');
        }

        $Tid = $smartro_payment_log['Tid'] ?? '';
        $Mid = $smartro_payment_log['Mid'] ?? '';
        $Amt = $smartro_payment_log['Amt'] ?? '';

        if (empty($Tid) || empty($Mid) || empty($Amt)) {

            throw new Exception('결제 정보가 불완전합니다.');
        }

        $url = SMARTRO_API . "/approval/cancel.do";
        // $Tid = "";            // 취소 요청할 Tid 입력
        // $Mid = "";            // 발급받은 테스트 Mid 설정(Real 전환 시 운영 Mid 설정)
        // $CancelAmt = "1004";    // 취소할 거래금액
        $CancelSeq = "1";        // 취소차수(기본값: 1, 부분취소 시마다 차수가 1씩 늘어남. 첫번째 부분취소=1, 두번째 부분취소=2, ...)
        $PartialCancelCode = "0";        // 0: 전체취소, 1: 부분취소
        $MerchantKey = $this->MKEY;              // 발급받은 테스트 상점키 설정(Real 전환 시 운영 상점키 설정)

        // 검증값 SHA256 암호화(Tid + MerchantKey + CancelAmt + PartialCancelCode)
        $HashData = base64_encode(hash('sha256', $Tid . $MerchantKey . $Amt . $PartialCancelCode, true));

        // 취소 요청 파라미터 셋팅
        $paramData = array(
            'SERVICE_MODE' => 'CL1',
            'Tid' => $Tid,
            'Mid' => $Mid,
            'CancelAmt' => $Amt,
            'CancelPwd' => $this->CANCEL_PW,
            'CancelMsg' => '',
            'CancelSeq' => $CancelSeq,
            'PartialCancelCode' => $PartialCancelCode,
            // 과세, 비과세, 부가세 셋팅(부가세 직접 계산 가맹점의 경우 각 값을 계산하여 설정해야 합니다.)
            'CancelTaxAmt' => '',
            'CancelTaxFreeAmt' => '',
            'CancelVatAmt' => '',
            // 분할정산 사용 가맹점의 경우, DivideInfo 파라미터를 가맹점에 맞게 설정해 주세요. (일반연동 참고)
            'DivideInfo' => '',
            // HASH 설정 (필수)
            'HashData' => $HashData
        );

        // json 데이터 AES256 암호화
        $EncData = base64_encode(openssl_encrypt(json_encode($paramData), 'aes-256-cbc', substr($MerchantKey, 0, 32), true, str_repeat(chr(0), 16)));
        $body = array(
            'EncData' => $EncData,
            'Mid' => $Mid
        );

        // json data
        $body = json_encode($body);

        $ret = $this->Curl($url, $body, 0);
        $ret = json_decode($ret, true);

        // SUCCESS
        $this->obj->service_model->update_order_item(DEBUG, [
            'status' => 'canceled',
            'cancel_date' => date('Y-m-d H:i:s'),
        ], [
            "id = '{$order_id}'"
        ]);

        return $ret;
    }

    function Curl($url, $post_data, $http_status, $header = null)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        // post_data
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HEADER, false);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        $body = null;
        // error
        if (!$response) {
            $body = curl_error($ch);
            // HostNotFound, No route to Host, etc  Network related error
            $http_status = -1;
        } else {
            //parsing http status code
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (!is_null($header)) {
                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

                $header = substr($response, 0, $header_size);
                $body = substr($response, $header_size);
            } else {
                $body = $response;
            }
        }
        curl_close($ch);
        return $body;
    }
}
