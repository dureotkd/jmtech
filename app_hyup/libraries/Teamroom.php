<?php
class teamroom
{


    public function __construct()
    {

        $this->obj = &get_instance();

        $this->hooks = [
            '개발자' => 'https://teamroom.nate.com/api/webhook/37340b64/mlivCc0PSXVdAWWGwWRGuJzv',
        ];
    }

    public function send($hook, $msg)
    {

        $webhook = $this->hooks[$hook];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $webhook); // Webhook URL
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'content=' . urlencode($msg)); // 메시지
        $return = curl_exec($ch);
        curl_close($ch);

        return $return;
    }
}
