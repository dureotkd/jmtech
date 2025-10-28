<?php

class sales extends MY_Controller
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
            'layout_data'           => $this->layout_config('report', '매출(거래명세표)'),
        ];

        $this->layout->view('/Sales/report_view', $view_data);
    }

    # 견적서
    public function estimate()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config('estimate', '견적서'),
        ];

        $this->layout->view('/Sales/estimate_view', $view_data);
    }

    # 수주서
    public function order()
    {

        $view_data =  [
            'faqs'          => '',
            'layout_data'   => $this->layout_config('order', '수주서'),
        ];

        $this->layout->view('/Sales/order_view', $view_data);
    }

    # 견적서 등록 (팝업)
    public function estimate_register()
    {
        $sheets = [
            '견적서',
            '내역서'
        ];

        $view_data =  [
            'sheets'                => $sheets,
            'layout_data'           => $this->layout_blank_config('', '견적서 등록'),
        ];

        $this->layout->view('/Sales/estimate_register_view', $view_data);
    }


    private function layout_config($sub_menu_code = '', $title = '')
    {

        $this->layout->setLayout("layout/template");
        $this->layout->setTitle($title);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'sales',
            'sub_menu_code'    => $sub_menu_code,
        ];
    }

    private function layout_blank_config($sub_menu_code = '', $title = '')
    {

        $this->layout->setPopHeader('견적서 등록');
        $this->layout->setLayout("layout/blank");
        $this->layout->setTitle($title);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'sales',
            'sub_menu_code'    => $sub_menu_code,
        ];
    }
}
