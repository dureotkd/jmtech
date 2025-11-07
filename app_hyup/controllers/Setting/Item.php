<?php

class item extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            'layout',
            'site_pagination',
            '/Service/user_service'
        ]);

        $this->load->model('/Page/service_model');
    }

    # 고객센터
    public function index()
    {
        $page                       = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $row_num                    = !empty($_REQUEST['row_num']) ? $_REQUEST['row_num'] : 15;
        $block_num                  = !empty($_REQUEST['block_num']) ? $_REQUEST['block_num'] : 10;

        $item_count = $this->service_model->get_item('one', [1]);
        $page_data          = $this->site_pagination->getPageNaviGationData($page, $item_count, $row_num, $block_num);
        $limit              = $page_data['res_limit'];

        /**
         * (
    [0] => Array
        (
            [id] => 1
            [item_code] => 성호스텐
            [item_name] => STS 304 양P/S(레) 판 2t*1219*2438
            [alias] => 
            [spec] => 
            [unit] => 
            [tax_type] => taxable
            [purchase_price] => 201150
            [sales_price] => 0
            [memo] => 
            [is_active] => 1
            [created_at] => 2025-11-03 16:50:24
            [updated_at] => 2025-11-03 16:50:24
        )
         */
        $item_list = $this->service_model->get_item('all', [1], $limit);

        $view_data =  [
            'page' => $page,
            'item_list'   => $item_list,
            'page_data'     => $page_data,
            'item_count'     => $item_count,
            'layout_data'   => $this->layout_config('item', '품목 관리'),
        ];

        $this->layout->view('/Setting/item_view', $view_data);
    }

    public function create()
    {

        $view_data =  [
            'layout_data'   => $this->layout_blank_config('', '품목 추가'),
        ];

        $this->layout->view('/Setting/create_item_view', $view_data);
    }

    private function layout_config($sub_menu_code = '', $title = '')
    {

        $this->layout->setLayout("layout/template");
        $this->layout->setTitle($title);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'setting',
            'sub_menu_code'    => $sub_menu_code,
        ];
    }

    private function layout_blank_config($sub_menu_code = '', $title = '')
    {

        $this->layout->setPopHeader($title);
        $this->layout->setLayout("layout/blank");
        $this->layout->setTitle($title);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'setting',
            'sub_menu_code'    => $sub_menu_code,
        ];
    }
}
