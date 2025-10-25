<?php

class order extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
            "alarmtalk",
            "/Service/user_service",
            "/Service/order_service",
        ]);

        $this->load->model('/Page/service_model');
    }

    public function index()
    {
        $search_type = $this->input->get('search_type');
        $search_value = $this->input->get('search_value');
        $search_order_status = $this->input->get('search_order_status');
        $excel_yn = $this->input->get('excel_yn') ?? 'N';
        $page = $this->input->get('page') ?? 1;

        $search_order_status_item = get_search_item_v2([
            'vo'            => unserialize(ORDER_STATUS),
            'select'        => $search_order_status,
            'add' => [
                'all' => '주문상태 전체'
            ],
            'tag'           => 's',
        ]);

        $search_type_item = get_search_item_v2(array(
            'vo'            => [
                'all' => '전체',
                'id'  => '아이디',
                'name' => '이름',
                'phone' => '연락처',
                'number' => '주문번호',
            ],
            'select'        => $search_type,
            'tag'           => 's',
        ));

        $where = [1];

        if (!empty($search_order_status) && $search_order_status != 'all') {

            $where[] = "a.status = '{$search_order_status}'";
        }

        if (!empty($search_type) && !empty($search_value)) {

            if ($search_type == 'all') {

                $where[] =
                    "(
                    d.user_id LIKE '%{$search_value}%' 
                    OR d.name LIKE '%{$search_value}%' 
                    OR d.phone LIKE '%{$search_value}%'
                    OR a.number LIKE '%{$search_value}%'
                    )";
            } else {

                if ($search_type == 'id') {
                    $where[] = "d.user_id LIKE '%{$search_value}%'";
                } else if ($search_type == 'name') {
                    $where[] = "d.name LIKE '%{$search_value}%'";
                } else if ($search_type == 'phone') {
                    $where[] = "d.phone LIKE '%{$search_value}%'";
                } else if ($search_type == 'number') {
                    $where[] = "a.number LIKE '%{$search_value}%'";
                }
            }
        }

        $order_items_db = $this->service_model->exec_sql(
            'all',
            sprintf("SELECT
                * ,
                a.is_multy as is_multy,
                a.status as order_status,
                a.id as order_item_id,
                b.id as order_detail_id,
                b.zipcode as zipcode,
                b.address as address,
                b.address_detail as address_detail,
                b.memo as order_memo,
                c.id as product_id,
                c.name as product_name,
                d.id as customer_id,
                d.name as customer_name,
                d.phone as customer_phone,
                d.email as customer_email,
                (SELECT AppCardName FROM mosihealth.smartro_payment_log WHERE id = a.payment_log_id) as app_card_name
            FROM
                mosihealth.order_item a
            LEFT JOIN
                mosihealth.order_detail b ON a.id = b.order_item_id
            LEFT JOIN
                mosihealth.product c ON b.product_id = c.id
            INNER JOIN
                mosihealth.user d ON a.user_id = d.id
            WHERE
                %s
            ORDER BY
                a.ordered_at DESC", join(' AND ', $where))
        );

        $order_items = [];

        if (!empty($order_items_db)) {

            foreach ($order_items_db as $row) {

                if ($row['is_multy'] == true) {

                    $bundle_items_all = $this->service_model->exec_sql(
                        'all',
                        sprintf(
                            "SELECT 
                                * , 
                                a.id as order_item_id,
                                b.id as order_bundle_item_id,
                                b.price as bundle_item_price,
                                b.amount as bundle_item_amount,
                                b.quantity as bundle_item_quantity,
                                c.name as product_name
                            FROM 
                                mosihealth.order_item a,
                                mosihealth.order_bundle_items b,
                                mosihealth.product c
                            WHERE 
                                a.id = b.order_item_id
                            AND 
                                b.product_id = c.id
                            AND 
                                a.id = '%s'
                            ",
                            $row['order_item_id']
                        )
                    );

                    $bundle_items_cnt = count($bundle_items_all);

                    $row['bundle_items'] = $bundle_items_all;
                    $row['bundle_items_cnt'] = $bundle_items_cnt;

                    // 액상 스위트린 포함 총 3종 구성

                    if ($bundle_items_cnt == 1) {
                        // 단일 상품인 경우
                        $row['product_name'] = $row['product_name'];
                    } else if ($bundle_items_cnt > 1) {
                        // 번들 상품인 경우
                        // 예: 액상 스위트린 등 2종
                        $row['product_name'] = $row['product_name'] . '등 ' . ($bundle_items_cnt - 1) . '종';
                    }
                }

                // if ($row['payment_method'] == '무통장입금') {

                //     $payment_log = $this->service_model->get_payaction_log('row', [
                //         "id = '{$row['payment_log_id']}'"
                //     ]);
                // } else if ($row['payment_method'] == '카드') {
                //     $payment_log = $this->service_model->get_payaction_log('row', [
                //         "id = '{$row['payment_log_id']}'"
                //     ]);
                // }

                // $row['payment_log'] = $payment_log;
                $order_items[] = $row;
            }
        }

        $layout_data = $this->layout_config($excel_yn == 'Y' ? "layout/blank" : "layout/admin");

        $view_data =  [

            'layout_data'           => $layout_data,
            'order_items'           => $order_items,

            'search_order_status_item' => $search_order_status_item,
            'search_type_item'      => $search_type_item,
            'search_order_status' => $search_order_status,
            'search_type'           => $search_type,
            'search_value'          => $search_value,
            'excel_yn'          => $excel_yn,
            'page' => $page,
        ];

        if ($excel_yn == 'Y') {

            $this->output->enable_profiler(false);

            $excel_file_name = "주문관리_" . date('Ymd') . ".xls";

            // Excel 파일로 다운로드 (header ms excel)
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=" . $excel_file_name . ".xls");
            header("Content-Transfer-Encoding: binary");
            header("Pragma: no-cache");
            header("Expires: 0");

            $this->layout->view('/Admin/Excel/order_excel_view', $view_data);
        } else {

            $this->layout->view('/Admin/order_view', $view_data);
        }
    }

    public function update_admin_memo()
    {

        $id = $this->input->post('id');
        $memo = $this->input->post('memo');

        $res_array = [
            'ok' => true,
            'msg' => '',
        ];

        $this->service_model->update_order_detail(DEBUG, [
            'admin_memo' => $memo,
        ], [
            "order_item_id = '{$id}'"
        ]);

        echo json_encode($res_array);
        exit;
    }

    public function update_tracking_number()
    {

        $id = $this->input->post('id');
        $tracking_number = $this->input->post('tracking_number');

        $res_array = [
            'ok' => true,
            'msg' => '',
        ];

        $this->service_model->update_order_detail(DEBUG, [
            'tracking_number' => $tracking_number,
        ], [
            "order_item_id = '{$id}'"
        ]);

        echo json_encode($res_array);
        exit;
    }

    public function update_order_status()
    {

        $id = $this->input->post('id');
        $order_status = $this->input->post('order_status');

        $res_array = [
            'ok' => true,
            'msg' => '',
        ];

        $order_detail = $this->service_model->get_order_detail('row', [
            "order_item_id = '{$id}'"
        ]);

        if ($order_status == 'canceled') {
            try {
                $res = $this->order_service->cancel($order_detail['order_item_id']);
            } catch (Exception $e) {

                $res_array['ok'] = false;
                $res_array['msg'] = $e->getMessage();
                echo json_encode($res_array);
                exit;
            }
        } else if ($order_status == 'completed') {
            try {
                $res = $this->order_service->complete($id);
            } catch (Exception $e) {

                $res_array['ok'] = false;
                $res_array['msg'] = $e->getMessage();
                echo json_encode($res_array);
                exit;
            }
        } else {

            if ($order_status == 'shipped') {

                $tracking_number = $order_detail['tracking_number'];

                if (empty($tracking_number)) {

                    $res_array['ok'] = false;
                    $res_array['msg'] = '송장번호를 먼저 입력후 배송처리 해주세요.';
                    echo json_encode($res_array);
                    exit;
                }

                $target_phone = !empty($order_detail['buyer_phone']) ? $order_detail['buyer_phone'] : $order_detail['receiver_phone'];
                $buyer_name = $order_detail['buyer_name'] ?? $order_detail['receiver_name'];

                $product_row = $this->service_model->get_product('row', [
                    "id = '{$order_detail['product_id']}'"
                ]);

                $order_item = $this->service_model->get_order_item('row', [
                    "id = '{$order_detail['order_item_id']}'"
                ]);

                $bundle_items_cnt = $this->service_model->get_order_bundle_items('one', [
                    "order_item_id = '{$order_detail['order_item_id']}'"
                ]);

                $product_name = $product_row['name'] ?? '';
                $product_name = $bundle_items_cnt == 1 ?
                    $product_name
                    : $product_name = $product_name . '등 ' . ($bundle_items_cnt - 1) . '종';
                $order_number = $order_item['number'];

                $this->alarmtalk->send([
                    'phone' => str_replace('-', '', $target_phone),
                    'name' => $buyer_name,
                    'templateCode' => 'ppur_2025082716051732533947443', // 실제 템플릿 코드로 변경
                    'type' => 'SHIP',
                    'changeWord' => [
                        'var1' => $buyer_name,              // [*1*] → 이름
                        'var2' => $product_name,            // [*2*] → 상품명
                        'var3' => $order_number,            // [*3*] → 주문번호
                        'var4' => $tracking_number,         // [*4*] → 송장번호
                    ],
                ]);
            }

            $res = $this->service_model->update_order_item(DEBUG, [
                'status' => $order_status,
            ], [
                "id = '{$order_detail['order_item_id']}'"
            ]);
        }

        if (!$res) {
            $res_array['ok'] = false;
            $res_array['msg'] = '주문 상태 변경에 실패했습니다.';
        }

        echo json_encode($res_array);
        exit;
    }

    private function layout_config($layout_name = "layout/admin")
    {

        $this->layout->setLayout($layout_name);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'order',
        ];
    }
}
