<?php
class smartro extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");
        $this->load->library("smartro");
        $this->load->library("member");
        $this->load->library("/Service/order");
        $this->load->library("/Service/user");
        $this->load->library("/Service/non_user");

        $this->load->model('/Page/service_model');
    }

    /**
     * 결제 승인
     * 승인된 결제 정보를 반환합니다.
     */
    public function pay_callback()
    {
        $res_array = [
            'ok' => true,
            'msg' => '결제 승인 정보가 정상적으로 처리되었습니다.',
        ];

        // * ----------- 결제정보 -----------

        $Tid = $_REQUEST['Tid'] ?? null;
        $TrAuthKey = $_REQUEST['TrAuthKey'] ?? null;

        // * ----------- 제품정보 -----------

        $product_id = $_REQUEST['product_id'] ?? null;
        $option_id = $_REQUEST['option_id'] ?? null;
        $quantity = $_REQUEST['quantity'] ?? 0;
        $price = $_REQUEST['price'] ?? 0;
        $Amt = $_REQUEST['Amt'] ?? 0;

        // * ----------- 배송정보 -----------

        $buyer_name = $_REQUEST['buyer_name'] ?? null;
        $buyer_phone = $_REQUEST['buyer_phone'] ?? null;
        $receiver_name = $_REQUEST['receiver_name'] ?? null;
        $receiver_phone = $_REQUEST['receiver_phone'] ?? null;
        $zipcode = $_REQUEST['zipcode'] ?? null;
        $address = $_REQUEST['address'] ?? null;
        $address_detail = $_REQUEST['address_detail'] ?? null;
        $memo = $_REQUEST['memo'] ?? null;

        if (empty($Tid) || empty($TrAuthKey)) {

            $this->layout->set_error("결제 승인 정보가 올바르지 않습니다.");
            $this->layout->view('error');
            return;
        }

        $payment_log_id = $this->service_model->insert_payment_log(DEBUG, $_REQUEST);

        foreach ([1] as $proc) {

            $response = $this->smartro->approvePay($Tid, $TrAuthKey);

            if ($response['status'] !== 'SUCCESS' || $response['messageCode'] !== '0000') {
                $res_array['ok'] = false;
                $res_array['msg'] = "결제 승인 정보 처리에 실패하였습니다. 오류: " . $response['message'];
                break;
            }

            $login_user = $this->user->getLoginUser();

            try {

                $common_payloads = [
                    // * ----------- 제품정보 -----------
                    'product_id' => $product_id,
                    'option_id' => $option_id,
                    'price' => $price,
                    'quantity' => $quantity,
                    'amount' => $Amt,

                    // * ----------- 배송정보 -----------
                    'buyer_name' => $buyer_name,
                    'buyer_phone' => $buyer_phone,
                    'receiver_name' => $receiver_name,
                    'receiver_phone' => $receiver_phone,
                    'zipcode' => $zipcode,
                    'address' => $address,
                    'address_detail' => $address_detail,
                    'memo' => $memo,

                    // * ----------- 결제정보 -----------
                    'Tid' => $Tid, // 결제 ID
                    'payment_log_id' => $payment_log_id, // 결제 로그 ID
                ];

                if (!empty($login_user)) {

                    # 로그인 사용자 주문
                    $this->order->create($common_payloads);
                } else {

                    # 비회원 주문
                    $this->non_user->order($common_payloads);
                }
            } catch (Exception $e) {

                $res_array['ok'] = false;
                $res_array['msg'] = $e->getMessage();
                break;
            }
        }

        $this->service_model->update_payment_log(DEBUG, [
            'ok' => $res_array['ok'],
            'msg' => $res_array['msg'],
        ], [
            "id = '{$payment_log_id}'"
        ]);
    }

    /**
     * 결제 중단
     * 결제 중단된 정보를 반환합니다.
     */
    public function stop_callback()
    {

        printr($_REQUEST);
    }


    function curl($url, $post_data, &$http_status, &$header = null)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        // post_data
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HEADER, false);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        $body = null;
        // error
        if (!$response) {
            $body = curl_error($ch);
            // HostNotFound, No route to Host, etc  Network related error
            $http_status = -1;
        } else {
            //parsing http status code
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (!is_null($header)) {
                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

                $header = substr($response, 0, $header_size);
                $body = substr($response, $header_size);
            } else {
                $body = $response;
            }
        }
        curl_close($ch);
        return $body;
    }
}
