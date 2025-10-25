<?php

class cron extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('toss');
        $this->load->library('log_util');

        $this->load->model('/Page/service_model');
    }

    /**
     * 
     *
     * @return void
     */
    public function review()
    {

        // json 불러와서 
        $json = file_get_contents("C:\\laragon\\www\\mosihealth\\assets\\app_hyup\\review.json");
        // json 디코딩
        $data = json_decode($json, true);

        foreach ($data as $item) {

            $user_login_id = $item['user_id'];
            $star = $item['star'];
            $content = $item['content'];
            $created_at = $item['created_at'];

            $this->service_model->insert_review(DEBUG, [
                'user_id' => 0,
                'product_id' => 4,
                'user_login_id' => $user_login_id,
                'rating' => $star,
                'content' => $content,
                'created_at' => $created_at
            ]);
        }
    }
}
