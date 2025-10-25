<?php

class Store_code extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library([
            "layout",
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
        $search_type = $this->input->get('search_type');
        $search_agent = $this->input->get('search_agent');
        $tab = $this->input->get('tab') ?? 'branch';

        $store_code_all = $this->service_model->exec_sql(
            'all',
            "SELECT 
            *, 
            a.id AS id, 
            a.agent_number AS agent_number,
            (SELECT name FROM user WHERE id = a.agent_number) AS agent_name,
            (SELECT name FROM user WHERE id = a.user_id) AS user_name
        FROM 
            store_code a
        LEFT JOIN 
            user b 
        ON 
            a.user_id = b.id
        "
        );

        $group_store = $this->service_model->exec_sql('all', "SELECT *  FROM user WHERE agent = 'BRANCH'");
        $group_store_data = [];
        if (!empty($group_store)) {
            foreach ($group_store as $store) {
                $group_store_data[$store['id']] = "부본사 - " . $store['id'];
            }
        }

        $store_code_all_data = [];

        if (!empty($store_code_all)) {

            foreach ($store_code_all as $store_code) {

                $user_id = $store_code['user_id'];
                // $user = $this->service_model->get_user('row', [
                //     "id = '{$user_id}'"
                // ]);
                $agent_number = $store_code['agent_number'];
                $agent_name = $store_code['agent_name'];

                $key_name = $agent_number . '_' . $agent_name;
                // $agnet = $this->service_model->get_user('row', [
                //     "id = '{$agent_number}'"
                // ]);

                $store_code_all_data[$key_name][] = $store_code;
            }
        }

        $search_type_item = get_search_item_v2(array(
            'vo'            => [
                'all' => '',
            ],
            'select'        => $search_type,
            'tag'           => 's',
        ));

        // STORE -> CUSTOMER
        $store_user_all = $this->service_model->get_user('all', [
            "agent = 'STORE'",
            "status = 'Y'"
        ]);

        $store_user_data = [];

        if (!empty($store_user_all)) {

            foreach ($store_user_all as $user) {

                $customer_user_all = $this->service_model->get_user('all', [
                    "agent = 'CUSTOMER'",
                    "status = 'Y'",
                    "agent_number = '{$user['id']}'",
                    "store_code = '{$user['store_code']}'"
                ]);

                if (!empty($customer_user_all)) {

                    $user['customer_user_all'] = $customer_user_all;
                }

                $store_user_data[$user['id']] = $user;
            }
        }

        // 정렬: customer_user_all 개수 기준 내림차순
        uasort($store_user_data, function ($a, $b) {
            $countA = isset($a['customer_user_all']) ? count($a['customer_user_all']) : 0;
            $countB = isset($b['customer_user_all']) ? count($b['customer_user_all']) : 0;
            return $countB <=> $countA; // 내림차순 (많은 순)
        });

        $view_data =  [
            'tab'              => $tab,
            'store_code_all'    => $store_code_all,
            'search_type_item'  => $search_type_item,
            'group_store_data' => $group_store_data,
            'search_type'       => $search_type,
            'store_user_data' => $store_user_data,
            'store_code_all_data' => $store_code_all_data,
            'layout_data'       => $this->layout_config(),
        ];

        $this->layout->view('/Admin/store_code_view', $view_data);
    }

    /**
     * 총판코드 생성
     */
    public function make_store_code()
    {
        $agent_number = $this->input->post('agent_number');
        $count = $this->input->post('count');

        $res_array = [
            'ok' => true,
            'message' => '생성되었습니다.',
        ];

        try {

            for ($i = 0; $i < $count; $i++) {

                // 자릿수 랜덤으로 3-6자리 사이로 지정
                $자리수 = random_int(3, 6);

                // 총판코드 생성
                $this->store_code_service->create($자리수, $agent_number);
            }
        } catch (Exception $e) {

            $res_array['ok'] = false;
            $res_array['message'] = $e->getMessage();
        }

        echo json_encode($res_array);
        exit;
    }

    public function update_store_code()
    {
        $store_code = $this->input->post('store_code');
        $id = $this->input->post('id');

        $res_array = [
            'ok' => true,
            'msg' => '수정되었습니다.',
        ];

        try {
            // $this->store_code_service->update($id, $store_code);

            if (empty($store_code) || empty($id)) {
                throw new Exception('총판코드와 ID는 필수 입력값입니다.');
            }

            $store_code_row = $this->service_model->get_store_code('row', [
                "id = '{$id}'"
            ]);

            if (empty($store_code_row)) {
                throw new Exception('해당 ID에 대한 총판코드가 존재하지 않습니다.');
            }

            // $user_id = $store_code_row['user_id'];

            // if (!empty($user_id)) {
            //     throw new Exception('해당 총판코드는 이미 회원에게 할당되어 있습니다.');
            // }

            $duplicate_check = $this->service_model->get_store_code('row', [
                "code = '{$store_code}'"
            ]);

            if (!empty($duplicate_check)) {
                throw new Exception('이미 존재하는 총판코드입니다.');
            }

            $this->service_model->update_store_code(DEBUG, [
                'code' => $store_code,
            ], [
                "id = '{$id}'"
            ]);
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
        exit;
    }

    public function update_customer_code()
    {
        $user_id = $this->input->post('user_id');
        $store_code = $this->input->post('store_code');

        $res_array = [
            'ok' => true,
            'msg' => '수정되었습니다.',
        ];

        try {
            if (empty($user_id) || empty($store_code)) {

                throw new Exception('user_id와 store_code는 필수 입력값입니다.');
            }

            $user_row = $this->service_model->get_user('row', [
                "user_id = '{$user_id}'"
            ]);

            if (empty($user_row)) {
                throw new Exception('해당 user_id에 대한 회원이 존재하지 않습니다.');
            }

            $store_code_row = $this->service_model->get_store_code('row', [
                "code = '{$store_code}'"
            ]);

            if (empty($store_code_row)) {
                throw new Exception('해당 store_code에 대한 총판코드가 존재하지 않습니다.');
            }

            $agent_number = $store_code_row['user_id'];

            $this->service_model->update_user(DEBUG, [
                "agent" => 'CUSTOMER',
                'store_code' => $store_code,
                'agent_number' => $agent_number,
            ], [
                "user_id = '{$user_id}'"
            ]);
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
            echo json_encode($res_array);
            exit;
        }

        echo json_encode($res_array);
        exit;
    }

    public function delete_customer_code()
    {
        $user_id = $this->input->post('user_id');

        $res_array = [
            'ok' => true,
            'msg' => '수정되었습니다.',
        ];

        try {
            if (empty($user_id)) {

                throw new Exception('user_id는 필수 입력값입니다.');
            }

            $res = $this->service_model->update_user(DEBUG, [
                "agent" => 'USER',
                'store_code' => '',
                'agent_number' => '',
            ], [
                "user_id = '{$user_id}'"
            ]);

            if (!$res) {
                throw new Exception(DB_ERR_MSG);
            }
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
        exit;
    }

    public function delete_store_code()
    {
        $id = $this->input->post('id');

        $res_array = [
            'ok' => true,
            'msg' => '수정되었습니다.',
        ];

        try {
            // $this->store_code_service->update($id, $store_code);

            if (empty($id)) {
                throw new Exception('ID는 필수 입력값입니다.');
            }

            $store_code_row = $this->service_model->get_store_code('row', [
                "id = '{$id}'"
            ]);

            if (empty($store_code_row)) {
                throw new Exception('해당 ID에 대한 총판코드가 존재하지 않습니다.');
            }

            $this->service_model->update_user(DEBUG, [
                "agent" => 'USER',
                'store_code' => ''
            ], [
                "store_code = '{$store_code_row['code']}'"
            ]);

            $this->service_model->delete_store_code(
                DEBUG,
                [
                    "id = '{$id}'"
                ]
            );
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
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
            'sub_menu_code'    => 'store_code',
        ];
    }
}
