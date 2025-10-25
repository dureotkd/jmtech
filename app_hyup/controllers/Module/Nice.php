<?php
class nice extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");

        $this->load->model('/Page/service_model');

        $this->load->helper('encrypt');
    }

    public function success_cert()
    {
        $sitecode = "BO706";  // NICE에서 발급한 사이트 코드
        $sitepasswd = "vZrtowQ50Cfh";  // NICE에서 발급한 사이트 패스워드

        $cb_encode_path = NICE_PROGRAM_PATH;

        $enc_data = $_REQUEST["EncodeData"] ?? '';        // 암호화된 결과 데이타

        if (preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {
            alert_close('입력 값 확인이 필요합니다');
            exit;
        } // 문자열 점검 추가.

        if (base64_encode(base64_decode($enc_data)) != $enc_data) {
            alert_close('입력 값 확인이 필요합니다');
            exit;
        }

        # http://115.68.223.11/nice/checkplus_success.php?EncodeData=AgAFQk83MDa7YDzbUOLbnousTcnxODZxZHWuYf2qo5fcj7P1JtnyyvppQjcVOsxRyPZSDq7umAouUlPZo4igUBbJPfyxMlxycjTVnbOkvqqtshyThGsgQ9GeQprjA0q3BIA4rAqzEPsgr8X%2Bc6oHK5bTwmisAkWVYOXr5nOXPAX0IvY0apjk7GopDbv4vHWKTDe/Hl/XGwpJPg%2BGNz1W6PWsCjxQlOYv3gOF%2BVRuJlQnH3%2BlhcqOAJo2OumxZkL9j8ZL2G/QNRsawskNX2k1gr/hR%2BBUWz0f%2B2F3k2nBaX8jXhfsuA7fbCKDTTafkbr37Ljgu0mtfuKjxyS2XaatynY/sznn5k%2B5G1FXo6jGf/rHQXLY8LT9TaYoWTmN5zkHP68Aa5BkxwQ/SQTBV1g0qE/bu0Zq2GG9DNuCOoOxBJpkoGORPLHnfjCZzvRniDxGdgVo/yCnpKpTxYGWjr1MHzfcoktqEXvq/JxaKpCacVI1z0BzlhqbvLN2kLqNd7qoTFiBZxk4EYdqKIPdG1pFP69vRMJ6rT5dEvvOlkDqTZdXkFNyVk4LcDsYdsfhHSD4KoHvFNJJijMWc/iTTeRBohOf5A4Kgtqi
        if ($enc_data != "") {
            $plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;
            $plaindata = iconv("EUC-KR", "UTF-8//IGNORE", $plaindata);  // 인코딩 변환
            //echo "[plaindata]  " . $plaindata . "<br>";

            if ($plaindata == -1) {
                $returnMsg  = "암/복호화 시스템 오류";
            } else if ($plaindata == -4) {
                $returnMsg  = "복호화 처리 오류";
            } else if ($plaindata == -5) {
                $returnMsg  = "HASH값 불일치 - 복호화 데이터는 리턴됨";
            } else if ($plaindata == -6) {
                $returnMsg  = "복호화 데이터 오류";
            } else if ($plaindata == -9) {
                $returnMsg  = "입력값 오류";
            } else if ($plaindata == -12) {
                $returnMsg  = "사이트 비밀번호 오류";
            } else {
                // 복호화가 정상적일 경우 데이터를 파싱합니다.
                $ciphertime = `$cb_encode_path CTS $sitecode $sitepasswd $enc_data`;    // 암호화된 결과 데이터 검증 (복호화한 시간획득)

                $_data_ = explode(":", $plaindata);
                $requestnumber = substr($_data_[2], 0, -1); // 맨 끝 1글자 제거
                $str = substr($_data_[18], 0, -1); // 맨 끝 1글자 제거

                @session_start();

                if (strcmp($_SESSION["REQ_SEQ"], $requestnumber) != 0) {
                    alert_close('올바른 경로로 접근하시기 바랍니다');
                    exit;
                }

                // $_data_[18] // 인증 후 휴대폰번호
                $formattedNumber = formatPhoneNumber($str);

                $tb_member = $this->service_model->get_tb_member('row', [
                    "cell = '{$formattedNumber}'"
                ]);

                if (empty($tb_member)) {
                    alert_close('일치하는 회원 정보가 없습니다');
                    exit;
                }

                $layout_data = $this->layout_config();

                $this->layout->view('Nice/success_view', [
                    'layout_data' => $layout_data,
                    'data' => $tb_member,
                ]);
            }
        }
    }
    public  function success_pw_cert()
    {

        $sitecode = "BO706";  // NICE에서 발급한 사이트 코드
        $sitepasswd = "vZrtowQ50Cfh";  // NICE에서 발급한 사이트 패스워드

        // Linux = /절대경로/ , Window = D:\\절대경로\\ , D:\절대경로\
        $cb_encode_path = NICE_PROGRAM_PATH;

        $enc_data = $_REQUEST["EncodeData"];        // 암호화된 결과 데이타

        //////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
        if (preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {
            echo "입력 값 확인이 필요합니다 : " . $match[0];
            exit;
        } // 문자열 점검 추가.
        if (base64_encode(base64_decode($enc_data)) != $enc_data) {
            echo "입력 값 확인이 필요합니다";
            exit;
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////

        if ($enc_data != "") {
            $plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;
            $plaindata = iconv("EUC-KR", "UTF-8//IGNORE", $plaindata);  // 인코딩 변환
            //echo "[plaindata]  " . $plaindata . "<br>";

            if ($plaindata == -1) {
                $returnMsg  = "암/복호화 시스템 오류";
            } else if ($plaindata == -4) {
                $returnMsg  = "복호화 처리 오류";
            } else if ($plaindata == -5) {
                $returnMsg  = "HASH값 불일치 - 복호화 데이터는 리턴됨";
            } else if ($plaindata == -6) {
                $returnMsg  = "복호화 데이터 오류";
            } else if ($plaindata == -9) {
                $returnMsg  = "입력값 오류";
            } else if ($plaindata == -12) {
                $returnMsg  = "사이트 비밀번호 오류";
            } else {
                // 복호화가 정상적일 경우 데이터를 파싱합니다.
                $ciphertime = `$cb_encode_path CTS $sitecode $sitepasswd $enc_data`;    // 암호화된 결과 데이터 검증 (복호화한 시간획득)

                $_data_ = explode(":", $plaindata);
                $requestnumber = substr($_data_[2], 0, -1); // 맨 끝 1글자 제거
                $str = substr($_data_[18], 0, -1); // 맨 끝 1글자 제거

                @session_start();

                if (strcmp($_SESSION["REQ_SEQ"], $requestnumber) != 0) {
                    alert_close('올바른 경로로 접근하시기 바랍니다');
                    exit;
                }

                // $_data_[18] // 인증 후 휴대폰번호
                $formattedNumber = formatPhoneNumber($str);

                $data = $this->service_model->get_tb_member('row', [
                    "cell = '{$formattedNumber}'"
                ]);

                if (!$data['idx']) {
                    alert_close('일치하는 회원 정보가 없습니다');
                    exit;
                }

                $_SESSION['find_idx'] = $data['idx'];

                $layout_data = $this->layout_config();

                $this->layout->view('Nice/success_pw_view', [
                    'layout_data' => $layout_data,
                    'data' => $data,
                ]);
            }
        }
    }

    public function fail_cert()
    {
        //**************************************************************************************************************
        //NICE평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED

        //서비스명 :  체크플러스 - 안심본인인증 서비스
        //페이지명 :  체크플러스 - 결과 페이지

        //보안을 위해 제공해드리는 샘플페이지는 서비스 적용 후 서버에서 삭제해 주시기 바랍니다.
        //**************************************************************************************************************

        $sitecode = "BO706";  // NICE에서 발급한 사이트 코드
        $sitepasswd = "vZrtowQ50Cfh";  // NICE에서 발급한 사이트 패스워드

        // Linux = /절대경로/ , Window = D:\\절대경로\\ , D:\절대경로\
        $cb_encode_path = "/home/gamematket/public_html/lib/CPClient_linux_x64";

        $enc_data = $_REQUEST["EncodeData"];        // 암호화된 결과 데이타

        //////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
        if (preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {
            echo "입력 값 확인이 필요합니다 : " . $match[0];
            exit;
        } // 문자열 점검 추가.
        if (base64_encode(base64_decode($enc_data)) != $enc_data) {
            echo "입력 값 확인이 필요합니다";
            exit;
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////

        if ($enc_data != "") {

            $plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;        // 암호화된 결과 데이터의 복호화
            echo "[plaindata] " . $plaindata . "<br>";

            if ($plaindata == -1) {
                $returnMsg  = "암/복호화 시스템 오류";
            } else if ($plaindata == -4) {
                $returnMsg  = "복호화 처리 오류";
            } else if ($plaindata == -5) {
                $returnMsg  = "HASH값 불일치 - 복호화 데이터는 리턴됨";
            } else if ($plaindata == -6) {
                $returnMsg  = "복호화 데이터 오류";
            } else if ($plaindata == -9) {
                $returnMsg  = "입력값 오류";
            } else if ($plaindata == -12) {
                $returnMsg  = "사이트 비밀번호 오류";
            } else {
                // 복호화가 정상적일 경우 데이터를 파싱합니다.
                $ciphertime = `$cb_encode_path CTS $sitecode $sitepasswd $enc_data`;    // 암호화된 결과 데이터 검증 (복호화한 시간획득)

                $requestnumber = GetValue($plaindata, "REQ_SEQ");
                $errcode = GetValue($plaindata, "ERR_CODE");
                $authtype = GetValue($plaindata, "AUTH_TYPE");

                $layout_data = $this->layout_config();

                $this->layout->view('Nice/fail_view', [
                    'layout_data' => $layout_data,

                    'ciphertime' => $ciphertime,
                    'requestnumber' => $requestnumber,
                    'errcode' => $errcode,
                    'authtype' => $authtype,
                ]);
            }
        }
    }


    private function layout_config()
    {

        $this->layout->setLayout("layout/none");
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [];
    }
}
