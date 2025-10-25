<?php

class agent extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
            "/Service/user_service",
            "/Service/point_service",
            "/Service/store_code_service",
        ]);

        $this->load->model('/Page/service_model');
    }

    /**
     * 총판코드 생성 & 조회
     * 본사 부본사 코드 생성 & 조회
     * 전체 회원 조회
     */
    public function index()
    {
        $id = $this->input->get('id');
        $user_id = $this->input->get('user_id');
        $search_type = $this->input->get('search_type');
        $search_user = $this->input->get('search_user') ?? 'all';
        $search_value = $this->input->get('search_value');
        $excel_yn = $this->input->get('excel_yn') ?? 'N';
        $page = $this->input->get('page') ?? 1;

        $where = [
            "status = 'Y'",
        ];

        if (!empty($search_user) && $search_user != 'all') {

            $where[] = "agent = '{$search_user}'";
        }

        if (!empty($search_type) && !empty($search_value)) {

            if ($search_type == 'all') {
                $where[] =
                    "(
                a.user_id LIKE '%{$search_value}%' 
                OR a.name LIKE '%{$search_value}%' 
                OR a.phone LIKE '%{$search_value}%'
                OR a.email LIKE '%{$search_value}%'
                OR a.store_code LIKE '%{$search_value}%'
                )";
            } else {

                $where[] = "{$search_type} LIKE '%{$search_value}%'";
            }
        }
        $user_all = $this->service_model->get_user('all', $where);

        $search_user_item = get_search_item_v2([
            'vo'            => unserialize(AGENT),
            'select'        => $search_user,
            'add' => [
                'all' => '유형 전체'
            ],
            'tag'           => 's',
        ]);

        $search_type_item = get_search_item_v2(array(
            'vo'            => [
                'all' => '전체',
                'id'  => '아이디',
                'name' => '이름',
                'phone' => '연락처',
                'email' => '이메일',
                'agent' => '총판코드',
            ],
            'select'        => $search_type,
            'tag'           => 's',
        ));

        // ^ 부본사
        $store_code_all = $this->service_model->exec_sql(
            'all',
            "SELECT
                * , (SELECT name FROM user WHERE id = a.user_id) AS user_name
            FROM
                store_code a
            WHERE
                a.user_id = 0
                
            ORDER BY
                code ASC"
        );

        // ^ 매장
        $store_user_all = $this->service_model->get_user('all', [
            "agent = 'STORE'",
            "status = 'Y'"
        ]);

        $layout_data = $excel_yn == 'Y' ? $this->layout_config("layout/blank") : $this->layout_config();

        $view_data =  [
            'user_all' => $user_all,
            'search_type_item' => $search_type_item,
            'search_user_item' => $search_user_item,
            'search_type' => $search_type,
            'search_user' => $search_user,
            'search_value' => $search_value,
            'layout_data' => $layout_data,
            'store_code_all' => $store_code_all,
            'store_user_all' => $store_user_all,
            'excel_yn' => $excel_yn,
            'page' => $page,
        ];

        if (!empty($user_id)) {

            $user_row = $this->service_model->get_user('row', [
                "user_id = '{$user_id}'"
            ]);


            $id = $user_row['id'] ?? null;
        }

        if (!empty($id)) {

            $user_row = $this->user_service->get($id);

            if ($user_row['agent'] == 'BRANCH') {

                $store_all = $this->service_model->get_user('all', [
                    "agent_number = '{$user_row['id']}'",
                    "agent = 'STORE'"
                ]);
                $ids = array_column($store_all, 'id');
                $total_point = $this->service_model->exec_sql(
                    "row",
                    sprintf("SELECT SUM(point) AS total_point FROM user WHERE id IN ('%s')", join(',', $ids))
                );

                $view_data['store_all'] = $store_all;
                $view_data['total_point'] = $total_point['total_point'] ?? 0;
            } else if ($user_row['agent'] == 'STORE') {

                $store_all = $this->service_model->get_user('all', [
                    "agent_number = {$user_row['id']}",
                    "agent = 'CUSTOMER'",
                ]);

                $ids = array_column($store_all, 'id');

                $total_point = $this->service_model->exec_sql(
                    "row",
                    sprintf("SELECT SUM(point) AS total_point FROM user WHERE id IN ('%s')", join(',', $ids))
                );

                $view_data['store_all'] = $store_all;
                $view_data['total_point'] = $total_point['total_point'] ?? 0;
            } else {

                $store_all = [];
                $total_point = 0;
            }

            // 포인트 내역
            $point_log_db = $this->point_service->showLog('all', [
                "user_id = {$id}",
            ]);

            $point_logs = [];

            if (!empty($point_log_db)) {

                foreach ($point_log_db as $row) {

                    if ($row['related_table'] == 'order') {

                        $order_item = $this->service_model->get_order_item('row', [
                            "id = {$row['related_id']}"
                        ]);

                        if (empty($order_item)) {

                            continue;
                        }

                        $buy_user = $this->service_model->get_user('row', [
                            "id = {$order_item['user_id']}"
                        ]);

                        $order_item['buy_user'] = $buy_user;
                        $row['order_item'] = $order_item;

                        $row['system_memo'] = join("<br/>", [
                            "{$buy_user['agent']}",
                            "주문번호 : <a href='/admin/order?search_type=number&search_value={$order_item['number']}' class='!text-blue-500 !underline'>{$order_item['number']}</a>",
                            "총금액 : " . number_format($order_item['total_amount'] + $order_item['shipping_fee']) . "원",
                            "구매자 이름 : {$buy_user['name']}",
                            "구매자 아이디 : {$buy_user['user_id']}",
                        ]);
                    }

                    $point_logs[] = $row;
                }
            }

            $view_data['user_id'] = $id;
            $view_data['user_row'] = $user_row;
            $view_data['point_logs'] = $point_logs;

            $this->layout->view('/Admin/agent_detail_view', $view_data);
        } else {

            if ($excel_yn == 'Y') {

                $this->output->enable_profiler(false);

                $excel_file_name = "회원관리_" . date('Ymd') . ".xls";

                // Excel 파일로 다운로드 (header ms excel)
                header("Content-type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename=" . $excel_file_name . ".xls");
                header("Content-Transfer-Encoding: binary");
                header("Pragma: no-cache");
                header("Expires: 0");

                $this->layout->view('/Admin/Excel/agent_excel_view', $view_data);
            } else {

                $this->layout->view('/Admin/agent_view', $view_data);
            }
        }
    }

    public function update_password()
    {

        $password = $this->input->post('password');
        $password_confirm = $this->input->post('password_confirm');
        $id = $this->input->post('id');

        $res_array = [
            'ok' => true,
            'msg' => '비밀번호가 변경되었습니다.',
        ];

        try {

            $this->user_service->changePasswordByAdmin($id, $password, $password_confirm);
        } catch (Exception $e) {
            $res_array = [
                'ok' => false,
                'msg' => '비밀번호 변경 중 오류가 발생했습니다: ' . $e->getMessage(),
            ];
        }

        echo json_encode($res_array);
        exit;
    }

    public function add_agent()
    {
        $user_ids = $this->input->post('user_ids');

        $res_array = [
            'ok' => true,
            'msg' => '부본사로 지정되었습니다.',
        ];

        foreach ([1] as $valid) {

            if (empty($user_ids)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '회원이 선택되지 않았습니다.';
                break;
            }

            $this->service_model->update_store_code(DEBUG, [
                "user_id" => 0,
            ], [
                "user_id IN (" . implode(',', $user_ids) . ")"
            ]);

            $this->service_model->update_user(DEBUG, [
                'agent' => 'BRANCH',
                'store_code' => '',
            ], [
                "id IN (" . implode(',', $user_ids) . ")"
            ]);
        }

        echo json_encode($res_array);
    }


    public function add_store_customer()
    {
        $user_id = $this->input->post('user_id');
        $store_code = $this->input->post('store_code');

        $res_array = [
            'ok' => true,
            'msg' => '고객 지정되었습니다.',
        ];

        try {

            $this->store_code_service->고객지정($user_id, $store_code);
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = '고객 지정 중 오류가 발생했습니다: ' . $e->getMessage();
        }

        echo json_encode($res_array);
    }

    public function update_user_status()
    {
        $user_id = $this->input->post('user_id');
        $status = $this->input->post('status');

        $res_array = [
            'ok' => true,
            'msg' => '처리되었습니다.',
        ];

        foreach ([1] as $valid) {

            if (empty($user_id)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '회원이 선택되지 않았습니다.';
                break;
            }

            try {

                $this->service_model->update_user(DEBUG, [
                    'status' => $status,
                ], [
                    "id = {$user_id}"
                ]);
            } catch (Exception $e) {
                $res_array['ok'] = false;
                $res_array['msg'] = '회원 삭제 중 오류가 발생했습니다: ' . $e->getMessage();
            }
        }

        echo json_encode($res_array);
    }

    public function add_store()
    {
        $user_id = $this->input->post('user_id');
        $store_code = $this->input->post('store_code');

        $res_array = [
            'ok' => true,
            'msg' => '총판코드가 지정되었습니다.',
        ];

        foreach ([1] as $valid) {

            try {

                $this->store_code_service->지정($user_id, $store_code);
            } catch (Exception $e) {
                $res_array['ok'] = false;
                $res_array['msg'] = '총판코드 지정 중 오류가 발생했습니다: ' . $e->getMessage();
            }
        }

        echo json_encode($res_array);
    }

    public function update_memo()
    {
        $memo = $this->input->post('memo');
        $id = $this->input->post('id');

        $res_array = [
            'ok' => true,
            'msg' => '메모가 저장되었습니다.',
        ];

        foreach ([1] as $valid) {

            if (empty($id)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '회원이 선택되지 않았습니다.';
                break;
            }

            $this->service_model->update_user(DEBUG, [
                'memo' => $memo,
            ], [
                "id = {$id}"
            ]);
        }

        echo json_encode($res_array);
    }

    public function update_user()
    {
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $phone = $this->input->post('phone');
        $zip_code = $this->input->post('zipcode');
        $address = $this->input->post('address');
        $address_detail = $this->input->post('address_detail');
        $memo = $this->input->post('memo');

        $res_array = [
            'ok' => true,
            'msg' => '정보가 저장되었습니다.',
        ];

        foreach ([1] as $valid) {

            if (empty($id)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '회원이 선택되지 않았습니다.';
                break;
            }

            $this->service_model->update_user(DEBUG, [
                'name' => $name,
                'phone' => $phone,
                'zip_code' => $zip_code,
                'address' => $address,
                'address_detail' => $address_detail,
                'memo' => $memo,

            ], [
                "id = {$id}"
            ]);
        }

        echo json_encode($res_array);
    }

    public function update_point_log_memo()
    {
        $log_id = $this->input->post('log_id');
        $memo = $this->input->post('memo');

        $res_array = [
            'ok' => true,
            'msg' => '메모가 변경되었습니다.',
        ];

        foreach ([1] as $valid) {

            if (empty($log_id)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '로그가 선택되지 않았습니다.';
                break;
            }

            $this->service_model->update_point_log(DEBUG, [
                'memo' => $memo,
            ], [
                "id = {$log_id}"
            ]);
        }


        echo json_encode($res_array);
    }

    public function delete_agent()
    {
        $user_ids = $this->input->post('user_ids');

        $res_array = [
            'ok' => true,
            'msg' => '부본사가 해제되었습니다.',
        ];

        foreach ([1] as $valid) {

            if (empty($user_ids)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '회원이 선택되지 않았습니다.';
                break;
            }

            $this->service_model->update_user(DEBUG, [
                'agent' => 'USER',
            ], [
                "id IN (" . implode(',', $user_ids) . ")"
            ]);
        }

        echo json_encode($res_array);
    }

    public function point_admin()
    {
        $point_type = $this->input->post('point_type');
        $point_value = $this->input->post('point_value');
        $user_id = $this->input->post('user_id');
        $memo = $this->input->post('memo');

        $res_array = [
            'ok' => true,
            'msg' => '포인트가 처리되었습니다.',
        ];

        foreach ([1] as $valid) {

            if ($point_type != 'plus' && $point_type != 'minus') {
                $res_array['ok'] = false;
                $res_array['msg'] = '포인트 타입이 잘못되었습니다.';
                break;
            }

            if (empty($user_id)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '회원이 선택되지 않았습니다.';
                break;
            }

            if (empty($point_value)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '포인트가 입력되지 않았습니다.';
                break;
            }

            try {

                if ($point_type == 'plus') {

                    $this->point_service->plusAdmin($user_id, $point_value, $memo);
                } else if ($point_type == 'minus') {

                    $this->point_service->minusAdmin($user_id, $point_value, $memo);
                }
            } catch (Exception $e) {
                $res_array['ok'] = false;
                $res_array['msg'] = '포인트 처리 중 오류가 발생했습니다: ' . $e->getMessage();
            }
        }

        echo json_encode($res_array);
    }

    private function layout_config($layout_name = "layout/admin")
    {

        $this->layout->setLayout($layout_name);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'agent',
        ];
    }
}
