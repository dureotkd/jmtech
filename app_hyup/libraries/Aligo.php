<?php
class aligo
{
    public function __construct()
    {

        $this->obj = &get_instance();

        $this->obj->load->model("/Page/service_model");
    }

    public function send_sms($member_idx, $msg = '')
    {
        $res_array = [];

        foreach ([1] as $process) {

            $tb_member_row = $this->obj->service_model->get_tb_member_row([
                "idx = {$member_idx}"
            ]);

            if (empty($tb_member_row)) {
                throw new Error('tb_member_row empty');
            }

            $cell         = $tb_member_row['cell'];
            $id         = $tb_member_row['id'];

            if (empty($cell)) {
                throw new Error('휴대전화 번호가 존재하지 않습니다');
            }

            $now_date = date('Y-m-d H:i:s');

            /****************** 인증정보 시작 ******************/
            $sms_url = "https://apis.aligo.in/send/"; // 전송요청 URL
            // $sms['user_id'] = "dbalslzk12"; // SMS 아이디
            // $sms['key'] = "w4l6r8k7ppgp104itti9pdrpp7zbgj3l"; //인증키
            $sms['user_id'] = "dureotkd123"; // SMS 아이디
            $sms['key'] = "0isuyvc9ie4wwvz6q5ic7jhh22lgdfd7"; //인증키

            /****************** 인증정보 끝 ********************/

            $sms['msg'] = stripslashes("[게임마켓]\n" . $msg . "\n" . "문의 : 1644-4176");
            $sms['receiver'] = $cell;
            $sms['destination'] = '';
            $sms['sender'] = '010-5653-9944';
            $sms['rdate'] = '';
            $sms['rtime'] = '';
            $sms['testmode_yn'] = "N";
            $sms['title'] = '게임마켓';
            $sms['msg_type'] = '';

            $host_info = explode("/", $sms_url);
            $port = $host_info[0] == 'https:' ? 443 : 80;

            $oCurl = curl_init();
            curl_setopt($oCurl, CURLOPT_PORT, $port);
            curl_setopt($oCurl, CURLOPT_URL, $sms_url);
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sms);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            $ret = curl_exec($oCurl);
            curl_close($oCurl);

            $retArr = json_decode($ret); // 결과배열

            $this->obj->service_model->insert_tb_sms_send_log(false, [
                "phone" => $cell,
                "id" => $id,
                "msg" => $msg,
                'status' => '1',
                'type' => 'SMS',
                'regdate' => $now_date,
            ]);
        }
    }

    public function send_sms_by_phone($phone, $id = '', $title = '', $msg = '')
    {
        $res_array = [];

        foreach ([1] as $process) {

            if (empty($phone)) {
                throw new Error('휴대전화 번호가 존재하지 않습니다');
            }

            $now_date = date('Y-m-d H:i:s');

            /****************** 인증정보 시작 ******************/
            $sms_url = "https://apis.aligo.in/send/"; // 전송요청 URL
            // $sms['user_id'] = "dbalslzk12"; // SMS 아이디
            // $sms['key'] = "w4l6r8k7ppgp104itti9pdrpp7zbgj3l"; //인증키

            $sms['user_id'] = "dureotkd123"; // SMS 아이디
            $sms['key'] = "0isuyvc9ie4wwvz6q5ic7jhh22lgdfd7"; //인증키

            /****************** 인증정보 끝 ********************/

            $sms['msg'] = stripslashes("[게임마켓]\n" . $msg . "\n" . "문의 : 1644-4176");
            $sms['receiver'] = $phone;
            $sms['destination'] = '';
            $sms['sender'] = '010-5653-9944';
            $sms['rdate'] = '';
            $sms['rtime'] = '';
            $sms['testmode_yn'] = "N";
            $sms['title'] = '';
            $sms['msg_type'] = '';

            $host_info = explode("/", $sms_url);
            $port = $host_info[0] == 'https:' ? 443 : 80;

            $oCurl = curl_init();
            curl_setopt($oCurl, CURLOPT_PORT, $port);
            curl_setopt($oCurl, CURLOPT_URL, $sms_url);
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sms);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            $ret = curl_exec($oCurl);
            curl_close($oCurl);

            $retArr = json_decode($ret); // 결과배열

            $this->obj->service_model->insert_tb_sms_send_log(false, [
                "phone" => $phone,
                "id" => $id,
                "msg" => $msg,
                'status' => '1',
                'type' => 'SMS',
                'regdate' => $now_date,
            ]);
        }
    }
}
