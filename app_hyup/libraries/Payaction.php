<?php
class payaction
{
    // public $MID = PAYUP_DEBUG == true ? "standard_test" : "hihome";
    public $API_KEY = '6UPPII65A252';
    public $MALL_ID = '1753410601273x682566397822238700';
    public $API_URL = 'https://api.payaction.app';

    public function __construct()
    {
        $this->obj = &get_instance();

        $this->obj->client = new \GuzzleHttp\Client();

        $this->obj->load->model('page/service_model');
    }

    function order($payloads = [])
    {
        $order_number = $payloads['order_number'] ?? null;
        $order_amount = $payloads['order_amount'] ?? null;
        $billing_name = $payloads['billing_name'] ?? null;
        $orderer_name = $payloads['orderer_name'] ?? null;
        $orderer_phone_number = $payloads['orderer_phone_number'] ?? null;
        $orderer_email = $payloads['orderer_email'] ?? null;

        try {

            $data = [
                'order_number' => $order_number,
                'order_amount' => $order_amount,
                'order_date' => date('Y-m-d H:i:s'),
                'billing_name' => $billing_name,
                'orderer_name' => $orderer_name,
                'orderer_phone_number' => $orderer_phone_number,
                'orderer_email' => $orderer_email,
                'status' => 1,
            ];

            return $this->obj->service_model->insert_payaction_order(DEBUG, $data);
        } catch (\Exception $e) {

            throw new Error($e->getMessage());
        }
    }

    function cancel($order_id, $payment_log_id)
    {

        $this->obj->service_model->update_payaction_order(DEBUG, [
            'status' => 0,
            'canceled_at' => date('Y-m-d H:i:s'),
        ], [
            "id = '{$payment_log_id}'"
        ]);

        $this->obj->service_model->update_order_item(DEBUG, [
            'status' => 'canceled',
            'cancel_date' => date('Y-m-d H:i:s'),
        ], [
            "id = '{$order_id}'"
        ]);
    }
}
