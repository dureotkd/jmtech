<?php
class Store_code_service
{
    protected $obj;
    protected $loginManager = false;

    public function __construct()
    {
        $this->obj = &get_instance();
        $this->obj->load->library([
            "ajax",
            "/Service/manager_service",
            "/Service/store_code_service",
        ]);
        $this->obj->load->model("/Page/service_model");

        // $this->loginManager = $this->obj->manager_service->getLoginUser();

        // if (empty($this->loginManager)) {
        //     throw new Exception('관리자 로그인이 필요합니다.');
        // }
    }

    # 주문 단건 조회
    public function get($id)
    {

        $agent = $this->obj->service_model->get_store_code('row', [
            "id = '{$id}'"
        ]);

        return $agent;
    }

    public function show($where, $limit = 10000)
    {

        $agents = $this->obj->service_model->get_store_code('all', $where, $limit);

        return $agents;
    }

    # 총판코드 생성 (3~ 6자리 랜덤 영문 대문자 + 숫자 조합)
    public function create($자리수 = 3, $id = 0)
    {
        if (empty($id)) throw new Exception('부본사 번호를 지정해주세요.');

        $user = $this->obj->service_model->get_user('row', [
            "id = '{$id}'"
        ]);

        if (empty($user)) throw new Exception('부본사 번호에 해당하는 매장이 존재하지 않습니다.');

        if ($자리수 < 3 || $자리수 > 6) {
            throw new Exception('자리수는 3~6자리 사이로 지정해주세요.');
        }

        // 랜덤 영문 대문자 + 숫자 조합
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';

        for ($i = 0; $i < $자리수; $i++) {

            $index = random_int(0, strlen($characters) - 1);
            $code .= $characters[$index];
        }

        $duplicate_check = $this->obj->service_model->get_store_code('one', [
            "code = '{$code}'"
        ]);

        if (!empty($duplicate_check)) {

            return $this->create();
        }

        $data = [
            'code' => $code,
            'agent_number' => $id,
            // 'user_id' => $this->loginUser['id'],
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $agent_id = $this->obj->service_model->insert_store_code(DEBUG, $data);

        if (empty($agent_id)) {
            throw new Exception(DB_ERR_MSG);
        }

        return $agent_id;
    }

    public function 지정($user_id, $code)
    {

        $agent = $this->obj->service_model->get_store_code('row', [
            "code = '{$code}'"
        ]);

        if (empty($agent)) {
            throw new Exception('해당 총판의 부지점이 존재하지 않습니다.');
        }

        $user_row = $this->obj->service_model->get_user('row', [
            "id = '{$user_id}'"
        ]);

        if (!empty($user_row['store_code'])) {

            $this->obj->service_model->update_store_code(DEBUG, [
                'user_id' => 0,
            ], [
                "code = '{$user_row['store_code']}'"
            ]);
        }

        $this->obj->service_model->update_store_code(DEBUG, [
            'user_id' => $user_id,
        ], [
            "code = '{$code}'"
        ]);

        // 회원의 에이전트 정보 업데이트
        $this->obj->service_model->update_user(DEBUG, [
            'agent_number' => $agent['agent_number'],
            'store_code' => $code,
            'agent' => 'STORE',
        ], [
            "id = '{$user_id}'"
        ]);

        return true;
    }

    public function 고객지정($user_id, $code)
    {

        $agent = $this->obj->service_model->get_store_code('row', [
            "code = '{$code}'"
        ]);

        if (empty($agent)) {
            throw new Exception('해당 총판의 부지점이 존재하지 않습니다.');
        }

        $store_user_row = $this->obj->service_model->get_user('row', [
            "store_code = '{$code}'",
            "agent = 'STORE'",
        ]);

        if (empty($store_user_row)) {
            throw new Exception('해당 총판의 매장이 존재하지 않습니다.');
        }

        // 회원의 에이전트 정보 업데이트
        $this->obj->service_model->update_user(DEBUG, [
            'agent_number' => $store_user_row['id'],
            'store_code' => $code,
            'agent' => 'CUSTOMER',
        ], [
            "id = '{$user_id}'"
        ]);

        return true;
    }
}
