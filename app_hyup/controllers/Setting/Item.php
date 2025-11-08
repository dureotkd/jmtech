<?php

class item extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            'layout',
            'site_pagination',
            '/Service/user_service',
            '/Service/item_service',
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
        $item_list = $this->service_model->get_item_custom('all', [1], "ORDER BY a.created_at DESC LIMIT {$limit}");

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

    public function create_item()
    {
        $item_name = $this->input->post('item_name');
        $alias = $this->input->post('alias');
        $unit = $this->input->post('unit');
        $purchase_price = $this->input->post('purchase_price');
        $sales_price = $this->input->post('sales_price');
        $memo = $this->input->post('memo');
        $is_active = $this->input->post('is_active');

        $res_array = [
            'ok' => true,
            'msg' => '품목이 성공적으로 생성되었습니다.',
        ];

        try {

            $this->item_service->createItem([
                'item_name'     => $item_name,
                'alias'          => $alias,
                'unit'           => $unit,
                'purchase_price' => $purchase_price,
                'sales_price'    => $sales_price,
                'memo'           => $memo,
                'is_active'      => $is_active,
            ]);
        } catch (Exception $e) {

            $res_array = [
                'ok' => false,
                'msg' => $e->getMessage(),
            ];
        }

        echo json_encode($res_array);
    }

    public function create_excel_item()
    {

        $tmp_name = !empty($_FILES['file1']['tmp_name']) ? $_FILES['file1']['tmp_name'] : '';

        $res_array = [
            'ok' => true,
            'msg' => '엑셀 파일이 성공적으로 처리되었습니다.',
        ];

        try {

            $res_array = $this->item_service->convertExcelToItem($tmp_name);
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    public function delete_item()
    {

        $id = $_GET['id'] ?? '';

        $res_array = [
            'ok' => true,
            'msg' => '품목이 삭제되었습니다.',
        ];

        try {

            $this->item_service->deleteItem($id);
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
