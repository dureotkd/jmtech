<?php

/**
 * ^ ------------- 머니핀 API -------------
 * & https://docs.bizno.moneypin.biz/api/api-biz-search-controller-search
 * * 
 */
class Moneypin
{

    public function __construct()
    {
        $this->CLIENT_ID = 'a79a52a8-620a-4e86-9a2a-a9c34c1c6946';
        $this->CLIENT_SECRET = 'wafnFL/3/zOgNWaUm4nH14glGRUcIqFs8M9N8Khbl2k=';
    }

    private function getToken()
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.moneypin.biz/bizno/v1/auth/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "grantType": "ClientCredentials",
                "clientId": "' . $this->CLIENT_ID . '",
                "clientSecret": "' . $this->CLIENT_SECRET . '"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $json = json_decode($response, true);

        return $json['token'];
    }

    public function searchCompany($company_nums)
    {
        $token = $this->getToken();

        // company_nums 배열을 JSON으로 변환
        $payload = json_encode([
            "bizNoList" => $company_nums
        ], JSON_UNESCAPED_UNICODE);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.moneypin.biz/bizno/v1/biz/info/all',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $json = json_decode($response, true);

        return $json;
    }
}
