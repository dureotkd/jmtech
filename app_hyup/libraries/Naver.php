<?php
class naver
{

    public $CLIENT_ID = "kxyogapw13";
    public $CLIENT_SECRET = "7l2uQiNA1NZ7hvRuEPl9cm2OvuXrymw4OqF4vjNb";

    public function __construct()
    {

        $this->obj = &get_instance();
        $this->obj->load->library("ajax");
    }

    public function Geocoding($address)
    {

        $encodedAddress = urlencode($address);
        $url = "https://maps.apigw.ntruss.com/map-geocode/v2/geocode?query={$encodedAddress}";

        $headers = [
            "X-NCP-APIGW-API-KEY-ID: {$this->CLIENT_ID}",
            "X-NCP-APIGW-API-KEY: {$this->CLIENT_SECRET}"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode == 200) {
            $result = json_decode($response, true);

            if (isset($result['addresses'][0])) {
                $x = $result['addresses'][0]['x']; // 경도
                $y = $result['addresses'][0]['y']; // 위도
                $longName = $result['addresses'][0]['addressElements'][0]['longName'];
                $shortName = $result['addresses'][0]['addressElements'][0]['shortName'];
                $shortName = unserialize(LOCAL)[$shortName] ?? $shortName;
                return ['lng' => $x, 'lat' => $y, 'longName' => $longName, 'shortName' => $shortName];
            } else {
                return ['error' => '주소를 찾을 수 없습니다.'];
            }
        } else {
            return ['error' => "API 요청 실패. 상태 코드: {$statusCode}"];
        }
    }
}
