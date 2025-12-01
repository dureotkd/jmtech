<?php

class Sales_profit extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            'layout',
            'Service/user_service',
            'site_pagination'
        ]);

        $this->load->model('/Page/service_model');
    }

    public function index() {}

    # 매출(거래명세표)
    public function partner_report()
    {
        $page                       = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $row_num                    = !empty($_REQUEST['row_num']) ? $_REQUEST['row_num'] : 15;
        $block_num                  = !empty($_REQUEST['block_num']) ? $_REQUEST['block_num'] : 10;

        $partner_count      = $this->service_model->get_business_partner('one', [1]);
        $page_data          = $this->site_pagination->getPageNaviGationData($page, $partner_count, $row_num, $block_num);
        $limit              = $page_data['res_limit'];

        $data = $this->service_model->get_sales_monthly_summary('all', [1], 'recent_3months_sales DESC', $limit);
        $stat = $this->service_model->get_sales_monthly_summary_stat([1]);

        $view_data =  [
            'data'                  => $data,
            'stat'                  => $stat,
            'page'                  => $page,
            'page_data'             => $page_data,
            'layout_data'           => $this->layout_config('partner_report'),
        ];

        $this->layout->view('/Sales_profit/partner_report_view', $view_data);
    }

    # 매출처현황 데이터 조회 (AJAX)
    public function get_partner_report_data()
    {
        $res_array = [
            'ok'    => true,
            'msg'   => '',
            'data'  => [],
        ];

        try {
            $data = $this->service_model->get_sales_monthly_summary('all', []);
            $res_array['data'] = $data;
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
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
