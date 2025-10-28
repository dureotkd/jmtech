<?php

class template_code
{

    public function __construct()
    {
        $this->obj = &get_instance();

        // 레이아웃 코드 모델 로드
        $this->obj->load->library('/Service/user_service');

        $this->obj->load->model('template_code_model');
        $this->obj->load->model('/Page/service_model');
    }

    public function getUseData($aside = false)
    {
        @session_start();


        $login_user = $this->obj->user_service->getLoginUser();

        $menus = $this->get_menus();

        $datas =  [
            'login_user' => $login_user,
            'menus' => $menus,
        ];

        return $datas;
    }

    private function get_menus()
    {

        $menus = unserialize(MENU);

        return $menus;
    }
}
