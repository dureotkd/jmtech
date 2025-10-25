<?php

class my extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
            "/Service/user_service",
            "/Service/point_service",
            "/Service/order_service",
        ]);
        $this->load->helper("javascript");

        $this->load->model('/Page/service_model');
    }

    public function index()
    {

        $login_user = $this->user_service->getLoginUser();

        if (empty($login_user)) {

            js_redirect('/login');
        }

        /**
         *        [id] => 4
            [user_id] => 7
            [total_amount] => 25000
            [status] => paid
            [ordered_at] => 2025-07-26 12:18:07
            [number] => 202507261218077
            [cancel_date] => 
            [payment_log_id] => 1
            [payment_method] => 무통장입금
            [paid_at] => 2025-07-26 12:23:23
            [order_item_id] => 1
            [product_id] => 4
            [option_id] => 0
            [quantity] => 1
            [price] => 25000
            [buyer_name] => 신성민
            [buyer_phone] => 010-5653-9944
            [receiver_name] => 신성민
            [receiver_phone] => 010-5653-9944
            [zipcode] => 06120
            [address] => 서울 강남구 강남대로112길 11
            [address_detail] => 112
            [memo] => 
            [name] => 액상 이눌린 스위트린
            [description] => 테스트
            [stock] => 0
            [category_id] => 
            [image_url] => https://mosihealth.com/assets/app_hyup/uploads/products/688189c5d0766.jpg
            [created_at] => 2025-06-29 21:18:48
            [detail_image_urls] => https://mosihealth.com/assets/app_hyup/uploads/products/68818984b3292.jpg,https://mosihealth.com/assets/app_hyup/uploads/products/68818984b3a76.jpg
            [recipe_ids] => 
            [detail_image_urls2] => https://mosihealth.com/assets/app_hyup/uploads/products/68818a15dd9d3.jpg
            [discount_price] => 25000
            [order_detail_id] => 1
         */
        $order_db_items = $this->service_model->exec_sql(
            'all',
            "SELECT
                * ,
                a.status as order_status,
                a.id as order_item_id,
                a.is_multy as is_multy,
                b.id as order_detail_id,
                a.shipping_fee as shipping_fee,
                c.id as product_id
            FROM
                mosihealth.order_item a
            LEFT JOIN
                mosihealth.order_detail b ON a.id = b.order_item_id
            LEFT JOIN
                mosihealth.product c ON b.product_id = c.id
            WHERE
                a.user_id = '{$login_user['id']}' 
            ORDER BY
                a.ordered_at DESC"
        );

        $order_items = [];

        if (!empty($order_db_items)) {

            foreach ($order_db_items as $db_items) {

                if ($db_items['is_multy'] == true) {

                    $bundle_items_cnt = $this->service_model->get_order_bundle_items('one', [
                        "order_item_id = '{$db_items['order_item_id']}'",
                    ]);

                    // 액상 스위트린 포함 총 3종 구성

                    if ($bundle_items_cnt == 1) {
                        // 단일 상품인 경우
                        $db_items['name'] = $db_items['name'];
                    } else if ($bundle_items_cnt > 1) {
                        // 번들 상품인 경우
                        // 예: 액상 스위트린 등 2종
                        $db_items['name'] = $db_items['name'] . '등 ' . ($bundle_items_cnt - 1) . '종';
                    }
                }

                $order_items[] = $db_items;
            }
        }

        $view_data =  [

            'layout_data' => $this->layout_config(),
            'login_user' => $login_user,
            'order_items' => $order_items,
        ];

        $this->layout->view('my_view', $view_data);
    }

    public function cart()
    {
        $login_user = $this->user_service->getLoginUser();

        if (empty($login_user)) {

            alert_redirect('로그인 후 이용해주세요', '/login');
        }

        $view_data =  [
            'layout_data' => $this->layout_config([
                'title' => '장바구니 | 제이엠테크',
                'description' => '제이엠테크 장바구니입니다. 선택한 상품을 확인하고 결제할 수 있습니다.',
            ]),
            'login_user' => $login_user,
        ];

        $this->layout->view('my_cart_view', $view_data);
    }

    public function cancel_order()
    {

        $id = $this->input->post('id');

        $res_array = [
            'ok' => true,
            'msg' => '주문이 취소되었습니다.',
        ];

        try {
            $this->order_service->cancel($id);
        } catch (Exception $e) {

            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    public function order()
    {
        $login_user = $this->user_service->getLoginUser();

        $view_data =  [
            'layout_data' => $this->layout_config(),
            'login_user' => $login_user,
        ];

        $this->layout->view('my_order_view', $view_data);
    }

    public function point()
    {

        $login_user = $this->user_service->getLoginUser();

        $point_log_db = $this->service_model->get_point_log('all', [
            "user_id = '{$login_user['id']}'",
        ]);

        $point_logs = [];

        if (!empty($point_log_db)) {

            $uuqid = uniqid();

            foreach ($point_log_db as $point_log) {

                if ($point_log['related_table'] == 'order') {

                    $order_item = $this->service_model->get_order_item('row', [
                        "id = '{$point_log['related_id']}'",
                    ]);

                    $point_log['number'] = $order_item['number'] ?? '';
                } else {

                    $point_log['number'] = $uuqid . $point_log['related_id'];
                }

                $point_logs[] = $point_log;
            }
        }


        $view_data =  [
            'layout_data' => $this->layout_config(
                [
                    'title' => '포인트 | 제이엠테크',
                    'description' => '제이엠테크 포인트 내역입니다. 포인트 적립 및 사용 내역을 확인할 수 있습니다.',
                ]
            ),
            'login_user' => $login_user,
            'point_logs' => $point_logs,
        ];

        $this->layout->view('my_point_view', $view_data);
    }

    public function review()
    {
        $login_user = $this->user_service->getLoginUser();

        $view_data =  [
            'layout_data' => $this->layout_config(
                [
                    'title' => '리뷰 | 제이엠테크',
                    'description' => '제이엠테크 리뷰 페이지입니다. 작성한 리뷰를 확인하고 수정할 수 있습니다.',
                ]
            ),
            'login_user' => $login_user,
        ];

        $this->layout->view('my_review_view', $view_data);
    }

    public function order_detail()
    {
        $login_user = $this->user_service->getLoginUser();

        if (empty($login_user)) {

            alert_redirect('로그인 후 이용해주세요', '/login');
        }

        $num = $this->input->get('num');

        if (empty($num)) {

            alert_redirect('잘못된 접근입니다.', '/my');
        }

        $order_item = $this->service_model->exec_sql(
            'row',
            "SELECT
                * ,
                a.status as order_status,
                a.is_multy as is_multy,
                a.id as order_item_id,
                b.id as order_detail_id,
                c.id as product_id
            FROM
                mosihealth.order_item a
            LEFT JOIN
                mosihealth.order_detail b ON a.id = b.order_item_id
            LEFT JOIN
                mosihealth.product c ON b.product_id = c.id
            WHERE
                a.user_id = '{$login_user['id']}'
            AND 
                a.number = '{$num}'"
        );

        if ($order_item['is_multy'] == true) {

            $bundle_items_cnt = $this->service_model->get_order_bundle_items('one', [
                "order_item_id = '{$order_item['order_item_id']}'",
            ]);

            // 액상 스위트린 포함 총 3종 구성
            $order_item['name'] = $order_item['name'] . '등 ' . ($bundle_items_cnt - 1) . '종';
        }

        $payment_log = [
            'method' => $order_item['payment_method'],
            'name' => '',
            'amount' => 0,
            'created_at' => '',
            'ok' => false,
            'msg' => ''
        ];

        if ($order_item['payment_method'] == '무통장입금') {

            $payaction_log = $this->service_model->get_payaction_log('row', [
                "a.id = '{$order_item['payment_log_id']}'",
            ]);

            $payment_log['name'] = $payaction_log['transaction_name'];
            $payment_log['amount'] = $payaction_log['amount'];
            $payment_log['created_at'] = $payaction_log['transaction_date'];
            $payment_log['msg'] = $payaction_log['msg'];
            $payaction_log['ok'] = true;
        } else if ($order_item['payment_method'] == '카드') {

            $smartro_payment_log = $this->service_model->get_smartro_payment_log('row', [
                "a.id = '{$order_item['payment_log_id']}'",
            ]);
        }

        if (empty($order_item)) {

            alert_redirect('주문 내역이 없습니다.', '/my');
        }

        $order_detail = $this->service_model->get_order_detail('row', [
            "order_item_id = '{$order_item['order_item_id']}'",
        ]);

        $site_meta_row = $this->service_model->get_site_meta('row', [
            "id = '1'",
        ]);

        $view_data =  [
            'layout_data' => $this->layout_config(),
            'login_user' => $login_user,
            'order_item' => $order_item,
            'order_detail' => $order_detail,
            'payment_log' => $payment_log,
            'site_meta_row' => $site_meta_row,
        ];

        $this->layout->view('my_order_detail_view', $view_data);
    }

    public function withdraw()
    {
        $login_user = $this->user_service->getLoginUser();

        if (empty($login_user)) {

            alert_redirect('로그인 후 이용해주세요', '/login');
        }

        $user_accounts = $this->service_model->get_user_account('all', [
            "user_id = '{$login_user['id']}'",
        ]);

        $point_request_sum = $this->service_model->exec_sql('row', "
            SELECT
                SUM(amount) as amount
            FROM
                mosihealth.point_request
            WHERE
                user_id = '{$login_user['id']}'
                AND type = 'withdraw'
                AND status = 'pending'
        ");


        $view_data =  [
            'layout_data' => $this->layout_config(
                [
                    'title' => '출금 신청 | 제이엠테크',
                    'description' => '제이엠테크 출금 신청 페이지입니다. 출금 계좌를 등록하고 출금 요청을 할 수 있습니다.',
                ]
            ),
            'login_user' => $login_user,
            'user_accounts' => $user_accounts,
            'point_request_sum' => $point_request_sum['amount'] ?? 0,
        ];


        $this->layout->view('my_withdraw_view', $view_data);
    }

    public function edit()
    {
        $res_array = [
            'ok'    => true,
            'msg'   => '',
        ];

        $user_id = $this->input->get('user_id');
        $email = $this->input->get('email');
        $phone = $this->input->get('phone');
        $password = $this->input->get('password');
        $repassword = $this->input->get('repassword');
        $file = $_FILES['file'] ?? null;

        try {

            $this->user_service->update([
                'user_id' => $user_id,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'repassword' => $repassword,
                'file' => $file,
            ]);
        } catch (Exception $e) {

            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        $view_data =  [
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('my_edit_view', $view_data);
    }

    public function update_user()
    {

        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');
        $password = $this->input->post('password');
        $repassword = $this->input->post('repassword');
        $store_code = $this->input->post('store_code');
        $file = $_FILES['profile_image'] ?? null;

        $res_array = [
            'ok'    => true,
            'msg'   => '수정 되었습니다.',
        ];

        foreach ([1] as $proc) {

            try {

                // 사용자 정보 업데이트
                $this->user_service->update([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => $password,
                    'repassword' => $repassword,
                    'store_code' => $store_code,
                    'file' => $file,
                ]);
            } catch (Exception $e) {

                // 예외 발생 시 에러 메시지 설정
                $res_array['ok'] = false;
                $res_array['msg'] = $e->getMessage();
            }
        }

        echo json_encode($res_array);
    }

    public function reg_account()
    {

        $res_array = [
            'ok'    => true,
            'message'   => '',
        ];

        $bank = $this->input->post('bank');
        $account_no = $this->input->post('account_no');
        $name = $this->input->post('name');

        try {

            $this->user_service->createAccount([
                'bank' => $bank,
                'account_no' => $account_no,
                'name' => $name,
            ]);
        } catch (Exception $e) {

            $res_array['ok'] = false;
            $res_array['message'] = $e->getMessage();
            echo json_encode($res_array);
            return;
        }

        echo json_encode($res_array);
    }

    public function delete_account()
    {

        $res_array = [
            'ok'    => true,
            'message'   => '',
        ];

        $id = $this->input->post('id');

        try {

            $this->user_service->deleteAccount($id);
        } catch (Exception $e) {

            $res_array['ok'] = false;
            $res_array['message'] = $e->getMessage();
            echo json_encode($res_array);
            return;
        }

        echo json_encode($res_array);
    }

    public function go_withdraw()
    {
        $amount = $this->input->post('amount');
        $bank = $this->input->post('bank');
        $account_no = $this->input->post('account_no');

        $res_array = [
            'ok'    => true,
            'message'   => '',
        ];

        $login_user = $this->user_service->getLoginUser();

        if (empty($login_user)) {

            $res_array['ok'] = false;
            $res_array['message'] = '로그인 후 이용해주세요.';
            echo json_encode($res_array);
            return;
        }

        // 출금 로직 처리
        // ...
        try {
            $this->point_service->requestWithdraw([
                'amount' => str_replace(',', '', $amount),
                'bank' => $bank,
                'account_no' => $account_no,
            ]);
        } catch (Exception $e) {

            $res_array['ok'] = false;
            $res_array['message'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    private function layout_config($params = [])
    {
        $title = $params['title'] ?? '마이페이지 | 제이엠테크';
        $description = $params['description'] ?? '제이엠테크 마이페이지입니다. 주문 내역, 장바구니, 포인트, 리뷰 등을 확인할 수 있습니다.';

        $this->layout->setLayout("layout/template");
        $this->layout->setTitle($title);
        $this->layout->setDescription($description);
        $this->layout->setAside();
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'banner',
        ];
    }
}
