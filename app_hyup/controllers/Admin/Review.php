<?php

class review extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
            "/Service/product_service"
        ]);

        $this->load->model('/Page/service_model');
    }

    public function index()
    {
        $products = $this->product_service->all();
        $product_list = [];

        if (!empty($products)) {

            foreach ($products as $product) {

                $reviews = $this->service_model->get_review('all', [
                    "a.product_id = '{$product['id']}'"
                ]);

                $qnas = $this->service_model->get_product_qna('all', [
                    "a.product_id = '{$product['id']}'"
                ]);
                $product['reviews'] = $reviews;
                $product['qnas'] = $qnas;
                $product_list[] = $product;
            }
        }

        $view_data =  [
            'layout_data'           => $this->layout_config(),
            'product_list'         => $product_list,
        ];

        $this->layout->view('/Admin/review_view', $view_data);
    }

    public function delete_review()
    {
        $id = $this->input->post('id');

        $res_array = [
            'ok' => true,
            'msg' => '리뷰가 삭제되었습니다.'
        ];

        if (empty($id)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '리뷰 ID가 필요합니다.';
            echo json_encode($res_array);
            exit;
        }

        $this->service_model->delete_review(DEBUG, [
            "id = '{$id}'"
        ]);

        echo json_encode($res_array);
        exit;
    }

    public function delete_qna()
    {
        $id = $this->input->post('id');

        $res_array = [
            'ok' => true,
            'msg' => 'qna가 삭제되었습니다.'
        ];

        if (empty($id)) {

            $res_array['ok'] = false;
            $res_array['msg'] = 'ID가 필요합니다.';
            echo json_encode($res_array);
            exit;
        }

        $this->service_model->delete_product_qna(DEBUG, [
            "id = '{$id}'"
        ]);

        echo json_encode($res_array);
        exit;
    }

    public function update_answer()
    {
        $id = $this->input->post('id');
        $answer = $this->input->post('answer');

        $res_array = [
            'ok' => true,
            'msg' => '답변이 수정되었습니다.'
        ];

        if (empty($id)) {

            $res_array['ok'] = false;
            $res_array['msg'] = 'ID가 필요합니다.';
            echo json_encode($res_array);
            exit;
        }

        $this->service_model->update_product_qna(DEBUG, [
            'status' => '답변완료',
            'answer' => $answer
        ], [
            "id = '{$id}'"
        ]);

        echo json_encode($res_array);
        exit;
    }

    private function layout_config()
    {

        $this->layout->setLayout("layout/admin");
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'review',
        ];
    }
}
