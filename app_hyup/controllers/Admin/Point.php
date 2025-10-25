<?php

class point extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
            "/Service/point_service",
            "/Service/user_service",
        ]);

        $this->load->model('/Page/service_model');
    }

    public function index()
    {
        $id = $this->input->get('id');
        $search_type = $this->input->get('search_type');
        $search_point_status = $this->input->get('search_point_status') ?? 'all';
        $search_point_request_type = $this->input->get('search_point_request_type') ?? 'all';
        $search_value = $this->input->get('search_value');

        $where = [
            "a.user_id = b.id",
        ];

        if (!empty($search_value)) {
            $where[] = "b.user_id LIKE '%{$search_value}%'";
        }

        if (!empty($search_point_status) && $search_point_status != 'all') {
            $where[] = "a.status = '{$search_point_status}'";
        }

        if (!empty($search_point_request_type) && $search_point_request_type != 'all') {
            $where[] = "a.type = '{$search_point_request_type}'";
        }

        $point_requests = $this->service_model->get_point_request_join('all', $where);

        $view_data = [
            'layout_data' => $this->layout_config(),
            'search_value' => $search_value,
            'search_type' => $search_type,
            'search_type_item' => get_search_item_v2([
                'vo' => [
                    'all' => '전체',
                    'id' => '아이디',
                    'name' => '이름',
                    'phone' => '연락처',
                    'email' => '이메일',
                ],
                'select' => $search_type,
                'tag' => 's',
            ]),
            'search_point_status' => get_search_item_v2([
                'vo' => unserialize(POINT_REQUEST_STATUS),
                'select' => $search_point_status,
                'add' => ['all' => '상태 전체'],
                'tag' => 's',
            ]),
            'search_point_request_type_item' => get_search_item_v2([
                'vo' => unserialize(POINT_REQUEST_TYPE),
                'select' => $search_point_request_type,
                'add' => ['all' => '유형 전체'],
                'tag' => 's',
            ]),
            'point_requests' => $point_requests,
        ];

        $this->layout->view('/Admin/point_view', $view_data);
    }

    public function reject_request()
    {
        $res_array = [
            'ok' => true,
            'message' => '요청이 처리되었습니다',
        ];

        $id = $this->input->post('id');

        try {

            $this->point_service->rejectPointRequest($id);
        } catch (Exception $e) {

            $res_array['ok'] = false;
            $res_array['message'] = '처리 중 오류가 발생했습니다: ' . $e->getMessage();
        }

        echo json_encode($res_array);
        exit;
    }

    public function approve_request()
    {
        $res_array = [
            'ok' => true,
            'message' => '요청이 처리되었습니다',
        ];

        $id = $this->input->post('id');

        try {

            $this->point_service->approvePointRequest($id);
        } catch (Exception $e) {

            $res_array['ok'] = false;
            $res_array['message'] = '처리 중 오류가 발생했습니다: ' . $e->getMessage();
        }

        echo json_encode($res_array);
        exit;
    }

    private function layout_config()
    {

        $this->layout->setLayout("layout/admin");
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'point',
        ];
    }
}
