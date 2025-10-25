<?php

class login extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");
        $this->load->library("/Service/manager_service");

        $this->load->model('/Page/service_model');
    }

    public function index()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('/Admin/login_view', $view_data);
    }

    public function login_proc()
    {
        $user_id = $this->input->post('user_id');
        $user_pw = $this->input->post('user_pw');

        $res_arary = [
            'ok' => true,
            'msg' => '',
        ];

        try {
            $this->manager_service->login($user_id, $user_pw);
        } catch (Exception $e) {
            $res_arary['ok'] = false;
            $res_arary['msg'] = $e->getMessage();
        }

        echo json_encode($res_arary);
        exit;
    }

    public function logout()
    {
        $this->manager->logout();
        header("Location: /admin/login");
        exit;
    }

    private function layout_config()
    {

        $this->layout->setLayout("layout/admin");
        $this->layout->setCss([]);
        $this->layout->setBlank(true);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'banner',
        ];
    }
}
