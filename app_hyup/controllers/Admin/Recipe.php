<?php

class recipe extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library([
            "layout",
            "file",
            "/Service/user_service",
            "/Service/recipe_service",
        ]);
        $this->load->model('/Page/service_model');
    }

    public function index()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config(),
        ];

        $id = $this->input->get('id');

        if (!empty($id)) {

            $recipe_row = $this->recipe_service->get($id);
            $view_data['recipe_row'] = $recipe_row;

            $this->layout->view('/Admin/recipe_detail_view', $view_data);
        } else {

            $recipe_all = $this->recipe_service->all();

            $view_data['recipe_all'] = $recipe_all;

            $this->layout->view('/Admin/recipe_view', $view_data);
        }
    }

    public function create()
    {
        $id = $this->input->get('id');

        $view_data =  [

            'layout_data'           => $this->layout_config(),
        ];


        if (!empty($id)) {

            $recipe_row = $this->recipe_service->get($id);

            $view_data['recipe_row'] = $recipe_row;
        }

        $this->layout->view('/Admin/recipe_create_view', $view_data);
    }

    public function create_recipe()
    {
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $content = $this->input->post('content');
        $cooking_time = $this->input->post('cooking_time');
        $level = $this->input->post('level');

        $res_array = [
            'ok' => true,
            'msg' => '등록되었습니다',
            'data' => null,
        ];

        foreach ([1] as $proc) {

            if (empty($title)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '레시피명을 입력해주세요.';
                break;
            }

            if (empty($content)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '설명을 입력해주세요.';
                break;
            }

            if (empty($level)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '레시피 난이도를 선택해주세요.';
                break;
            }


            if (empty($cooking_time)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '조리시간을 입력해주세요.';
                break;
            }


            $insert_data = [
                'title' => $title,
                'content' => $content,
                'cooking_time' => $cooking_time,
                'level' => $level,
            ];

            if ($_FILES['main_image']['error'] == UPLOAD_ERR_OK) {

                $main_image_res = $this->file->upload('main_image', '/assets/app_hyup/uploads/reciepe', 5, ['jpg', 'jpeg', 'png', 'gif']);

                if ($main_image_res['status'] == 'success') {
                    $insert_data['image_url'] = $main_image_res['fileSrc'];
                }
            }

            $insert_data['created_at'] = date('Y-m-d H:i:s');

            if (empty($id)) {

                $res = $this->service_model->insert_recipe(DEBUG, $insert_data);

                if (empty($res)) {

                    $res_array['ok'] = false;
                    $res_array['msg'] = '레시피 등록에 실패했습니다. 다시 시도해주세요.';
                    break;
                }
            } else {

                $res = $this->service_model->update_recipe(DEBUG, $insert_data, [
                    "id = '{$id}'"
                ]);

                if (empty($res)) {

                    $res_array['ok'] = false;
                    $res_array['msg'] = '레시피 수정에 실패했습니다. 다시 시도해주세요.';
                    break;
                }
            }

            $res_array['data'] = $res;
        }

        echo json_encode($res_array);
        exit;
    }

    public function delete_recipe()
    {
        $id = $this->input->post('id');

        $res_array = [
            'ok' => true,
            'msg' => '삭제되었습니다',
            'data' => null,
        ];

        if (empty($id)) {

            $res_array['ok'] = false;
            $res_array['msg'] = '레시피 ID가 없습니다.';
        } else {

            $res = $this->service_model->delete_recipe(DEBUG, [
                "id = '{$id}'"
            ]);

            if (empty($res)) {

                $res_array['ok'] = false;
                $res_array['msg'] = '레시피 삭제에 실패했습니다. 다시 시도해주세요.';
            } else {
                $res_array['data'] = $res;
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
            'sub_menu_code'    => 'recipe',
        ];
    }
}
