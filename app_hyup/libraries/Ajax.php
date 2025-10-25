<?php
class ajax
{
    public function request($url, $data = [], $header = [], $method = "POST")
    {
        $method = strtoupper($method);
        $headers = array("Content-Type: application/json");

        if ($header) {
            $headers = array_merge($headers, $header);
        }

        $ch = curl_init();

        // GET 방식일 경우 쿼리스트링 붙이기
        if ($method === "GET" && !empty($data)) {
            $url .= '?' . http_build_query($data);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 12);

        // POST, PUT 등은 바디에 JSON 데이터 전송
        if ($method !== "GET") {
            $post_data = json_encode($data);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }

        $response = curl_exec($ch);

        if (curl_error($ch)) {
            $curl_data = null;
        } else {
            $curl_data = $response;
        }

        curl_close($ch);

        return json_decode($curl_data, true);
    }
}
