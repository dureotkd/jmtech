<?php

class autobank extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "aligo",
            "teamroom",
            "/Service/point_service",
            "alarmtalk"
        ]);

        $this->load->model('/Page/service_model');
    }

    public function slack()
    {

        // Slack에서 보내는 요청은 JSON 형식입니다
        header('Content-Type: application/json');

        // 요청 본문 받기
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $text = $data['event']['text'] ?? null;

        $text = str_replace('[Web발신]', '', $text);
        $lines = explode("\n", trim($text));

        $date = $lines[0] ?? '';

        if (strstr($date, '신한')) {

            $date = $lines[0] ?? '';
            $account = !empty($lines[1]) ? preg_replace('/[^0-9]/', '', str_replace(' ', '', $lines[1])) : '';
            $amount  = !empty($lines[2]) ? preg_replace('/[^0-9]/', '', str_replace(' ', '', $lines[2])) : '';
            $balance = !empty($lines[3]) ? preg_replace('/[^0-9]/', '', str_replace(' ', '', $lines[3])) : '';
            $name    = !empty($lines[4]) ? preg_replace('/\s+/', '', $lines[4]) : '';

            // date:신한08/07 13:10
            // 위에를 2025-08-07 13:10:00 으로 변환
            $date = date('Y-m-d H:i:s', strtotime(str_replace('신한', '', $date)));

            $this->auto_deposit_check_mosihealth([
                'date' => $date,
                'amount' => $amount,
                'name' => $name,
                'balance' => $balance,
                'account' => $account,
            ]);
        } else {

            $date = $lines[0] ?? '';
            $amount = !empty($lines[1]) ? preg_replace('/[^0-9]/', '', $lines[1]) : '';
            $balance = !empty($lines[2]) ? preg_replace('/[^0-9]/', '', $lines[2]) : '';
            $name = $lines[3] ?? '';
            $account = $lines[4] ?? '';
            $bank = $lines[5] ?? '';

            if ($bank == '기업') {

                $client = new \GuzzleHttp\Client();
                $response = $client->request('POST', "https://www.gamemarket.kr/api/autobank", [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'accept' => '*/*',
                    ],
                    'body' => json_encode([
                        'amount' => $amount,
                        'name' => $name,
                        'balance' => $balance,
                        'account' => $account,
                        'bank' => $bank,
                        'date' => $date,
                    ]),
                ]);
            }
        }
    }

    /**
     * & http://127.0.0.1/autobank/payaction
     * 
     * {
    "transaction_type": "deposited",
    "bank_account_id": "1747742998954x641239108830363600","1747742998954x641239108830363600"
    "bank_account_number": "18116590601015",
    "bank_code": "003",
    "amount": 100,
    "transaction_date": "2025-05-22T14:34:00+09:00", "2025-05-22T14:54:00+09:00"
    "transaction_name": "신성민",
    "balance": 141985253,
    "processing_date": "2025-05-22T14:34:57+09:00"
}
     */
    public function auto_deposit_check_mosihealth($payloads)
    {

        $res_array = [
            'ok' => true,
            'msg' => '정상처리되었습니다',
        ];

        $amount = $payloads['amount'];
        $transaction_name = $payloads['name'];
        $transaction_date = $payloads['date'];
        $bank_account_id = $payloads['account'];
        $balance = $payloads['balance'];

        $now_date = date("Y-m-d H:i:s");
        $process_date = date('Y-m-d H:i:s');

        $already_deposit = $this->service_model->get_payaction_log('row', [
            "transaction_type = 'deposited'",
            "amount = {$amount}",
            "transaction_name = '{$transaction_name}'",
            "transaction_date = '{$transaction_date}'",
            "balance = {$balance}",
            "ok = 1"
        ]);

        if (!empty($already_deposit)) {
            $this->teamroom->send('개발자', join("\n", [
                "이미 처리된 입금 정보입니다",
                "입금일시 : {$transaction_date}",
                "금액 : {$amount}",
                "잔액 : {$balance}",
                "이름 : {$transaction_name}",
                "처리일시 : {$process_date}",
            ]));
            return;
        }

        foreach ([1] as $proc) {

            $payaction_order = $this->service_model->get_payaction_order_custom('row', [
                "billing_name = '{$transaction_name}'", // 입금자명
                "order_amount = '{$amount}'", // 결제금액
                'status = 1',
            ], "ORDER BY created_at DESC");

            if (empty($payaction_order)) {
                $this->teamroom->send('개발자', join("\n", [
                    "주문 정보가 존재하지 않습니다",
                    "입금일시 : {$transaction_date}",
                    "금액 : {$amount}",
                    "잔액 : {$balance}",
                    "이름 : {$transaction_name}",
                    "처리일시 : {$process_date}",
                ]));
                $res_array['ok'] = false;
                $res_array['msg'] = '주문 정보가 존재하지 않습니다 (무통장입금)';
                break;
            }

            $order_number = $payaction_order['order_number'] ?? '';

            $order_item = $this->service_model->get_order_item('row', [
                "number = '{$order_number}'",
            ]);

            $this->teamroom->send('개발자', join("\n", [
                "[무통장입금]",
                "order_number : {$order_number}",
            ]));

            if (empty($order_item)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '주문 아이템 정보가 존재하지 않습니다';
                break;
            }

            // ^ 1. 주문 정보 업데이트 (결제완료)
            $update_res1 = $this->service_model->update_order_item(DEBUG, [
                'status' => 'paid',
                'paid_at' => $now_date,
                'payment_log_id' => $payaction_order['id'],
            ], [
                "number = '{$order_number}'"
            ]);

            if (empty($update_res1)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '주문 정보 업데이트에 실패했습니다';
                break;
            }

            // ^ 2. 결제 정보 업데이트 (결제완료)
            $update_res2 = $this->service_model->update_payaction_order(DEBUG, [
                'status' => 2, // 결제완료
                'paid_at' => $now_date,
            ], [
                "order_number = '{$order_number}'"
            ]);

            if (empty($update_res2)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '결제 정보 업데이트에 실패했습니다';
                break;
            }

            try {

                $this->point_service->buyRewardAdmin($order_item['id']);
            } catch (Exception $e) {
                $res_array['ok'] = false;
                $res_array['msg'] = '포인트 지급에 실패했습니다: ' . $e->getMessage();
                break;
            }
        }

        $this->service_model->insert_payaction_log(DEBUG, [
            'transaction_type' => 'deposited',
            'bank_account_id' => $bank_account_id,
            'amount' => $amount,
            'bank_code' => '003', // 신한은행
            'bank_account_number' => '140015517681', // 예시 계좌번호
            'transaction_date' => $transaction_date,
            'transaction_name' => $transaction_name,
            'balance' => $balance,
            'processing_date' => $process_date,
            'reg_date' => $now_date,
            'ok' => $res_array['ok'],
            'msg' => $res_array['msg'],
        ]);


        try {

            $bundle_items = $this->service_model->get_order_bundle_items('all', [
                "order_item_id = '{$order_item['id']}'",
            ]);

            $order_detail = $this->service_model->get_order_detail('row', [
                "order_item_id = '{$order_item['id']}'",
            ]);

            $product = $this->service_model->get_product('row', [
                "id = '{$order_detail['product_id']}'",
            ]);

            $buyer_name = $order_detail['buyer_name'] ?? '';
            $buyer_phone = $order_detail['buyer_phone'] ?? '';
            $receiver_phone = $order_detail['receiver_phone'] ?? '';

            $bundle_items_cnt = count($bundle_items);
            $product_name = $bundle_items_cnt == 1 ?
                $product['name']
                : $product['name'] = $product['name'] . '등 ' . ($bundle_items_cnt - 1) . '종';

            $target_phone = !empty($buyer_phone) ? $buyer_phone : $receiver_phone;

            $this->teamroom->send('개발자', join("\n", [
                "SMS 전송",
                "target_phone : {$target_phone}",
                "product_name : {$product_name}",
                "amount : " . number_format($amount),
                "buyer_name : {$buyer_name}",
                "order_number : {$order_number}",
            ]));

            $this->alarmtalk->send([
                'phone' => str_replace('-', '', $target_phone),
                'name' => $buyer_name,
                'templateCode' => 'ppur_2025081114295415150392024',
                'type' => 'PAY',
                'changeWord' => [
                    'var1' => $buyer_name,               // [*1*] → 이름
                    'var2' => $product_name,             // [*2*] → 상품명
                    'var3' => $order_number,             // [*3*] → 주문번호
                    'var4' => number_format($amount)     // [*4*] → 금액
                ],
            ]);
        } catch (Exception $e) {
            $this->teamroom->send('개발자', join("\n", [
                "SMS 발송에 실패했습니다",
                "order_number : {$order_number}",
                "error : " . $e->getMessage(),
            ]));
        }

        // 최종 응답 반환 (페이액션용)
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
        exit;
    }
}
