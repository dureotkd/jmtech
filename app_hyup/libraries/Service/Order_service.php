<?php
class Order_service
{
    protected $obj;
    protected $loginUser = false;

    public function __construct()
    {
        $this->obj = &get_instance();
        $this->obj->load->library([
            'ajax',
            'smartro',
            'payaction',
            'alarmtalk',
            'teamroom',
            '/Service/product_service',
            '/Service/point_service'
        ]);


        $this->obj->load->model("/Page/service_model");
    }

    public function get_item($type, $where)
    {

        $orders = $this->obj->service_model->get_order_item($type, $where);

        return $orders;
    }

    public function get_detail($type, $where)
    {

        $orders = $this->obj->service_model->get_order_detail($type, $where);

        return $orders;
    }

    # 주문 생성 (상품구매시)
    public function create($payloads)
    {
        $non_user = $payloads['non_user'] ?? false;

        // * ----------- 회원정보 -----------
        $user_id = $payloads['user_id'] ?? 0;

        $login_user = !empty($non_user) ? [] : $this->obj->user_service->getLoginUser();

        // ^ 회원 주문시 로그인 아닐 경우 PASS..
        if (empty($non_user)) {

            if (!empty($user_id)) {

                $login_user = $this->obj->user_service->get($user_id);
            }

            if (empty($login_user)) {

                throw new Exception('로그인 후 주문해주세요...');
            }
        }

        // * ----------- 제품정보 -----------
        $product_id = $payloads['product_id'] ?? ''; // 상품 ID
        $option_id = $payloads['option_id'] ?? ''; // 옵션 ID
        $price = $payloads['price'] ?? 0; // 상품 가격
        $quantity = $payloads['quantity'] ?? 0; // 상품 수량
        $amount = $payloads['amount'] ?? 0;  // 결제 총액
        $option1_quantity = $payloads['option1_quantity'] ?? 0; // 옵션 수량

        $bundle_items = $payloads['bundle_items'] ?? []; // 번들 상품 정보 (멀티 상품인 경우)

        // * ----------- 배송정보 -----------
        $shipping_fee = $payloads['shipping_fee'] ?? 0; // 배송비
        $buyer_name = $payloads['buyer_name'] ?? ''; // 구매자 이름
        $buyer_phone = $payloads['buyer_phone'] ?? ''; // 구매자 전화번호
        $receiver_name = $payloads['receiver_name'] ?? ''; // 수령자 이름
        $receiver_phone = $payloads['receiver_phone'] ?? ''; // 수령자 전화번호
        $zipcode = $payloads['zipcode'] ?? ''; // 우편번호
        $address = $payloads['address'] ?? ''; // 주소
        $address_detail = $payloads['address_detail'] ?? ''; // 상세 주소
        $memo = $payloads['memo'] ?? ''; // 주문 메모

        $status = $payloads['status'] ?? 'pending'; // 주문 상태 (기본값: pending)
        $paid_at = $payloads['paid_at'] ?? null; // 결제 완료 시간

        $payment_method = $payloads['payment_method'] ?? ''; // 결제 방법
        $payment_log_id = $payloads['payment_log_id'] ?? ''; // 결제 로그 ID

        if (empty($product_id)) {

            throw new Exception('상품을 선택해주세요.');
        }

        // if (empty($option_id)) {

        //     throw new Exception('옵션을 선택해주세요.');
        // }

        if ($quantity <= 0) {

            throw new Exception('수량을 올바르게 입력해주세요.');
        }

        $product = $this->obj->product_service->get($product_id);
        $db_price = $product['price'] ?? 0;

        $option1_fee = !empty($option1_quantity) ? 1000 * $option1_quantity : 0;

        // if (empty($bundle_items)) {

        //     if ($price * $quantity != $amount) {

        //         throw new Exception('상품 가격이 올바르지 않습니다.');
        //     }

        //     // * 2차검증 (가격)
        //     if ($db_price != $price) {

        //         throw new Exception('상품 가격이 올바르지 않습니다.');
        //     }
        // }

        if (empty($product)) {

            throw new Exception('존재하지 않는 상품입니다.');
        }

        if ($price <= 0) {

            throw new Exception('상품 가격이 올바르지 않습니다.');
        }

        $order_number = $this->make_order_number($login_user['id']);

        $data = [
            'user_id' => $login_user['id'],
            'total_amount' => $amount,
            'option1_fee' => $option1_fee,
            'status' => $status,
            'paid_at' => $paid_at,
            'number' => $order_number,
            'payment_method' => $payment_method, // 결제 방법
            'payment_log_id' => $payment_log_id, // 결제 로그 ID
            'shipping_fee' => $shipping_fee, // 배송비
            'is_multy' => !empty($bundle_items) ? 1 : 0, // 멀티 상품 여부
            'ordered_at' => date('Y-m-d H:i:s'),
        ];

        // LOG 생성
        switch ($payment_method) {
            case '무통장입금':

                try {

                    $payment_log_id = $this->obj->payaction->order([
                        'order_number' => $order_number,
                        'order_amount' => $amount + $shipping_fee + $option1_fee,
                        'billing_name' => $buyer_name,
                        'orderer_name' => $buyer_name,
                        'orderer_phone_number' => $buyer_phone,
                        'orderer_email' => $this->loginUser['email'] ?? '',
                    ]);

                    $data['payment_log_id'] = $payment_log_id;
                } catch (Exception $e) {

                    throw new Exception($e->getMessage());
                }

                break;
            case '카드':

                $bundle_items_cnt = count($bundle_items);
                $product_name = $bundle_items_cnt == 1 ?
                    $product['name']
                    : $product['name'] = $product['name'] . '등 ' . ($bundle_items_cnt - 1) . '종';

                $target_phone = !empty($buyer_phone) ? $buyer_phone : $receiver_phone;

                $this->obj->alarmtalk->send([
                    'phone' => str_replace('-', '', $target_phone),
                    'name' => $buyer_name,
                    'templateCode' => 'ppur_2025081114295415150392024', // 실제 템플릿 코드로 변경
                    'type' => 'PAY',
                    'changeWord' => [
                        'var1' => $buyer_name,                                          // [*1*] → 이름
                        'var2' => $product_name,                                        // [*2*] → 상품명
                        'var3' => $order_number,                                        // [*3*] → 주문번호
                        'var4' => number_format($amount + $shipping_fee + $option1_fee) // [*4*] → 금액
                    ],
                ]);

                break;
            default:
                throw new Exception('지원하지 않는 결제 방법입니다.');
        }

        $order_item_id = $this->obj->service_model->insert_order_item(DEBUG, $data);

        if (empty($order_item_id)) {

            throw new Exception(DB_ERR_MSG);
        }

        $order_detail_id = $this->obj->service_model->insert_order_detail(DEBUG, [
            'order_item_id' => $order_item_id,
            'product_id' => $product_id,
            'option_id' => $option_id,
            'quantity' => $quantity,
            'option1_quantity' => $option1_quantity,
            'price' => $price,
            'buyer_name' => $buyer_name,
            'buyer_phone' => $buyer_phone,
            'receiver_name' => $receiver_name,
            'receiver_phone' => $receiver_phone,
            'zipcode' => $zipcode,
            'address' => $address,
            'address_detail' => $address_detail,
            'memo' => $memo,
        ]);

        if (empty($order_detail_id)) {

            throw new Exception(DB_ERR_MSG);
        }

        if (!empty($bundle_items)) {

            // * 멀티 상품인 경우, 추가적인 주문 상세를 처리합니다.
            $bundle_items = $payloads['bundle_items'] ?? [];

            foreach ($bundle_items as $bundle_item) {

                $this->obj->service_model->insert_order_bundle_items(DEBUG, [
                    'order_item_id' => $order_item_id,
                    'price' => $bundle_item['price'],
                    'product_id' => $bundle_item['product_id'],
                    'quantity' => $bundle_item['quantity'],
                    'amount' => $bundle_item['price'] * $bundle_item['quantity'],
                ]);
            }
        }

        // * 비회원은 reward지급 없음

        /**
         * ^ 포인트적립은 최종완료시 처리
         */
        $is_reward = true;

        foreach ([1] as $valid) {

            if (!empty($non_user)) {

                $is_reward = false;
                break;
            }

            if (empty($login_user['agent'])) {

                $is_reward = false;
                break;
            }

            if ($payment_method == '무통장입금') {

                $is_reward = false;
                break;
            }
        }

        if ($is_reward) {

            try {

                $this->obj->point_service->buyReward($order_item_id);
            } catch (Exception $e) {

                $this->obj->teamroom->send('개발자', join("\n", [
                    "주문 ID: {$order_item_id}",
                    "에러 메시지: {$e->getMessage()}"
                ]));

                throw new Exception($e->getMessage());
            }
        }

        return $order_item_id;
    }

