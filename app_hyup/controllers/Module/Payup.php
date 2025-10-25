<?php
class payup extends MY_Controller
{
    public $MID = "hihome";
    public $API_KEY = "16f36f0ec6dd46dc8fdcd30818260735";

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");

        $this->load->model('/Page/service_model');

        $this->load->helper('encrypt');
    }

    public function getAccessToken()
    {

        // POST 요청을 보낼 URL
        $url = "https://standard.testpayup.co.kr/auth/v1/accessToken";
        // 요청할 데이터
        $data = array(
            "merchantId" => $this->MID,
            "apiKey" => $this->API_KEY
        );

        // cURL 세션 초기화
        $ch = curl_init($url);

        // cURL 옵션 설정
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // 요청 보내고 응답 받기
        $response = curl_exec($ch);

        // 오류 처리
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            // 응답을 JSON으로 디코딩
            $responseData = json_decode($response, true);

            return json_encode($responseData, JSON_PRETTY_PRINT);
        }

        // cURL 세션 닫기
        curl_close($ch);
    }

    public function approvePayment()
    {

        $accessToken = $this->getAccessToken();

        printr($accessToken);
        exit;

        $url = "https://standard.testpayup.co.kr/api/v1/payment";

        $data = array(
            "transactionId" => "transactionId",
            "amount" => "amount",
            "orderNumber" => "orderNumber"
        );

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: ' . $accessToken
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        printr($response);
        exit;

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            $responseData = json_decode($response, true);

            if ($responseCode == 200) {
                if ($responseData["status"] === "SUCCESS") {
                    $dataMap = $responseData["data"];
                    header('Content-Type: application/json');
                    echo json_encode(array(
                        "responseCode" => $dataMap["responseCode"],
                        "responseMsg" => $dataMap["responseMsg"]
                    ), JSON_PRETTY_PRINT);
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(array(
                        "responseCode" => $responseData["messageCode"],
                        "responseMsg" => $responseData["message"]
                    ), JSON_PRETTY_PRINT);
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode($responseData, JSON_PRETTY_PRINT);
            }
        }

        curl_close($ch);
    }

    public function cancelPayment()
    {

        $accessToken = $this->getAccessToken();

        printr($accessToken);
        exit;

        $url = "https://standard.testpayup.co.kr/api/v1/payment";
        $data = array(
            "transactionId" => "transactionId",
            "cancelReason" => "취소사유",
        );

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: ' . $accessToken
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        printr($response);
        exit;
    }
}
