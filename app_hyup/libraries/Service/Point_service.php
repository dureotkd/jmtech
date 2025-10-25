<?php
class Point_service
{
    protected $obj;

    public function __construct()
    {

        $this->obj = &get_instance();
        $this->obj->load->model("/Page/service_model");

        $this->obj->load->library([
            "teamroom",
            "ajax",
            "/Service/user_service",
        ]);

        // $a = $this->obj->user_service->getLoginUser();

        // printr($a);
        // exit;

        // $this->loginUser = $this->obj->user_service->getLoginUser();
    }

    #  포인트 지급
    public function plus($point, $id)
    {

        // $ori_point = $login_user['point'] ?? 0;
        $target_user = $this->obj->service_model->get_user('row', [
            "id = '{$id}'"
        ]);

        $ori_point = $target_user['point'] ?? 0;

        // 포인트 지급 로직
        $data = [
            'point' => (int)$ori_point + (int)$point,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $res = $this->obj->service_model->update_user(DEBUG, $data, [
            "id = '{$id}'"
        ]);

        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }

        return $res;
    }

    # 포인트 차감
    public function minus($point, $id)
    {
        // $ori_point = $login_user['point'] ?? 0;
        $target_user = $this->obj->service_model->get_user('row', [
            "id = '{$id}'"
        ]);

        $ori_point = $target_user['point'] ?? 0;

        // 포인트 차감 로직
        $data = [
            'point' => (int)$ori_point - (int)$point,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $res = $this->obj->service_model->update_user(DEBUG, $data, [
            "id = '{$id}'"
        ]);

        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }

        return $res;
    }

    public function plusAdmin($user_id, $point, $memo = '')
    {

        if (empty($user_id) || empty($point)) {
            throw new Exception('유효하지 않은 사용자 ID 또는 금액입니다.');
        }

        $user = $this->obj->service_model->get_user('row', [
            "id = '{$user_id}'"
        ]);

        if (empty($user)) {
            throw new Exception('사용자를 찾을 수 없습니다.');
        }

        $data = [
            'point' => (int)$user['point'] + (int)$point,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $res = $this->obj->service_model->update_user(DEBUG, $data, [
            "id = '{$user_id}'"
        ]);

        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }

        $this->createLog($user_id, $point, 'admin', 'ADMIN', 0, '관리자 포인트 적립', $memo);

        return $res;
    }


    public function minusAdmin($user_id, $point, $memo = '')
    {

        if (empty($user_id) || empty($point)) {
            throw new Exception('유효하지 않은 사용자 ID 또는 금액입니다.');
        }

        $user = $this->obj->service_model->get_user('row', [
            "id = '{$user_id}'"
        ]);

        if (empty($user)) {
            throw new Exception('사용자를 찾을 수 없습니다.');
        }

        $res_point = (int)$user['point'] - (int)$point;

        if ($res_point < 0) {
            throw new Exception('음수 포인트로 차감할 수 없습니다.');
        }

        $data = [
            'point' => $res_point,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $res = $this->obj->service_model->update_user(DEBUG, $data, [
            "id = '{$user_id}'"
        ]);

        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }

        $this->createLog($user_id, $point, 'admin', 'ADMIN', 0, '관리자 포인트 차감', $memo);

        return $res;
    }

