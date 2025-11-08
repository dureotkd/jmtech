<?php

class partner extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            'layout',
            'site_pagination',
            '/Service/user_service',
            '/Service/partner_service'
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
            'layout_data'   => $this->layout_config('partner', '거래처 관리'),
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

    public function create_partner()
    {

        $type          = $_POST['type'] ?? '';
        $company_name  = $_POST['company_name'] ?? '';
        $company_num   = $_POST['company_num'] ?? '';
        $ceo_name      = $_POST['ceo_name'] ?? '';
        $phone_number  = $_POST['phone_number'] ?? '';
        $fax_number    = $_POST['fax_number'] ?? '';
        $address       = $_POST['address'] ?? '';
        $zip_code      = $_POST['zip_code'] ?? '';
        $business_type = $_POST['business_type'] ?? '';
        $memo          = $_POST['memo'] ?? '';
        $bank_code     = $_POST['bank_code'] ?? '';

        // 배열 값 (manager 관련)
        $manager_name  = $_POST['manager_name'] ?? [];
        $manager_phone = $_POST['manager_phone'] ?? [];
        $manager_email = $_POST['manager_email'] ?? [];
        $manager_note  = $_POST['manager_note'] ?? [];

        $file = $_FILES['file1'] ?? null;

        $res_array = [
            'ok' => true,
            'msg' => '거래처가 추가되었습니다.',
        ];

        try {

            $insert_id = $this->partner_service->create([
                'type'          => $type,
                'company_name'  => $company_name,
                'company_num'   => $company_num,
                'ceo_name'      => $ceo_name,
                'phone_number'  => $phone_number,
                'fax_number'    => $fax_number,
                'address'       => $address,
                'zip_code'      => $zip_code,
                'business_type' => $business_type,
                'memo'          => $memo,
                'bank_code'     => $bank_code,

                'manager_name'  => $manager_name,
                'manager_phone' => $manager_phone,
                'manager_email' => $manager_email,
                'manager_note'  => $manager_note,
            ]);

            if (!empty($file)) {

                $this->partner_service->uploadFile($insert_id);
            }
        } catch (Exception $e) {

            $res_array = [
                'ok' => false,
                'msg' => $e->getMessage(),
            ];
        }

        echo json_encode($res_array);
    }

    public function delete_partner()
    {

        $id = $_GET['id'] ?? '';

        $res_array = [
            'ok' => true,
            'msg' => '거래처가 삭제되었습니다.',
        ];

        try {

            $this->partner_service->deletePartner($id);
        } catch (Exception $e) {

            $res_array = [
                'ok' => false,
                'msg' => $e->getMessage(),
            ];
        }

        echo json_encode($res_array);
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
