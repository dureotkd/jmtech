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

        $item_list = $this->service_model->get_item('all', [1], $limit);

        $view_data =  [
            'page' => $page,
            'item_list'   => $item_list,
            'page_data'     => $page_data,

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
