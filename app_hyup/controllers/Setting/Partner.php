<?php

class partner extends MY_Controller
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
        $page = $_REQUEST['page'] ?? 1;

        // * 거채처

        /**
         * 
    [1] => Array
        (
            [id] => 2
            [type] => business
            [company_name] => (유)에이지케이특수강
            [partner_name] => 
            [company_num] => 113-81-40307
            [private_num] => 
            [ceo_name] => 
            [phone_country_code] => +82
            [phone_number] => 
            [fax_number] => 
            [company_tel_number] => 
            [address] => 
            [business_type] => 
            [business_item] => 
            [group_sales] => 0
            [group_purchase] => 0
            [group_etc] => 0
            [is_active] => 1
            [memo] => 
            [account_info_json] => 
            [appoint_user_name] => 
            [appoint_user_phone] => 
            [appoint_user_email] => 
            [appoint_user_memo] => 
            [created_at] => 2025-10-29 22:47:54
            [updated_at] => 2025-10-29 22:47:54
            [이월미수금] => 
            [이월미지급금] => 
        )
         */
        $page                       = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $row_num                    = !empty($_REQUEST['row_num']) ? $_REQUEST['row_num'] : 15;
        $block_num                  = !empty($_REQUEST['block_num']) ? $_REQUEST['block_num'] : 10;

        $partner_count      = $this->service_model->get_business_partner('one', [1]);
        $page_data          = $this->site_pagination->getPageNaviGationData($page, $partner_count, $row_num, $block_num);
        $limit              = $page_data['res_limit'];

        $partner_list = $this->service_model->get_business_partner('all', [1], $limit);

        $view_data =  [
            'page' => $page,

            'partner_list'   => $partner_list,
            'page_data'     => $page_data,
            'layout_data'   => $this->layout_config(),
        ];

        $this->layout->view('/Setting/partner_view', $view_data);
    }

    public function create()
    {

        $view_data =  [
            'layout_data'   => $this->layout_blank_config('', '거래처 추가'),
        ];

        $this->layout->view('/Setting/create_partner_view', $view_data);
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
            'sub_menu_code'    => 'partner',
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
            'top_menu_code'    => 'sales',
            'sub_menu_code'    => $sub_menu_code,
        ];
    }
}
