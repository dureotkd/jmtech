<?php

class business extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");
        $this->load->library("/Service/user_service");

        $this->load->model('/Page/service_model');
    }

    # 비즈니스
    public function index()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('business_view', $view_data);
    }

    # 카페 사업자 안내
    public function guide()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('business_bulk_view', $view_data);
    }

    # 쇼핑하기
    public function space()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('business_space_view', $view_data);
    }

    # 협업문의
    public function partnership()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('business_partnership_view', $view_data);
    }

    private function layout_config()
    {

        $this->layout->setLayout("layout/template");
        $this->layout->setTitle('브랜드 소개 | 제이엠테크');
        $this->layout->setDescription('제이엠테크 브랜드 소개 페이지입니다.');
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'banner',
        ];
    }
}