    // 구매 보상 포인트 지급
    public function buyReward($order_item_id)
    {

        $site_info = $this->obj->service_model->get_site_meta('row', [
            "id = 1"
        ]);

        $head_point = $site_info['head_point'] ?? 0;
        $agent_point = $site_info['agent_point'] ?? 0;
        $store_point = $site_info['store_point'] ?? 0;

        if (empty($head_point) || empty($agent_point) || empty($store_point)) {

            throw new Exception('적립금 정보가 없습니다.');
        }

        $order_item = $this->obj->service_model->get_order_item('row', [
            "id = '{$order_item_id}'",
            // "user_id = '{$login_user['id']}'",
            // "status = 'pending'"
        ]);

        if (empty($order_item)) {

            throw new Exception('존재하지 않는 주문입니다.');
        }

        $login_user = $this->obj->user_service->get($order_item['user_id']);
        $agent = $login_user['agent'] ?? '';
        $agent_number = $login_user['agent_number'] ?? '';

        if ($agent == 'STORE') {

            if (empty($agent_number)) {

                throw new Exception('부본사 번호가 없습니다.');
            }
        }

        $order_detail = $this->obj->service_model->get_order_detail('row', [
            "order_item_id = '{$order_item['id']}'",
        ]);

        if (empty($order_detail)) {

            throw new Exception('주문 상세 정보를 찾을 수 없습니다.');
        }

        $quantity = $order_detail['quantity'] ?? 0;

        if ($quantity <= 0) {

            throw new Exception('유효하지 않은 주문 수량입니다.');
        }

        $head_point = (int)$head_point * (int)$quantity;
        $agent_point = (int)$agent_point * (int)$quantity;
        $store_point = (int)$store_point * (int)$quantity;

        $this->obj->teamroom->send('개발자', join("\n", [
            "주문 ID: {$order_item_id}",
            "헤드 포인트: {$head_point}",
            "에이전트 포인트: {$agent_point}",
            "스토어 포인트: {$store_point}",
            "agent_number : {$agent_number}"
        ]));

        try {

            switch ($agent) {

                # 본사
                case 'HEAD':
                    $this->plus($head_point, HEAD_USER_ID, true);
                    $this->createLog(HEAD_USER_ID, $head_point, 'order', 'SAVE', $order_item_id, '거래 완료');

                    break;

                # 부본사
                case 'BRANCH':
                    $this->plus($head_point, HEAD_USER_ID, true);
                    $this->plus($agent_point, $login_user['id'], true);

                    $this->createLog(HEAD_USER_ID, $head_point, 'order', 'SAVE', $order_item_id, '거래 완료');
                    $this->createLog($login_user['id'], $agent_point, 'order', 'SAVE', $order_item_id, '거래 완료');

                    break;

                # 매장
                case 'STORE':
                    $this->plus($head_point, HEAD_USER_ID, true);
                    $this->plus($agent_point, $agent_number, true);
                    $this->plus($store_point, $login_user['id'], true);

                    $this->createLog(HEAD_USER_ID, $head_point, 'order', 'SAVE', $order_item_id, '거래 완료');
                    $this->createLog($agent_number, $agent_point, 'order', 'SAVE', $order_item_id, '거래 완료');
                    $this->createLog($login_user['id'], $store_point, 'order', 'SAVE', $order_item_id, '거래 완료');

                    break;

                # 고객
                case 'CUSTOMER':

                    $store_user = $this->obj->service_model->get_user('row', [
                        "id = '{$agent_number}'",
                        "agent = 'STORE'",
                        "agent_number != ''"
                    ]);

                    $this->obj->teamroom->send('개발자', join("\n", [
                        "-- CUSTOMER --",
                        "store_user : {$store_user['name']}",
                        "store_user : {$store_user['agent_number']}"
                    ]));

                    if (empty($store_user)) {

                        throw new Exception('매장 정보를 찾을 수 없습니다.');
                    }

                    $branch_agent_number = $store_user['agent_number'] ?? '';

                    $this->plus($head_point, HEAD_USER_ID, true);
                    $this->plus($agent_point, $branch_agent_number, true);
                    $this->plus($store_point, $agent_number, true); // 고객이 구입시 -> 매장에게 리워드지급

                    $this->createLog(HEAD_USER_ID, $head_point, 'order', 'SAVE', $order_item_id, '거래 완료');
                    $this->createLog($branch_agent_number, $agent_point, 'order', 'SAVE', $order_item_id, '거래 완료');
                    $this->createLog($agent_number, $store_point, 'order', 'SAVE', $order_item_id, '거래 완료');

                    break;

                case 'NON_USER':
                    // 비회원은 포인트 지급하지 않음
                    break;

                case 'USER':
                    // 일반 사용자는 포인트 지급하지 않음
                    break;

                default:
                    throw new Exception('유효하지 않은 에이전트입니다.');
            }
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }

        return true;
    }

