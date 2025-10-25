<?php

class sales_document extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");
        $this->load->library("/Service/user_service");

        $this->load->model('/Page/service_model');
    }

    public function index() {}

    # 매출(거래명세표)
    public function report()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config('report'),
        ];

        $this->layout->view('/Sales/report_view', $view_data);
    }

    # 매출세금계산서(현영/기타)
    public function tax_bill()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config('tax_bill'),
        ];

        $this->layout->view('/Sales/tax_bill_view', $view_data);
    }


    private function layout_config($sub_menu_code = '')
    {

        $this->layout->setLayout("layout/template");
        $this->layout->setTitle('브랜드 소개 | 제이엠테크');
        $this->layout->setDescription('제이엠테크 브랜드 소개 페이지입니다.');
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'sales',
            'sub_menu_code'    => $sub_menu_code,
        ];
    }
}
