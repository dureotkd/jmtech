<?php
class Non_user_service
{
    protected $obj;

    public function __construct()
    {

        $this->obj = &get_instance();
        $this->obj->load->library("ajax");
        $this->obj->load->library("/Service/Order");

        $this->obj->load->model("/Page/service_model");
    }

    # PK 조회
    public function get($id)
    {
        $non_user = $this->obj->service_model->get_non_user('row', [
            "id = '{$id}'"
        ]);

        return $non_user;
    }

    public function show($id)
    {
        $non_user = $this->get($id);
        $non_user['order_item'] = !empty($non_user['order_item_id']) && $this->obj->service_model->get_order_item('row', [
            "id = '{$non_user['order_item_id']}'"
        ]);
        $non_user['product'] = !empty($non_user['order_item']['product_id']) && $this->obj->service_model->get_product('row', [
            "id = '{$non_user['order_item']['product_id']}'"
        ]);
        return $non_user;
    }

    /**
     * * 비회원 주문
     * @param array $payloads
     */
    public function order($payloads)
    {

        // * ----------- 제품정보 -----------
        $product_id             = $payloads['product_id'] ?? '';
        $quantity               = $payloads['quantity'] ?? 0;
        $price                  = $payloads['price'] ?? 0;
        $amount                 = $payloads['amount'] ?? 0;
        $option_id              = $payloads['option_id'] ?? '';

        // * ----------- 배송정보 -----------
        $buyer_name             = $payloads['buyer_name'] ?? '';
        $buyer_phone            = $payloads['buyer_phone'] ?? '';
        $receiver_name          = $payloads['receiver_name'] ?? '';
        $receiver_phone         = $payloads['receiver_phone'] ?? '';
        $zipcode                = $payloads['zipcode'] ?? '';
        $address                = $payloads['address'] ?? '';
        $address_detail         = $payloads['address_detail'] ?? '';
        $memo                   = $payloads['memo'] ?? '';

        try {

            $order_item_id = $this->obj->order->create([
                'non_user'   => true,

                // * ----------- 제품정보 -----------
                'product_id' => $product_id,
                'option_id'  => $option_id,
                'price'      => $price,
                'quantity'   => $quantity,
                'amount'     => $amount,

                // * ----------- 배송정보 -----------
                'buyer_name'        => $buyer_name,
                'buyer_phone'       => $buyer_phone,
                'receiver_name'     => $receiver_name,
                'receiver_phone'    => $receiver_phone,
                'zipcode'           => $zipcode,
                'address'           => $address,
                'address_detail'    => $address_detail,
                'memo'              => $memo,
            ]);

            $data = [
                'order_item_id'   => $order_item_id,
                'zipcode'         => $zipcode,
                'address'          => $address,
                'address_detail'   => $address_detail,
                'memo'            => $memo,
                'buyer_name'      => $buyer_name,
                'buyer_phone'     => $buyer_phone,
                'receiver_name'   => $receiver_name,
                'receiver_phone'  => $receiver_phone,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];

            $non_user_res = $this->obj->service_model->insert_non_user(DEBUG, $data);

            return $non_user_res;
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * 주문 상세 조회
     * @param int $id
     * @return array
     */
    public function showOrder($number, $phone)
    {

        $non_user = $this->obj->service_model->get_non_user('row', [
            "buyer_phone = '{$phone}'"
        ]);

        if (empty($non_user)) {
            throw new Exception('존재하지 않는 비회원입니다.');
        }

        $order_item_id = $non_user['order_item_id'] ?? null;

        $order_item = $this->obj->service_model->get_order_item('row', [
            "id = '{$order_item_id}'"
        ]);

        $db_number = $order_item['number'] ?? null;

        if ($db_number != $number) {

            throw new Exception('주문번호가 일치하지 않습니다.');
        }

        $non_user['order_item'] = $order_item;
        $non_user['order_detail'] = $this->obj->service_model->get_order_detail('all', [
            "order_item_id = '{$order_item_id}'"
        ]);

        return $non_user;
    }
}
