<?php

class product extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library([
            "layout",
            "file",
            "/Service/user_service",
            "/Service/product_service",
        ]);
        $this->load->model('/Page/service_model');
    }

    public function index()
    {

        $id = $this->input->get('id');

        if (!empty($id)) {

            $product_row = $this->product_service->get($id);

            $view_data =  [
                'product_row'          => $product_row,
                'layout_data'           => $this->layout_config(),
            ];

            $this->layout->view('/Admin/product_detail_view', $view_data);
        } else {

            $product_all = $this->product_service->all();

            $view_data =  [
                'product_all'          => $product_all,
                'layout_data'           => $this->layout_config(),
            ];

            $this->layout->view('/Admin/product_view', $view_data);
        }
    }

    public function create()
    {
        $id = $this->input->get('id');

        $product_row = $this->product_service->get($id);

        $view_data =  [
            'product_row'          => $product_row,
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('/Admin/product_create_view', $view_data);
    }

    public function create_product()
    {
        $id = $this->input->post('id');
        $product_name = $this->input->post('product_name');
        $price = $this->input->post('price');
        $discount_price = $this->input->post('discount_price');
        $description = $this->input->post('description');

        $res_array = [
            'ok' => true,
            'msg' => '등록되었습니다',
        ];

        foreach ([1] as $proc) {

            if (empty($product_name)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '상품명을 입력해주세요.';
                break;
            }

            if (empty($price)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '판매가를 입력해주세요.';
                break;
            }

            if (empty($discount_price)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '매장 판매가를 입력해주세요.';
                break;
            }

            if ($price < $discount_price) {

                $res_array['ok'] = false;
                $res_array['msg'] = '매장 판매가는 판매가보다 작을 수 없습니다.';
                break;
            }

            $insert_data = [
                'name' => $product_name,
                'price' => $price,
                'discount_price' => $discount_price,
                'description' => $description,
            ];

            if ($_FILES['main_image']['error'] == UPLOAD_ERR_OK) {

                $main_image_res = $this->file->upload('main_image', '/assets/app_hyup/uploads/products', 5, ['jpg', 'jpeg', 'png', 'gif']);

                if ($main_image_res['status'] == 'success') {
                    $insert_data['image_url'] = $main_image_res['fileSrc'];
                } else {
                    $res_array['ok'] = false;
                    $res_array['msg'] = $main_image_res['message'];
                    break;
                }
            }

            if ($_FILES['detail_image']['error'][0] == UPLOAD_ERR_OK || $_FILES['detail_image']['error'][1] == UPLOAD_ERR_OK || $_FILES['detail_image']['error'][2] == UPLOAD_ERR_OK) {

                $detail_images = $this->file->upload_multiple('detail_image', '/assets/app_hyup/uploads/products', 5, ['jpg', 'jpeg', 'png', 'gif']);
                $detail_image_urls = [];
                if (!empty($detail_images)) {

                    foreach ($detail_images as $key => $file) {

                        if ($file['status'] == 'success') {

                            $detail_image_urls[] = $file['fileSrc'];
                        }
                    }
                }

                $insert_data['detail_image_urls'] = join(',', $detail_image_urls);
            }

            if ($_FILES['detail_image2']['error'][0] == UPLOAD_ERR_OK) {

                $detail_image2_res = $this->file->upload_multiple('detail_image2', '/assets/app_hyup/uploads/products', 5, ['jpg', 'jpeg', 'png', 'gif']);

                $detail_image_urls2 = [];

                if (!empty($detail_image2_res)) {

                    foreach ($detail_image2_res as $key => $file) {

                        if ($file['status'] == 'success') {

                            $detail_image_urls2[] = $file['fileSrc'];
                        }
                    }
                }

                $insert_data['detail_image_urls2'] = join(',', $detail_image_urls2);
            }

            if (!empty($id)) {

                $update_product_res = $this->service_model->update_product(DEBUG, $insert_data, [
                    "id = '{$id}'"
                ]);

                if (empty($update_product_res)) {

                    $res_array['ok'] = false;
                    $res_array['msg'] = '상품 수정에 실패했습니다. 다시 시도해주세요.';
                    break;
                }
            } else {

                $insert_data['created_at'] = date('Y-m-d H:i:s');

                $insert_product_res = $this->service_model->insert_product(DEBUG, $insert_data);

                if (empty($insert_product_res)) {

                    $res_array['ok'] = false;
                    $res_array['msg'] = '상품 등록에 실패했습니다. 다시 시도해주세요.';
                    break;
                }
            }
        }

        echo json_encode($res_array);
        exit;
    }

    public function delete_product()
    {
        $id = $this->input->post('id');

        $res_array = [
            'ok' => true,
            'msg' => '삭제되었습니다',
        ];

        if (empty($id)) {
            $res_array['ok'] = false;
            $res_array['msg'] = '삭제할 상품을 선택해주세요.';
        } else {

            $delete_product_res = $this->service_model->delete_product(DEBUG, [
                "id = '{$id}'"
            ]);

            if (empty($delete_product_res)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '상품 삭제에 실패했습니다. 다시 시도해주세요.';
            }
        }

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
            'sub_menu_code'    => 'product',
        ];
    }
}
