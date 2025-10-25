<?php

class User extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");
        $this->load->library("/Service/user_service");

        $this->load->model('/Page/service_model');
    }

    /**
     * 총판코드 생성 & 조회
     * 본사 부본사 코드 생성 & 조회
     * 전체 회원 조회
     */
    public function index()
    {
        $id = $this->input->get('id');

        $view_data =  [

            'layout_data'           => $this->layout_config(),
        ];

        if (!empty($id)) {
            // If an ID is provided, show the user detail view
            $this->layout->view('/Admin/user_detail_view', $view_data);
            return;
        }

        $this->layout->view('/Admin/user_view', $view_data);
    }


    private function layout_config()
    {

        $this->layout->setLayout("layout/admin");
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'user',
        ];
    }
}
