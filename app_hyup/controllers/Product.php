<?php

class product extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            'layout',
            'file',
            'smartro',
            'payaction',
            '/Service/product_service',
            '/Service/order_service',
            '/Service/user_service',
        ]);

        $this->load->model('/Page/service_model');
    }

    # 쇼핑하기
    public function index()
    {
        $product_id = $this->input->get('id');
        $category = $this->input->get('category') ?? '';

        if ($product_id) {

            $this->product_detail_view($product_id);
        } else {

            $this->product_list_view();
        }
    }


    private function product_list_view()
    {

        $products = $this->product_service->all(10);

        $view_data = [
            'layout_data' => $this->layout_config(),
            'products' => $products,
        ];

        $this->layout->view('product_list_view', $view_data);
    }

    private function product_detail_view($product_id)
    {
        if (empty($product_id)) {
            show_404();
        }

        $product = $this->product_service->get($product_id);

        $reviews = $this->product_service->get_reviews($product_id);
        $photo_reviews = $this->product_service->get_photo_reviews($product_id);

        $qnas = $this->service_model->get_product_qna('all', ['product_id' => $product_id]);
        $avg_review = [];

        if (!empty($reviews)) {

            $total_rating = 0;
            $total_reviews = count($reviews);

            foreach ($reviews as $review) {

                $total_rating += $review['rating'];
                @$avg_review[$review['rating']]++;
            }

            $avg_review['rating'] = round($total_rating / $total_reviews, 1);
        }

        $view_data = [
            'layout_data' => $this->layout_config(
                [
                    'title' => $product['name'] . ' | 제이엠테크',
                    'description' => $product['description'],
                ]
            ),
            'product' => $product,
            'product_id' => $product_id,
            'photo_reviews' => $photo_reviews,
            'reviews' => $reviews,
            'qnas' => $qnas,
            'avg_review' => $avg_review,
            'login_user' => $this->user_service->getLoginUser(),
        ];

        $this->layout->view('product_view', $view_data);
    }

    public function order()
    {

        $product_id = $this->input->get('product_id');
        $quantity = $this->input->get('quantity');
        $option_id = $this->input->get('option_id') ?? 0;
        $order_detail_id = $this->input->get('order_detail_id');

        if (empty($product_id) || empty($quantity)) {
            show_404();
        }

        $login_user = $this->user_service->getLoginUser();

        if (empty($login_user)) {
            alert_redirect('로그인 후 이용해주세요.', '/login');
        }

        /**
         * Array
(
    [id] => 4
    [name] => 마이노멀 저당 아이스바
    [description] => 리얼 과육이 들어간 저당 아이스바 2종!

입 안 가득 퍼지는 상큼함으로 무더위 건강하게 함께 날려요.
올해 우리집 여름 간식은 마이노멀 저당 아이스바로 해결

✔️ 당류 오직 1g! 칼로리 25kcal!
✔️ 딸기 / 청사과 2가지 맛으로 입안 가득 느껴지는 상큼함
✔️ 진짜 과육이 들어가서 더욱 아삭하고 새콤달콤하게
✔️ 비타민 C 1일 기준치 100% 함유
    [price] => 50000
    [stock] => 0
    [category_id] => 
    [image_url] => https://mosihealth.test/assets/app_hyup/uploads/products/6861315f42c2a.png
    [created_at] => 2025-06-29 21:18:48
    [detail_image_urls] => https://mosihealth.test/assets/app_hyup/uploads/products/68612f2899307.png,https://mosihealth.test/assets/app_hyup/uploads/products/68612f28996f4.png
    [recipe_ids] => 
    [detail_image_urls2] => https://mosihealth.test/assets/app_hyup/uploads/products/68612f2899aa9.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f2899e0a.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f289a0fb.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f289a491.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f289a7d2.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f289aaef.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f289ae93.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f289b220.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f28a0ed3.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f28a12b4.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f28a1698.jpg,https://mosihealth.test/assets/app_hyup/uploads/products/68612f28a19e3.jpg
    [ori_price] => 50000
    [discount] => 50000
    [image_array] => Array
        (
            [0] => https://mosihealth.test/assets/app_hyup/uploads/products/6861315f42c2a.png
            [1] => https://mosihealth.test/assets/app_hyup/uploads/products/68612f2899307.png
            [2] => https://mosihealth.test/assets/app_hyup/uploads/products/68612f28996f4.png
        )

)
         */
        $product = $this->product_service->get($product_id);

        $expire_time = date('YmdHis', strtotime('+20 minutes')); // 20분 후의 시간
        $expire_time = substr($expire_time, 0, 12);                 // YYYYMMDDhhmm

        $zipcode = 0;
        $address = '';
        $address_detail = '';
        $receiver_name = $login_user['name'];
        $receiver_phone = $login_user['phone'];

        if (!empty($order_detail_id)) {

            $order_detail = $this->order_service->get_detail('row', [
                "id = '{$order_detail_id}'"
            ]);

            $zipcode = $order_detail['zipcode'];
            $address = $order_detail['address'];
            $address_detail = $order_detail['address_detail'];
            $receiver_name = $order_detail['receiver_name'];
            $receiver_phone = $order_detail['receiver_phone'];
        }

        $배송비 = 배송비측정기준($zipcode, $product['price'] * $quantity);

        $view_data = [
            'layout_data' => $this->layout_config(),
            'product' => $product,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'option_id' => $option_id,
            'login_user' => $login_user,
            'tday' => date('YmdHis'),

            'zipcode' => $zipcode,
            'address' => $address,
            'address_detail' => $address_detail,
            'receiver_name' => $receiver_name,
            'receiver_phone' => $receiver_phone,

            // 현재시간에서 20분후
            'expire_time' => $expire_time,
            '배송비' => $배송비,
        ];

        $this->layout->view('product_order_view', $view_data);
    }

    public function payment()
    {


        $this->layout->view('product_payment_view', [
            'layout_data' => $this->layout_config(),
        ]);
    }

    public function create_qna()
    {
        $product_id = $this->input->post('product_id');
        $title = $this->input->post('title');
        $content = $this->input->post('content');
        $is_secret = $this->input->post('is_secret') ? 1 : 0;

        $res_array = [
            'ok' => true,
            'msg' => 'Q&A가 등록되었습니다.',
        ];

        $login_user =  $this->user_service->getLoginUser();

        if (empty($login_user)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '.';
            echo json_encode($res_array);
            exit;
        }

        if (empty($product_id)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '상품을 선택해주세요.';
            echo json_encode($res_array);
            exit;
        }

        if (empty($title)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '제목을 입력해주세요.';
            echo json_encode($res_array);
            exit;
        }

        if (empty($content)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '내용을 입력해주세요.';
            echo json_encode($res_array);
            exit;
        }

        $this->service_model->insert_product_qna(DEBUG, [
            'product_id' => $product_id,
            'title' => $title,
            'content' => $content,
            'writer_name' => $login_user['name'],
            'writer_user_id' => $login_user['user_id'],
            'is_secret' => $is_secret,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        echo json_encode($res_array);
        exit;
    }

    public function create_review()
    {
        $product_id = $this->input->post('product_id');
        $rating = $this->input->post('rating');
        $content = $this->input->post('content');

        $res_array = [
            'ok' => true,
            'msg' => '리뷰가 등록되었습니다.',
        ];

        $login_user =  $this->user_service->getLoginUser();

        if (empty($login_user)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '로그인 후 이용해주세요.';
            echo json_encode($res_array);
            exit;
        }

        if (empty($product_id)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '상품을 선택해주세요.';
            echo json_encode($res_array);
            exit;
        }

        if (empty($rating)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '평점을 선택해주세요.';
            echo json_encode($res_array);
            exit;
        }

        if (empty($content)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '리뷰 내용을 입력해주세요.';
            echo json_encode($res_array);
            exit;
        }

        $insert_data = [
            'product_id' => $product_id,
            'rating' => $rating,
            'content' => $content,
            'user_login_id' => $login_user['user_id']
        ];

        try {

            if ($_FILES['review_images']['error'][0] == UPLOAD_ERR_OK) {

                $review_images = $this->file->upload_multiple('review_images', '/assets/app_hyup/uploads/review', 5, ['jpg', 'jpeg', 'png', 'gif']);
                $review_image_urls = [];

                if (!empty($review_images)) {

                    foreach ($review_images as $key => $file) {

                        if ($file['status'] == 'success') {

                            $review_image_urls[] = $file['fileSrc'];
                        }
                    }
                }

                $insert_data['image_urls'] = join(',', $review_image_urls);
            }

            $res = $this->service_model->insert_review(DEBUG, $insert_data);

            if (empty($res)) {
                $res_array['ok'] = false;
                $res_array['msg'] = DB_ERR_MSG;
            }
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
        exit;
    }

    public function create_faq() {}

    # TODO: 상품 무통장 결제 pay_bank_transfer_view
    public function pay_bank()
    {
        $res_array = [
            'ok' => true,
            'msg' => '',
            'redirect_url' => '/product/order',
        ];

        $product_id = $this->input->post('product_id');
        $quantity = $this->input->post('quantity');
        $price = $this->input->post('price');
        $amount = $this->input->post('amount');
        $shipping_fee = $this->input->post('shipping_fee') ?? 0;
        $option1_quantity = $this->input->post('option1_quantity') ?? 0;

        $receiver_name = $this->input->post('receiver_name');
        $receiver_phone = $this->input->post('receiver_phone');
        $zipcode = $this->input->post('zipcode');
        $address = $this->input->post('address');
        $address_detail = $this->input->post('address_detail');
        $memo = $this->input->post('memo');

        $login_user = $this->user_service->getLoginUser();

        try {

            $order_item_id = $this->order_service->create([
                // ^ -------------- 제품정보 -----------
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $price,
                'amount' => $amount,
                'option1_quantity' => $option1_quantity,

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

                alert_redirect(DB_ERR_MSG . ' 주문 생성에 실패했습니다.', '/product/order?product_id=' . $product_id);
            }
        } catch (Throwable $e) {

            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
            $res_array['redirect_url'] = 'back';

            alert_back($res_array['msg']);
            exit;
        }

        if (!empty($res_array['ok'])) {

            $order_item = $this->order_service->get_item('row', [
                "id = '{$order_item_id}'"
            ]);

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
                'product_id' => $product_id,
                'quantity' => $quantity,
                'receiver_name' => $receiver_name,
                'receiver_phone' => $receiver_phone,
                'zipcode' => $zipcode,
                'address' => $address,
                'address_detail' => $address_detail,
                'memo' => $memo,
            ]);
        }
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

        $product_id = $this->input->post('product_id');
        $quantity = $this->input->post('quantity');
        $price = $this->input->post('price');
        $amount = $this->input->post('amount');
        $receiver_name = $this->input->post('receiver_name');
        $receiver_phone = $this->input->post('receiver_phone');
        $zipcode = $this->input->post('zipcode');
        $address = $this->input->post('address');
        $address_detail = $this->input->post('address_detail');
        $memo = $this->input->post('memo');
        $shipping_fee = $this->input->post('shipping_fee') ?? 0;
        $option1_fee = $this->input->post('option1_fee') ?? 0;
        $option1_quantity = $this->input->post('option1_quantity') ?? 0;

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
                "-------- 결제 (바로결제) --------",
                "session : " . print_r($_SESSION, true),
                "request : " . print_r($_REQUEST, true),
                "mobile_pay_key : " . print_r($mobile_pay_array, true)
            ]));

            if (empty($mobile_pay_array)) {

                $this->teamroom->send('개발자', join("\n", [
                    "-------- 결제오류 (바로결제) --------",
                    "상단을 확인하세요"
                ]));

                $res_array['ok'] = false;
                $res_array['msg'] = '결제 정보가 없습니다. 다시 시도해주세요.';
                alert_back($res_array['msg']);
                exit;
            }

            $user_id = $mobile_pay_array['user_id'] ?? '';
            $product_id = $mobile_pay_array['product_id'] ?? '';
            $quantity = $mobile_pay_array['quantity'] ?? 1;
            $price = $mobile_pay_array['price'] ?? 0;
            $amount = $mobile_pay_array['amount'] ?? 0;
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
                    'option1_quantity' => $option1_quantity,

                    // ^ ----------- 배송정보 -----------
                    'buyer_name' => $login_user['name'],
                    'buyer_phone' => $login_user['phone'],
                    'receiver_name' => $receiver_name,
                    'receiver_phone' => $receiver_phone,
                    'zipcode' => $zipcode,
                    'address' => $address,
                    'address_detail' => $address_detail,
                    'memo' => $memo,
                    'shipping_fee' => $shipping_fee,

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

            // alert_redirect($res_array['msg'], '/product/order?product_id=' . $product_id);

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

    public function pay_stop() {}

    private function layout_config($params = [])
    {
        $title = $params['title'] ?? '상품 | 제이엠테크';
        $description = $params['description'] ?? '제이엠테크 상품 페이지입니다. 다양한 상품을 확인하고 구매할 수 있습니다.';

        $this->layout->setLayout("layout/template");
        $this->layout->setTitle($title);
        $this->layout->setDescription($description);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'banner',
        ];
    }
}
