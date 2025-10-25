<?php
class product_service
{
    protected $obj;

    public function __construct()
    {
        $this->obj = &get_instance();
        $this->obj->load->library("ajax");

        $this->obj->load->library([
            "/Service/user_service",
            "/Service/product_discount_service"
        ]);

        $this->obj->load->model("/Page/service_model");
    }

    # 상품 단건 조회
    public function get($product_id)
    {

        $product = $this->obj->service_model->get_product('row', [
            "id = '{$product_id}'"
        ]);

        if (!empty($product)) {

            $login_user = $this->obj->user_service->getLoginUser();
            $agent = $login_user['agent'] ?? '';

            // & 관리자 보는 용도뿐
            $product['only_admin_discount_price'] = $product['discount_price'];

            if ($agent == 'STORE' || $agent == 'CUSTOMER') {

                $product['price'] = !empty($product['discount_price']) ? $product['discount_price'] : $product['price'];
            } else {

                // discount_price 키값 제거

                if (isset($product['discount_price'])) {
                    unset($product['discount_price']);
                }
            }

            $image_url = $product['image_url'] ?? '';
            $detail_image_urls = !empty($product['detail_image_urls']) ? explode(',', $product['detail_image_urls']) : [];
            $detail_image_urls2 = !empty($product['detail_image_urls2']) ? explode(',', $product['detail_image_urls2']) : [];

            $product['image_array'] = array_merge([$image_url], $detail_image_urls);
            $product['detail_image_urls2'] = $detail_image_urls2;
        }

        return $product;
    }

    # 전체 상품 목록 조회
    public function all($limit = 10)
    {

        $products = $this->obj->service_model->get_product('all', [
            1
        ], $limit);

        $data = [];

        if (!empty($products)) {

            $login_user = $this->obj->user_service->getLoginUser();
            $agent = $login_user['agent'] ?? '';

            foreach ($products as $product) {

                // & 관리자 보는 용도뿐
                $product['only_admin_discount_price'] = $product['discount_price'];

                if ($agent == 'STORE' || $agent == 'CUSTOMER') {

                    $product['price'] = !empty($product['discount_price']) ? $product['discount_price'] : $product['price'];
                } else {

                    // discount_price 키값 제거

                    if (isset($product['discount_price'])) {
                        unset($product['discount_price']);
                    }
                }

                $data[] = $product;
            }
        }

        return $data;
    }

    # 조건 검색 조회 (카테고리, 키워드 등)
    public function search($filters = []) {}

    # 상품에 대한 리뷰 목록 조회
    public function get_reviews($product_id)
    {

        $reviews = $this->obj->service_model->get_review('all', [
            "product_id = '{$product_id}'"
        ]);

        return $reviews;
    }

    public function get_photo_reviews($product_id)
    {

        $reviews = $this->obj->service_model->get_review('all', [
            "product_id = '{$product_id}'",
            "image_urls != ''"
        ]);

        $images_urls = [];

        if (!empty($reviews)) {
            foreach ($reviews as $review) {

                $photos = !empty($review['image_urls']) ? explode(',', $review['image_urls']) : [];

                foreach ($photos as $photo) {

                    $images_urls[] = $photo;
                }
            }
        }

        return $images_urls;
    }

    # 상품 생성
    public function create($payloads) {}

    # 상품 수정
    public function update($product_id, $data) {}

    # 상품 삭제
    public function delete($product_id) {}
}
