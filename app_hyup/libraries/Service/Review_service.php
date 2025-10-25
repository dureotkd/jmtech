<?php
class Review_service
{
    protected $obj;
    protected $loginUser = false;

    public function __construct()
    {
        $this->obj = &get_instance();
        $this->obj->load->library("ajax");
        $this->obj->load->model("service_model");
        $this->obj->load->library("user");

        $this->loginUser = $this->obj->user->getLoginUser();
    }

    # 리뷰 단건 조회
    public function get($id)
    {

        $review = $this->obj->service_model->get_review('row', [
            "id = '{$id}'"
        ]);

        return $review;
    }

    public function list($where = [], $limit = 10)
    {

        $reviews = $this->obj->service_model->get_review('all', $where);

        return $reviews;
    }

    # 리뷰 생성
    public function create($payloads)
    {
        if (empty($this->loginUser)) throw new Exception('로그인이 필요합니다.');

        $product_id = $payloads['product_id'] ?? '';
        $content = $payloads['content'] ?? '';
        $rating = $payloads['rating'] ?? 0;

        if (mb_strlen($content) < 20) {

            throw new Exception('리뷰 내용은 최소 20자 이상 입력해야 합니다.');
        }

        if (empty($rating)) {

            throw new Exception('리뷰 평점은 필수입니다.');
        }

        if (empty($product_id)) {

            throw new Exception('상품 ID가 필요합니다.');
        }

        $login_user_id = $this->loginUser['id'] ?? '';
        $masked_user_id = strlen($login_user_id) > 4
            ? substr($login_user_id, 0, 4) . '*****'
            : $login_user_id . '*****';


        // 리뷰 데이터 삽입
        $data = [
            'user_id' => $masked_user_id,
            'product_id' => $product_id,
            'content' => $content,
            'rating' => $rating,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->obj->service_model->insert_review($data);
    }

    # 리뷰 삭제
    public function delete()
    {

        if (empty($this->loginUser)) throw new Exception('로그인이 필요합니다.');
    }
}
