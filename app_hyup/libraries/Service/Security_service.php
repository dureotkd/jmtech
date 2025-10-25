<?php
class Security_service
{
    protected $obj;
    protected $secret = '6Lf_6YkrAAAAACKWD7OsuG8dgQrFZY_bW8EQ3eqI';

    public function __construct()
    {

        $this->obj = &get_instance();
    }

    public function recapcha()
    {
        $response = $_POST['g-recaptcha-response'] ?? '';

        // 3. Google에 검증 요청
        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $this->secret,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR'],
        ];

        // 4. POST 요청 전송
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ]
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents($verifyUrl, false, $context);
        $resultJson = json_decode($result);

        // 5. 결과 확인
        if (!empty($resultJson->success)) {
            // reCAPTCHA 인증 성공
            return true;
        } else {
            throw new Exception('reCAPTCHA 인증에 실패했습니다');
        }
    }
}
