<?php

class cart extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            'layout',
            'file',
            'smartro',
            'teamroom',
            'payaction',
            '/Service/product_service',
            '/Service/order_service',
            '/Service/user_service',
        ]);

        $this->load->model('/Page/service_model');
    }

    public function index()
    {

        $login_user = $this->user_service->getLoginUser();

        $order_detail_id = $this->input->get('order_detail_id');
        $cart_data = get_cookie('cart_' . $login_user['id']);
        $cart = json_decode($cart_data, true);

        if (empty($order_detail_id)) {

            if (empty($cart)) {

                alert_back('장바구니에 담긴 상품이 없습니다.');
            }
        }

        $product_all = [];
        $total_cnt = 0;
        $total_amount = 0;

        $option1_fee = 0;
        $option1_quantity = 0;
        $zipcode = 0;
        $address = '';
        $address_detail = '';
        $receiver_name = $login_user['name'];
        $receiver_phone = $login_user['phone'];

        if (!empty($order_detail_id)) {

            $order_detail = $this->order_service->get_detail('row', [
                "id = '{$order_detail_id}'"
            ]);

            $order_item = $this->order_service->get_item('row', [
                "id = '{$order_detail['order_item_id']}'"
            ]);

            if (empty($order_detail)) {

                alert_back('주문 상세 정보가 존재하지 않습니다.');
            }


            $zipcode = $order_detail['zipcode'];
            $address = $order_detail['address'];
            $address_detail = $order_detail['address_detail'];
            $receiver_name = $order_detail['receiver_name'];
            $receiver_phone = $order_detail['receiver_phone'];
            $option1_quantity = $order_detail['option1_quantity'] ?? 0;

            $option1_fee = $order_item['option1_fee'] ?? 0;

            if ($order_item['is_multy']) {

                $bundle_items_all = [];
                $bundle_items = $this->service_model->get_order_bundle_items_purchased('all', [
                    "order_item_id = '{$order_item['id']}'"
                ]);

                foreach ($bundle_items as $bundle_item) {

                    $product = $this->product_service->get($bundle_item['product_id']);

                    if (empty($product)) {
                        continue;
                    }

                    $product['quantity'] = $bundle_item['quantity'];
                    $total_cnt += $product['quantity'];
                    $total_amount += $product['price'] * $product['quantity'];

                    $bundle_items_all[] = $product;
                }

                $product_all = $bundle_items_all;
            } else {

                $product = $this->product_service->get($order_detail['product_id']);

                $product['quantity'] = $order_detail['quantity'];
                $total_cnt += $product['quantity'];
                $total_amount += $product['price'] * $product['quantity'];

                $product_all[] = $product;
            }
        } else {

            foreach ($cart as $cart_item) {

                $product = $this->product_service->get($cart_item['product_id']);

                if (empty($product)) {
                    continue;
                }

                if ($product['id'] == $cart_item['product_id']) {

                    $product['quantity'] = $cart_item['quantity'];
                } else {
                    $product['quantity'] = 1; // 기본값 설정
                }

                $total_cnt += $product['quantity'];

                $total_amount += $product['price'] * $product['quantity'];

                $product_all[] = $product;
            }
        }

        if (empty($product_all)) {
            alert_back('장바구니에 담긴 상품이 품절되었습니다.');
            // delete_cookie('cart_' . $login_user['id'], 'www.mosihealth.com', '/');
            force_delete_cookie('cart_' . $login_user['id']);
        }

        if (empty($login_user)) {

            alert_redirect('로그인 후 이용해주세요.', '/login');
        }

        $배송비 = 배송비측정기준($zipcode, $total_amount + $option1_fee);

        $view_data =  [
            'layout_data' => $this->layout_config(),
            'login_user' => $login_user,
            'product_all' => $product_all,
            'cart' => $cart,
            'total_cnt' => $total_cnt,
            'tday' => date('YmdHis'),
            'option1_fee' => $option1_fee,
            'option1_quantity' => $option1_quantity,
            'zipcode' => $zipcode,
            'address' => $address,
            'address_detail' => $address_detail,
            'receiver_name' => $receiver_name,
            'receiver_phone' => $receiver_phone,
            '배송비' => $배송비,
        ];

        $this->layout->view('cart_view', $view_data);
    }

    public function getEncryptData()
    {

        $total_amount = $this->input->post('total_amount');
        $tday = $this->input->post('tday');

        $EncryptData = base64_encode(hash('sha256', $tday . MID . $total_amount . MKEY, true));

        echo $EncryptData;
    }


    # TODO: 상품 무통장 결제 pay_bank_transfer_view
    public function pay_bank()
    {
        $res_array = [
            'ok' => true,
            'msg' => '',
            'redirect_url' => '/product/order',
        ];

        $product_ids = $this->input->post('product_id');
        $quantitys = $this->input->post('quantity');
        $prices = $this->input->post('price');
        $shipping_fee = $this->input->post('shipping_fee') ?? 0;
        $option1_quantity = $this->input->post('option1_quantity') ?? 0;

        $receiver_name = $this->input->post('receiver_name');
        $receiver_phone = $this->input->post('receiver_phone');
        $zipcode = $this->input->post('zipcode');
        $address = $this->input->post('address');
        $address_detail = $this->input->post('address_detail');
        $memo = $this->input->post('memo');

        $login_user = $this->user_service->getLoginUser();

        if (count($product_ids) != count($quantitys) || count($product_ids) != count($prices)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '상품 정보가 올바르지 않습니다.';
            alert_back($res_array['msg']);
            exit;
        }

        if (empty($login_user)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '로그인 후 이용해주세요.';
            alert_back($res_array['msg']);
            exit;
        }

        $product_id = 0;
        $quantity = 0;
        $amount = 0;
        $bundle_items = [];

        foreach ($product_ids as $index => $product_id) {

            $tmp_quantity = $quantitys[$index] ?? 1;
            $tmp_price = $prices[$index] ?? 0;

            if (empty($product_id) || empty($tmp_quantity) || empty($tmp_price)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '상품 정보가 올바르지 않습니다.';
                alert_back($res_array['msg']);
                exit;
            }

            // 총 금액 계산
            $quantity += $tmp_quantity;
            $amount += ($tmp_price * $tmp_quantity);

            $bundle_items[] = [
                'product_id' => $product_id,
                'quantity' => $tmp_quantity,
                'price' => $tmp_price,
            ];
        }
        // printr($_REQUEST);
        // printr($bundle_items);
        // printr($quantity);
        // printr($amount);
        // exit;

        // $smartro_payment_log = $this->service_model->get_smartro_payment_log('row', [
        //     "Tid = '{$Tid}'"
        // ]);

        try {

            $order_item_id = $this->order_service->create([
                // ^ -------------- 제품정보 -----------
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $amount,
                'amount' => $amount,
                'option1_quantity' => $option1_quantity,
                'bundle_items' => $bundle_items,

                // ^ ----------- 배송정보 -----------
                'shipping_fee' => $shipping_fee,
                'buyer_name' => $login_user['name'],
                'buyer_phone' => $login_user['phone'],
                'receiver_name' => $receiver_name,
                'receiver_phone' => $receiver_phone,
                'zipcode' => $zipcode,
                'address' => $address,
                'address_detail' => $address_detail,
                'memo' => $memo,

                // ^ ----------- 결제정보 -----------
                'payment_method' => '무통장입금',
            ]);

            if (empty($order_item_id)) {

                alert_back(DB_ERR_MSG . ' 주문 생성에 실패했습니다.');
            }
        } catch (Throwable $e) {

            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
            $res_array['redirect_url'] = 'back';

            alert_back($res_array['msg']);
            exit;
        }

        if (!empty($res_array['ok'])) {

            js_redirect('/cart/pay_bank_transfer?id=' . $order_item_id);

            // delete_cookie("cart_" . $login_user['id']);

            // $order_item = $this->order_service->get_item('row', [
            //     "id = '{$order_item_id}'"
            // ]);

            // $order_detail = $this->order_service->get_detail('row', [
            //     "order_item_id = '{$order_item_id}'"
            // ]);

            // $site_meta_row = $this->service_model->get_site_meta('row', [
            //     "id = 1"
            // ]);

            // $this->layout->view('pay_bank_transfer_view', [
            //     'layout_data' => $this->layout_config(),

            //     'order_item' => $order_item,
            //     'order_detail' => $order_detail,
            //     'site_meta_row' => $site_meta_row,
            //     'order_item_id' => $order_item_id,
            //     'product_id' => $product_id,
            //     'quantity' => $quantity,
            //     'receiver_name' => $receiver_name,
            //     'receiver_phone' => $receiver_phone,
            //     'zipcode' => $zipcode,
            //     'address' => $address,
            //     'address_detail' => $address_detail,
            //     'memo' => $memo,
            // ]);
        }
    }

    public function pay_bank_transfer()
    {

        $order_item_id = $this->input->get('id');

        $login_user = $this->user_service->getLoginUser();
        $order_item = $this->order_service->get_item('row', [
            "id = '{$order_item_id}'"
        ]);

        if ($login_user['id'] != $order_item['user_id']) {
            alert_back('잘못된 접근입니다.');
        }

        delete_cookie("cart_" . $login_user['id']);

        $order_detail = $this->order_service->get_detail('row', [
            "order_item_id = '{$order_item_id}'"
        ]);

        $site_meta_row = $this->service_model->get_site_meta('row', [
            "id = 1"
        ]);

        $this->layout->view('pay_bank_transfer_view', [
            'layout_data' => $this->layout_config(),
            'order_item' => $order_item,
            'order_detail' => $order_detail,
            'site_meta_row' => $site_meta_row,
            'order_item_id' => $order_item_id,
        ]);
    }

    public function set_mobile_product()
    {

        $mobile_pay_key = $_REQUEST['mobile_pay_key'] ?? '';
        $user_id = $_REQUEST['user_id'] ?? '';

        $this->teamroom->send('개발자', join("\n", [
            "-------- 결제셋팅 (제발) --------",
            "mobile_pay_key : " . print_r($mobile_pay_key, true),
            "login_user : " . $user_id,
        ]));

        $insert_mobile_pay_res = $this->service_model->insert_mobile_pay(DEBUG, [
            'user_id'       => $user_id,
            'data'          => $mobile_pay_key,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        if (empty($insert_mobile_pay_res)) {
            echo json_encode([
                'ok' => false,
                'msg' => '결제셋팅에 실패했습니다.',
            ]);
            exit;
        }

        echo json_encode([
            'ok' => true,
            'msg' => '',
        ]);
    }

    # TODO: 상품 카드(스마트로) 결제 pay_success_view
    public function pay()
    {
        $res_array = [
            'ok' => true,
            'msg' => '결제가 완료되었습니다.',
            'redirect_url' => '',
        ];

        $Tid = $this->input->post('Tid');
        $TrAuthKey = $this->input->post('TrAuthKey');

        $user_id = $this->input->post('user_id');

        $product_ids = $this->input->post('product_id');
        $quantitys = $this->input->post('quantity');
        $option1_quantity = $this->input->post('option1_quantity') ?? 0;

        $prices = $this->input->post('price');
        $shipping_fee = $this->input->post('shipping_fee') ?? 0;
        $amount = $this->input->post('amount');

        $receiver_name = $this->input->post('receiver_name');
        $receiver_phone = $this->input->post('receiver_phone');
        $zipcode = $this->input->post('zipcode');
        $address = $this->input->post('address');
        $address_detail = $this->input->post('address_detail');
        $memo = $this->input->post('memo');

        if (IS_MOBILE) {

            $extra = json_decode(base64_decode($_REQUEST['MerReqData']), true);
            $user_id = $extra['MallReserved'] ?? null;

            $login_user = $this->user_service->get($user_id);

            $mobile_pay = $this->service_model->get_mobile_pay('row', [
                "user_id = '{$login_user['id']}'"
            ]);

            // 1. URL 디코딩
            $json = urldecode($mobile_pay['data']);

            // 2. JSON → 배열 변환
            $mobile_pay_array = json_decode($json, true);

            $this->teamroom->send('개발자', join("\n", [
                "-------- 결제 (장바구니) --------",
                "user_id :" . $user_id,
                "session : " . print_r($_SESSION, true),
                "request : " . print_r($_REQUEST, true),
                "mobile_pay_key : " . print_r($mobile_pay_array, true)
            ]));

            if (empty($mobile_pay_array)) {

                $this->teamroom->send('개발자', join("\n", [
                    "-------- 결제오류 (장바구니) --------",
                    "상단을 확인하세요"
                ]));

                $res_array['ok'] = false;
                $res_array['msg'] = '결제 정보가 없습니다. 다시 시도해주세요.';
                alert_back($res_array['msg']);
                exit;
            }

            $product_ids = $mobile_pay_array['product_id'] ?? '';
            $quantitys = $mobile_pay_array['quantity'] ?? 1;
            $prices = $mobile_pay_array['price'] ?? 0;

            $receiver_name = $mobile_pay_array['receiver_name'] ?? $login_user['name'];
            $receiver_phone = $mobile_pay_array['receiver_phone'] ?? $login_user['phone'];
            $zipcode = $mobile_pay_array['zipcode'] ?? '';
            $address = $mobile_pay_array['address'] ?? '';
            $address_detail = $mobile_pay_array['address_detail'] ?? '';
            $memo = $mobile_pay_array['memo'] ?? '';
            $shipping_fee = $mobile_pay_array['shipping_fee'] ?? 0;
            $option1_quantity = $mobile_pay_array['option1_quantity'] ?? 0;
        } else {

            $login_user = $this->user_service->get($user_id);

            if (empty($login_user)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '로그인 후 이용해주세요.';
                alert_back($res_array['msg']);
                exit;
            }
        }

        if (is_array($product_ids)) {

            if (count($product_ids) != count($quantitys) || count($product_ids) != count($prices)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '상품 정보가 올바르지 않습니다.';
                alert_back($res_array['msg']);
                exit;
            }
        }

        if (empty($login_user)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '로그인 후 이용해주세요.';
            alert_back($res_array['msg']);
            exit;
        }

        // 복수상품 (2종 이상)
        if (is_array($product_ids)) {

            $product_id = 0;
            $quantity = 0;
            $amount = 0;
            $price = 0;
            $bundle_items = [];

            foreach ($product_ids as $index => $product_id) {

                $tmp_quantity = $quantitys[$index] ?? 1;
                $tmp_price = $prices[$index] ?? 0;

                if (empty($product_id) || empty($tmp_quantity) || empty($tmp_price)) {

                    $res_array['ok'] = false;
                    $res_array['msg'] = '상품 정보가 올바르지 않습니다.';
                    alert_back($res_array['msg']);
                    exit;
                }

                $product_id = $product_id;
                $quantity += $tmp_quantity;
                $amount += ($tmp_price * $tmp_quantity);
                $price = $tmp_price;
                // 총 금액 계산


                $bundle_items[] = [
                    'product_id' => $product_id,
                    'quantity' => $tmp_quantity,
                    'price' => $tmp_price,
                ];
            }

            // 단일상품 (1종)
        } else {

            $product_id = $product_ids;
            $quantity = $quantitys;
            $amount = $prices;
            $price = $prices;
            $bundle_items = [];
        }

        $already_paid = false;

        try {

            $smartro_payment_log = $this->service_model->get_smartro_payment_log('row', [
                "Tid = '{$Tid}'"
            ]);

            if (empty($smartro_payment_log)) {

                /**
                 * 1. 결제 승인
                 * - INSERT smartro_payment_log
                 */
                $payment_log_id = $this->smartro->approvePay($Tid, $TrAuthKey);

                if (empty($payment_log_id)) {

                    throw new Exception(DB_ERR_MSG . ' 결제 승인에 실패했습니다.');
                }

                /**
                 * 2. 주문 생성
                 * - INSERT order_item (주문 정보)
                 * - INSERT order_detail (주문 상세 정보)
                 * - 회원 포인트 적립
                 */
                $order_item_id = $this->order_service->create([
                    'user_id' => $user_id,

                    // ^ -------------- 제품정보 -----------
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'amount' => $amount,
                    'status' => 'paid',
                    'paid_at' => date('Y-m-d H:i:s'),
                    'shipping_fee' => $shipping_fee,
                    'option1_quantity' => $option1_quantity,
                    'bundle_items' => $bundle_items,

                    // ^ ----------- 배송정보 -----------
                    'buyer_name' => $login_user['name'],
                    'buyer_phone' => $login_user['phone'],
                    'receiver_name' => $receiver_name,
                    'receiver_phone' => $receiver_phone,
                    'zipcode' => $zipcode,
                    'address' => $address,
                    'address_detail' => $address_detail,
                    'memo' => $memo,

                    // ^ ----------- 결제정보 -----------
                    'payment_method' => '카드',
                    'payment_log_id' => $payment_log_id,
                ]);

                if (empty($order_item_id)) {

                    alert_redirect(DB_ERR_MSG . ' 주문 생성에 실패했습니다.', '/product/order?product_id=' . $product_id);
                }
            } else {

                $already_paid = true;
                $payment_log_id = $smartro_payment_log['id'];
                $order_item_row = $this->service_model->get_order_item('row', [
                    "payment_log_id = '{$payment_log_id}'",
                    "payment_method = '카드'",
                ]);

                if (empty($order_item_row)) {

                    alert_redirect('결제 정보가 존재하지않습니다', '/product/order?product_id=' . $product_id);
                }

                $order_item_id = $order_item_row['id'];
            }
        } catch (Exception $e) {

            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
            $res_array['redirect_url'] = 'back';

            alert_back($res_array['msg']);
            exit;
        }

        if (!empty($res_array['ok'])) {

            delete_cookie("cart_" . $login_user['id']);

            $order_item = $this->order_service->get_item('row', [
                "id = '{$order_item_id}'"
            ]);

            $smartro_payment_log = $this->service_model->get_smartro_payment_log('row', [
                "id = '{$payment_log_id}'"
            ]);

            $this->layout->view('pay_success_view', [
                'layout_data' => $this->layout_config(),

                'order_item' => $order_item,
                'smartro_payment_log' => $smartro_payment_log,

                'already_paid' => $already_paid,
                'order_item_id' => $order_item_id,
                'product_id' => $product_id,
                'quantity' => $quantity,
                'receiver_name' => $receiver_name,
                'receiver_phone' => $receiver_phone,
                'zipcode' => $zipcode,
                'address' => $address,
                'address_detail' => $address_detail,
                'memo' => $memo,
                'payment_method' => '카드',
            ]);
        }
    }

    private function layout_config()
    {

        $this->layout->setLayout("layout/template");
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'banner',
        ];
    }
}
