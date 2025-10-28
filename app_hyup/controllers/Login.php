<?php

class login extends MY_Controller
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
        $sec = $this->input->get('sec') ?? 'gi';

        $view_data =  [
            'layout_data'           => $this->layout_config(),
            'sec'                   => $sec,
        ];

        $this->layout->view('login_view', $view_data);
    }

    public function login_proc()
    {

        $res_array = [
            'ok'    => true,
            'msg'   => '',
        ];

        $user_id = $this->input->post('user_id');
        $password = $this->input->post('password');

        try {

            $this->user_service->login($user_id, $password);
        } catch (Exception $e) {

            $res_array = [
                'ok'    => false,
                'msg'   => $e->getMessage(),
            ];
        }

        echo json_encode($res_array);
    }

    public function find_id()
    {

        $res_array = [
            'ok'    => true,
            'msg'   => '',
            'data'  => [],
        ];

        $email = $this->input->post('email');

        try {
            $user = $this->user_service->findId($email);

            if (!empty($user)) {

                $res_array['data'] = "회원님의 아이디는 {$user['user_id']} 입니다.";
            }
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['data'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    /**
     * 비밀번호 찾기
     * 
     * @throws Exception
     */
    public function find_password()
    {

        $res_array = [
            'ok'    => true,
            'msg'   => '비밀번호가 초기화 되었습니다.',
            'data'  => '',
        ];

        $user_id = $this->input->post('user_id');

        try {
            $this->user_service->findPassword($user_id);
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['data'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    /**
     * 비밀번호 재설정
     * https://mosihealth.test/login/reset_password?reset_token=123
     *
     * @return void
     */
    public function reset_password()
    {

        $res_array = [
            'ok'    => true,
            'data'   => '',
        ];

        $reset_token = $this->input->post('reset_token');
        $password = $this->input->post('password');
        $repassword = $this->input->post('repassword');

        try {
            $this->user_service->changePassword($reset_token, $password, $repassword);
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['data'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    public function logout()
    {

        $this->user_service->logout();
    }

    private function layout_config($params = [])
    {
        $title = $params['title'] ?? '로그인 ';

        $this->layout->setLayout("layout/blank");
        $this->layout->setTitle($title);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'banner',
        ];
    }
}
