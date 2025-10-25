<?php
class Product_discount_service
{
    protected $obj;
    protected $loginUser = false;

    public function __construct()
    {
        $this->obj = &get_instance();
        $this->obj->load->library("ajax");
        $this->obj->load->library("/Service/user_service", null, "user_service");

        $this->obj->load->model("/Page/service_model");

        $this->loginUser = $this->obj->user_service->getLoginUser();
    }

    public function getDiscount($product_id, $product_price)
    {
        $res = 0;

        $agent = $this->loginUser['agent'] ?? '';

        if ($agent == 'STORE') {

            $product_discont = $this->obj->service_model->get_product_discount('row', [
                "product_id = '{$product_id}'",
            ]);

            $discount_price = $product_discont['price'] ?? 0;

            $res = (int)$product_price - (int)$discount_price;
        } else {

            $res = (int)$product_price;
        }

        return $res;
    }
}
