<?php
class Manager_service
{
    protected $obj;

    public function __construct()
    {

        $this->obj = &get_instance();
        $this->obj->load->library("ajax");
        $this->obj->load->library("php_email");
        $this->obj->load->model("/Page/service_model");
    }

    public function login($user_id, $password)
    {
        // 아이디로 사용자 정보 조회
        $manager = $this->obj->service_model->get_manager('row', [
            "user_id = '{$user_id}'"
        ]);

        if (empty($manager)) {
            throw new Exception('아이디를 다시 한번 확인해주세요');
        }

        // 비밀번호 확인
        if ($manager['password'] !== $password) {
            throw new Exception('비밀번호를 다시 한번 확인해주세요.');
        }

        // 로그인 세션 설정
        session_start();
        $_SESSION['mid'] = $manager['id'];

        return true;
    }

    // 로그인 사용자 정보 조회
    public function getLoginUser()
    {

        session_start();

        if (isset($_SESSION['mid'])) {
            $manager = $this->obj->service_model->get_manager('row', [
                "id = '{$_SESSION['mid']}'"
            ]);

            return $manager;
        }

        return false;
    }

    # 로그아웃
    public function logout()
    {

        session_start();
        unset($_SESSION['mid']);
        session_destroy();

        return true;
    }
}
