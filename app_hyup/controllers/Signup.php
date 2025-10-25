<?php

class signup extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
            "/Service/user_service",
        ]);

        $this->load->model('/Page/service_model');
    }

    public function index()
    {
        $agree = $this->input->get('agree');

        $view_data =  [
            'layout_data'           => $this->layout_config(),
            'agree'                 => $agree,
        ];

        $this->layout->view('signup_view', $view_data);
    }

    public function signup_proc()
    {
        $res_array = [
            'ok'    => true,
            'msg'   => '회원가입이 완료되었습니다',
        ];

        $user_id = $this->input->post('user_id');
        $password = $this->input->post('password');
        $repassword = $this->input->post('repassword');
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $email_domain = $this->input->post('email_domain');
        $phone = $this->input->post('phone');
        $store_code = $this->input->post('store_code');
        $market_agree = $this->input->post('market_agree');

        try {

            $this->user_service->join([
                'user_id'       => $user_id,
                'email'         => $email . '@' . $email_domain,
                'password'      => $password,
                'repassword'    => $repassword,
                'name'          => $name,
                'phone'         => $phone,
                'store_code'    => $store_code,
                'market_agree'  => $market_agree,
            ]);
        } catch (Exception $e) {

            $res_array = [
                'ok'    => false,
                'msg'   => $e->getMessage(),
            ];

            echo json_encode($res_array);
            return;
        }

        echo json_encode($res_array);
    }

    private function layout_config($params = [])
    {
        $title = $params['title'] ?? '회원가입 | 제이엠테크';
        $description = $params['description'] ?? '제이엠테크 회원가입 페이지입니다.';

        $this->layout->setLayout("layout/blank");
        $this->layout->setTitle($title);
        $this->layout->setDescription($description);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'banner',
        ];
    }
}