    # 구매확정 처리
    public function complete($order_item_id)
    {

        if (empty($order_item_id)) {

            throw new Exception('주문 아이디가 없습니다.');
        }

        $order_item = $this->obj->service_model->get_order_item('row', [
            "id = '{$order_item_id}'"
        ]);

        if (empty($order_item)) {

            throw new Exception('존재하지 않는 주문입니다.');
        }

        if ($order_item['status'] == 'completed') {

            // throw new Exception('이미 구매확정된 주문입니다.');
        }

        if (!empty($order_item['user_id'])) {

            try {

                // $this->obj->point_service->buyRewardAdmin($order_item_id);

                $res = $this->obj->service_model->update_order_item(DEBUG, [
                    'status' => 'completed',
                ], [
                    "id = '{$order_item_id}'"
                ]);

                if (empty($res)) {

                    throw new Exception(DB_ERR_MSG);
                }
            } catch (Exception $e) {

                throw new Exception($e->getMessage());
            }
        }

        return true;
    }

    # 주문 취소 처리
    public function cancel($order_item_id)
    {

        if (empty($order_item_id)) {

            throw new Exception('주문 아이디가 없습니다.');
        }

        $order_item = $this->obj->service_model->get_order_item('row', [
            "id = '{$order_item_id}'"
        ]);

        if (empty($order_item)) {

            throw new Exception('존재하지 않는 주문입니다.');
        }

        if ($order_item['status'] == 'shipped' || $order_item['status'] == 'completed') {

            throw new Exception('취소할 수 없는 주문입니다.');
        }

        if ($order_item['status'] == 'canceled') {

            throw new Exception('이미 취소된 주문입니다.');
        }

        switch ($order_item['payment_method']) {
            case '무통장입금':
                try {
                    $cancel_res = $this->obj->payaction->cancel($order_item['id'], $order_item['payment_log_id']);
                } catch (Exception $e) {

                    throw new Exception($e->getMessage());
                }
                break;

            case '카드':

                try {
                    /**
                     * Array
(
    [EdiDate] => 20250809141937
    [RemainTaxAmt] => 000000000000
    [CancelNum] => 
    [RemainAmt] => 000000000000
    [CancelAmt] => 000000022000
    [CancelPwd] => 646464
    [CancelMsg] => 
    [CancelTaxAmt] => 000000000000
    [RemainVatAmt] => 000000000000
    [CancelTaxFreeAmt] => 000000000000
    [CancelTime] => 
    [ErrorMsg] => 취소 완료 거래임(기취소)
    [Mid] => mosi00001m
    [CancelVatAmt] => 000000000000
    [Tid] => mosi00001m01012508091416236973
    [CancelSvcAmt] => 000000000000
    [RemainTaxFreeAmt] => 000000000000
    [CancelDate] => 
    [PayName] => 
    [PayMethod] => CARD
    [ResultMsg] => 취소 완료 거래임(기취소)
    [Moid] => 
    [PTid] => 
    [ErrorCode] => 2013
    [CancelSeq] => 001
    [ResultCode] => 2013
)
                     */
                    $cancel_res = $this->obj->smartro->cancelPay(
                        $order_item['id'],
                        $order_item['payment_log_id']
                    );
                } catch (Exception $e) {

                    throw new Exception($e->getMessage());
                }

                break;
            default:
                throw new Exception('지원하지 않는 결제 방법입니다.');
        }

        /**
         * * 포인트 환급
         * * 주문 아이템에 대한 포인트 환급 로직을 추가합니다.
         * * * 예시: 포인트 환급을 위해 주문 아이템의 ID를 사용하여 포인트를 환급합니다.
         */
        try {

            $this->obj->point_service->buyRewardRollback($order_item_id);
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }

        return true;
    }

    private function make_order_number($user_id)
    {

        return date('YmdHis') . $user_id;
    }

    # 주문 상태 변경
    public function update_status() {}

    # 주문 로그 기록
    public function log($id) {}

    # 주문 로그 조회
    public function history($id) {}
}