    public function buyRewardAdmin($order_item_id)
    {

        $order_item = $this->obj->service_model->get_order_item('row', [
            "id = '{$order_item_id}'",
        ]);

        if (empty($order_item)) {

            throw new Exception('존재하지 않는 주문입니다.');
        }

        $login_user = $this->obj->user_service->get($order_item['user_id']);

        $site_info = $this->obj->service_model->get_site_meta('row', [
            "id = 1"
        ]);

        $head_point = $site_info['head_point'] ?? 0;
        $agent_point = $site_info['agent_point'] ?? 0;
        $store_point = $site_info['store_point'] ?? 0;

        $agent_number = $login_user['agent_number'] ?? '';
        $agent = $login_user['agent'] ?? '';

        if ($agent == 'STORE') {

            if (empty($agent_number)) {

                throw new Exception('부본사 번호가 없습니다.');
            }
        }

        if (empty($head_point) || empty($agent_point) || empty($store_point)) {

            throw new Exception('적립금 정보가 없습니다.');
        }

        $order_detail = $this->obj->service_model->get_order_detail('row', [
            "order_item_id = '{$order_item['id']}'",
        ]);

        if (empty($order_detail)) {

            throw new Exception('주문 상세 정보를 찾을 수 없습니다.');
        }

        $quantity = $order_detail['quantity'] ?? 0;

        if ($quantity <= 0) {

            throw new Exception('유효하지 않은 주문 수량입니다.');
        }

        $head_point = (int)$head_point * (int)$quantity;
        $agent_point = (int)$agent_point * (int)$quantity;
        $store_point = (int)$store_point * (int)$quantity;

        try {

            switch ($agent) {

                # 본사
                case 'HEAD':
                    $this->plus($head_point, HEAD_USER_ID, true);
                    $this->createLog(HEAD_USER_ID, $head_point, 'order', 'SAVE', $order_item_id, '거래 완료');

                    break;

                # 부본사
                case 'BRANCH':
                    $this->plus($head_point, HEAD_USER_ID, true);
                    $this->plus($agent_point, $login_user['id'], true);

                    $this->createLog(HEAD_USER_ID, $head_point, 'order', 'SAVE', $order_item_id, '거래 완료');
                    $this->createLog($login_user['id'], $agent_point, 'order', 'SAVE', $order_item_id, '거래 완료');

                    break;

                # 매장
                case 'STORE':
                    $this->plus($head_point, HEAD_USER_ID, true);
                    $this->plus($agent_point, $agent_number, true);
                    $this->plus($store_point, $login_user['id'], true);

                    $this->createLog(HEAD_USER_ID, $head_point, 'order', 'SAVE', $order_item_id, '거래 완료');
                    $this->createLog($agent_number, $agent_point, 'order', 'SAVE', $order_item_id, '거래 완료');
                    $this->createLog($login_user['id'], $store_point, 'order', 'SAVE', $order_item_id, '거래 완료');

                    break;

                # 고객
                case 'CUSTOMER':

                    $store_user = $this->obj->service_model->get_user('row', [
                        "id = '{$agent_number}'",
                        "agent = 'STORE'",
                        "agent_number != ''"
                    ]);

                    if (empty($store_user)) {

                        throw new Exception('매장 정보를 찾을 수 없습니다.');
                    }

                    $branch_agent_number = $store_user['agent_number'] ?? '';

                    $this->plus($head_point, HEAD_USER_ID, true);
                    $this->plus($agent_point, $branch_agent_number, true);
                    $this->plus($store_point, $agent_number, true); // 고객이 구입시 -> 매장에게 리워드지급

                    $this->createLog(HEAD_USER_ID, $head_point, 'order', 'SAVE', $order_item_id, '거래 완료');
                    $this->createLog($branch_agent_number, $agent_point, 'order', 'SAVE', $order_item_id, '거래 완료');
                    $this->createLog($agent_number, $store_point, 'order', 'SAVE', $order_item_id, '거래 완료');

                    break;

                case 'NON_USER':
                    // 비회원은 포인트 지급하지 않음
                    break;

                case 'USER':
                    // 일반 사용자는 포인트 지급하지 않음
                    break;

                default:
                    throw new Exception('유효하지 않은 에이전트입니다.');
            }
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }

        return true;
    }

