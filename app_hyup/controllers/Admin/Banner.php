<?php

class banner extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
            "file",
            "/Service/user_service",
        ]);
        $this->load->model('/Page/service_model');
    }

    public function index()
    {
        $banner_row = $this->service_model->get_banner('row', [
            "id = 1"
        ]);

        $view_data =  [
            'layout_data'           => $this->layout_config(),
            'banner_row'         => $banner_row,
        ];

        $this->layout->view('/Admin/banner_view', $view_data);
    }

    public function remove_image()
    {
        $imageId = $this->input->post('imageId');

        $res_array = [
            'ok' => true,
            'message' => '삭제되었습니다',
        ];

        $banner_row = $this->service_model->get_banner('row', [
            "id = 1"
        ]);

        $banner_images = $banner_row['image_url'] ?? '';
        $banner_images = explode(',', $banner_images);
        if (isset($banner_images[$imageId - 1])) {
            unset($banner_images[$imageId - 1]);
        }
        $banner_images = array_values($banner_images); // Re-index the array
        $banner_images = join(',', $banner_images);
        $update_data = [
            'image_url' => $banner_images,
        ];

        try {
            $res = $this->service_model->update_banner(DEBUG, $update_data, [
                "id = 1"
            ]);

            if (empty($res)) {
                $res_array['ok'] = false;
                $res_array['message'] = DB_ERR_MSG;
            }
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['message'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    public function save_banner()
    {

        $res_array = [
            'ok' => true,
            'message' => '배너가 저장되었습니다',
        ];

        foreach ([1] as $proc) {

            $update_data = [
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $origin_banner_row = $this->service_model->get_banner('row', [
                "id = 1"
            ]);


            try {

                if ($_FILES['banner_image']['error'][0] == UPLOAD_ERR_OK) {


                    $banner_images = $this->file->upload_multiple('banner_image', '/assets/app_hyup/uploads/banner', 5, ['jpg', 'jpeg', 'png', 'gif']);
                    $banner_image_urls = [];

                    if (!empty($banner_images)) {

                        foreach ($banner_images as $key => $file) {

                            if ($file['status'] == 'success') {

                                $banner_image_urls[] = $file['fileSrc'];
                            }
                        }
                    }

                    // 기존 배너 이미지 URL을 가져와서 새로운 이미지 URL과 합칩니다.
                    $existing_images = $origin_banner_row['image_url'] ?? '';
                    if (!empty($existing_images)) {
                        $existing_images = explode(',', $existing_images);
                        $banner_image_urls = array_merge($existing_images, $banner_image_urls);
                    }

                    $update_data['image_url'] = join(',', $banner_image_urls);
                }

                $res = $this->service_model->update_banner(DEBUG, $update_data, [
                    "id = 1"
                ]);

                if (empty($res)) {
                    $res_array['ok'] = false;
                    $res_array['msg'] = DB_ERR_MSG;
                }
            } catch (Exception $e) {
                $res_array['ok'] = false;
                $res_array['msg'] = $e->getMessage();
            }
        }

        // 성공 응답 반환
        echo json_encode($res_array);
    }

    private function layout_config()
    {

        $this->layout->setLayout("layout/admin");
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'banner',
        ];
    }
}
