<?php

class Sales_profit extends MY_Controller
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
    public function partner_report()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config('partner_report'),
        ];

        $this->layout->view('/Sales_profit/partner_report_view', $view_data);
    }

    # 견적서
    public function sales_report()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config('sales_report'),
        ];

        $this->layout->view('/Sales_profit/sales_report_view', $view_data);
    }

    # 수주서
    public function profit_report()
    {

        $view_data =  [
            'faqs'          => '',
            'layout_data'   => $this->layout_config('profit_report'),
        ];

        $this->layout->view('/Sales_profit/profit_report_view', $view_data);
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