    // 구매 보상 포인트 롤백 (취소시)
    public function buyRewardRollback($order_item_id)
    {
        $order_item = $this->obj->service_model->get_order_item('row', [
            "id = '{$order_item_id}'",
        ]);

        if (empty($order_item)) {

            throw new Exception('존재하지 않는 주문입니다.');
        }

        $login_user = $this->obj->user_service->get($order_item['user_id']);

        $site_info = $this->obj->service_model->get_site_meta('row', [
            "id = 1"
        ]);

        $head_point = $site_info['head_point'] ?? 0;
        $agent_point = $site_info['agent_point'] ?? 0;
        $store_point = $site_info['store_point'] ?? 0;

        $agent_number = $login_user['agent_number'] ?? '';
        $agent = $login_user['agent'] ?? '';

        if ($agent == 'STORE') {

            if (empty($agent_number)) {

                throw new Exception('부본사 번호가 없습니다.');
            }
        }

        if (empty($head_point) || empty($agent_point) || empty($store_point)) {

            throw new Exception('적립금 정보가 없습니다.');
        }

        $order_detail = $this->obj->service_model->get_order_detail('row', [
            "order_item_id = '{$order_item['id']}'",
        ]);

        if (empty($order_detail)) {

            throw new Exception('주문 상세 정보를 찾을 수 없습니다.');
        }

        $quantity = $order_detail['quantity'] ?? 0;

        if ($quantity <= 0) {

            throw new Exception('유효하지 않은 주문 수량입니다.');
        }

        $head_point = (int)$head_point * (int)$quantity;
        $agent_point = (int)$agent_point * (int)$quantity;
        $store_point = (int)$store_point * (int)$quantity;

        try {

            switch ($agent) {

                # 본사
                case 'HEAD':
                    $this->minus($head_point, HEAD_USER_ID, true);
                    $this->createLog(HEAD_USER_ID, $head_point, 'order', 'REFUND', $order_item_id, '거래 취소');

                    break;

                # 부본사
                case 'BRANCH':
                    $this->minus($head_point, HEAD_USER_ID, true);
                    $this->minus($agent_point, $login_user['id'], true);

                    $this->createLog(HEAD_USER_ID, $head_point, 'order', 'REFUND', $order_item_id, '거래 취소');
                    $this->createLog($login_user['id'], $agent_point, 'order', 'REFUND', $order_item_id, '거래 취소');

                    break;

                # 매장
                case 'STORE':
                    $this->minus($head_point, HEAD_USER_ID, true);
                    $this->minus($agent_point, $agent_number, true);
                    $this->minus($store_point, $login_user['id'], true);

                    $this->createLog(HEAD_USER_ID, $head_point, 'order', 'REFUND', $order_item_id, '거래 취소');
                    $this->createLog($agent_number, $agent_point, 'order', 'REFUND', $order_item_id, '거래 취소');
                    $this->createLog($login_user['id'], $store_point, 'order', 'REFUND', $order_item_id, '거래 취소');

                    break;

                # 고객
                case 'CUSTOMER':

                    $store_user = $this->obj->service_model->get_user('row', [
                        "id = '{$agent_number}'",
                        "agent = 'STORE'",
                        "agent_number != ''"
                    ]);

                    if (empty($store_user)) {

                        throw new Exception('매장 정보를 찾을 수 없습니다.');
                    }

                    $branch_agent_number = $store_user['agent_number'] ?? '';

                    $this->minus($head_point, HEAD_USER_ID, true);
                    $this->minus($agent_point, $branch_agent_number, true);
                    $this->minus($store_point, $agent_number, true); // 고객이 구입시 -> 매장에게 리워드지급

                    $this->createLog(HEAD_USER_ID, $head_point, 'order', 'REFUND', $order_item_id, '거래 취소');
                    $this->createLog($branch_agent_number, $agent_point, 'order', 'REFUND', $order_item_id, '거래 취소');
                    $this->createLog($agent_number, $store_point, 'order', 'REFUND', $order_item_id, '거래 취소');

                    break;

                case 'NON_USER':
                    // 비회원은 포인트 지급하지 않음
                    break;

                case 'USER':
                    // 일반 사용자는 포인트 지급하지 않음
                    break;

                default:
                    throw new Exception('유효하지 않은 에이전트입니다.');
            }
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }

        return true;
    }

    # 포인트 이력 조회
    public function showLog($type, $where)
    {

        $point_log = $this->obj->service_model->get_point_log($type, $where);

        return $point_log;
    }

    # 포인트 출금요청
    public function requestWithdraw($payloads)
    {
        $amount = $payloads['amount'] ?? 0;
        $bank = $payloads['bank'] ?? '';
        $account_no = $payloads['account_no'] ?? '';

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Exception('로그인이 필요합니다.');
        }

        if (empty($bank) || empty($account_no)) {
            throw new Exception('은행 계좌 정보가 필요합니다.');
        }

        if (empty($amount) || $amount <= 0) {
            throw new Exception('유효하지 않은 금액입니다.');
        }

