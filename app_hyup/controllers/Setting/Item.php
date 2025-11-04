<?php

class item extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");
        $this->load->library("/Service/user_service");

        $this->load->model('/Page/service_model');
    }

    # 고객센터
    public function index()
    {


        $view_data =  [
            'layout_data'   => $this->layout_config(),
        ];

        $this->layout->view('/Setting/item_view', $view_data);
    }

    private function layout_config()
    {

        $this->layout->setLayout("layout/template");
        $this->layout->setTitle('브랜드 소개 | 제이엠테크');
        $this->layout->setDescription('제이엠테크 브랜드 소개 페이지입니다.');
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'setting',
            'sub_menu_code'    => 'item',
        ];
    }
}