        $point_request_sum = $this->obj->service_model->exec_sql('row', "
            SELECT
                SUM(amount) as amount
            FROM
                mosihealth.point_request
            WHERE
                user_id = '{$login_user['id']}'
                AND status = 'pending'
                AND type = 'withdraw'
        ");

        $point_request_sum = $point_request_sum['amount'] ?? 0;

        // 현재 포인트 - 출금 요청 금액
        $res_point = (int)$login_user['point'] - (int)$point_request_sum;

        if ($amount > $res_point) {
            throw new Exception('포인트가 부족합니다.');
        }

        $res = $this->obj->service_model->insert_point_request(DEBUG, [
            'user_id' => $login_user['id'],
            'type' => 'withdraw',
            'amount' => $amount,
            'bank' => $bank,
            'account_no' => $account_no,
            'status' => 'pending',
            'requested_at' => date('Y-m-d H:i:s'),
        ]);

        if (empty($res)) {
            throw new Exception(DB_ERR_MSG);
        }

        // 포인트 이력 생성
        $this->createLog($login_user['id'], "-{$amount}", 'withdraw_request', 'REQUEST', 0, '포인트 출금 요청');

        return true;
    }

    public function rejectPointRequest($id)
    {
        if (empty($id)) {
            throw new Exception('유효하지 않은 요청 ID입니다.');
        }

        $point_request = $this->obj->service_model->get_point_request('row', [
            "id = '{$id}'"
        ]);

        if (empty($point_request)) {
            throw new Exception('요청을 찾을 수 없습니다.');
        }

        if ($point_request['status'] !== 'pending') {
            throw new Exception('이미 처리된 요청입니다.');
        }

        $res = $this->obj->service_model->update_point_request(DEBUG, [
            'status' => 'rejected',
            'processed_at' => date('Y-m-d H:i:s'),
        ], [
            "id = '{$id}'"
        ]);

        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }

        // 포인트 이력 생성
        $this->createLog($point_request['user_id'], 0, 'withdraw_request', 'REJECT', $id, '포인트 출금 요청 거절');

        return true;
    }

    public function approvePointRequest($id)
    {
        if (empty($id)) {
            throw new Exception('유효하지 않은 요청 ID입니다.');
        }

        $point_request = $this->obj->service_model->get_point_request('row', [
            "id = '{$id}'"
        ]);

        if (empty($point_request)) {
            throw new Exception('요청을 찾을 수 없습니다.');
        }

        if ($point_request['status'] !== 'pending') {
            throw new Exception('이미 처리된 요청입니다.');
        }

        $user = $this->obj->service_model->get_user('row', [
            "id = '{$point_request['user_id']}'"
        ]);

        if (empty($user)) {
            throw new Exception('사용자를 찾을 수 없습니다.');
        }

        // 포인트 차감
        $res_point = (int)$user['point'] - (int)$point_request['amount'];

        if ($res_point < 0) {
            throw new Exception('포인트가 부족합니다.');
        }

        $data = [
            'point' => $res_point,
        ];

        $res = $this->obj->service_model->update_user(DEBUG, $data, [
            "id = '{$point_request['user_id']}'"
        ]);

        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }

        // 요청 승인
        $res = $this->obj->service_model->update_point_request(DEBUG, [
            'status' => 'approved',
            'processed_at' => date('Y-m-d H:i:s'),
        ], [
            "id = '{$id}'"
        ]);

        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }

        // 포인트 이력 생성
        $this->createLog($point_request['user_id'], "-{$point_request['amount']}", 'withdraw_request', 'USE', $id, '포인트 출금 요청 승인');

        return true;
    }

    # 포인트 이력 생성
    public function createLog($uid, $buy_reward, $related_table, $point_type, $related_id, $description, $memo = '')
    {

        $user = $this->obj->service_model->get_user('row', [
            "id = '{$uid}'"
        ]);

        if (empty($user)) {

            throw new Error('로그인 정보가 없습니다');
        }

        $log_res = $this->obj->service_model->insert_point_log(DEBUG, [
            'user_id'       => $uid,
            'related_table' => $related_table,
            'related_id'    => $related_id,
            'point_type'    => $point_type,
            'description'   => $description,
            'amount'        => $buy_reward,
            'memo'          => $memo,
            'balance'       => $user['point'],
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
            'ip'            => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        if (empty($log_res)) {

            throw new Error(DB_ERR_MSG);
        }
    }
}
