<?php

class event extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
            "file"
        ]);

        $this->load->model('/Page/service_model');
    }

    public function index()
    {
        $id = $this->input->get('id');


        if (!empty($id)) {
            $event_row = $this->service_model->get_community_event('row', [
                "id = '{$id}'"
            ]);

            $view_data =  [
                'event_row'             => $event_row,
                'layout_data'           => $this->layout_config(),
            ];

            $this->layout->view('/Admin/Community/event_detail_view', $view_data);
        } else {

            $event_all = $this->service_model->get_community_event('all', [1]);

            $view_data =  [
                'event_all'             => $event_all,
                'layout_data'           => $this->layout_config(),
            ];

            $this->layout->view('/Admin/Community/event_view', $view_data);
        }
    }

    public function create()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('/Admin/Community/event_create_view', $view_data);
    }

    public function create_event()
    {

        $title = $this->input->post('title');
        $content = $this->input->post('content');

        $res_array = [
            'ok' => true,
            'msg' => '이벤트가 등록되었습니다.',
        ];

        foreach ([1] as $proc) {

            if (empty($title)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '제목을 입력해주세요.';
                break;
            }

            if (empty($content)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '내용을 입력해주세요.';
                break;
            }

            $event_data = [
                'title' => $title,
                'content' => $content,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            if ($_FILES['main_image']['error'] == UPLOAD_ERR_OK) {

                $main_image_res = $this->file->upload('main_image', '/assets/app_hyup/uploads/event', 5, ['jpg', 'jpeg', 'png', 'gif']);

                if ($main_image_res['status'] == 'success') {

                    $event_data['image_url'] = $main_image_res['fileSrc'];
                }
            }

            $event_id = $this->service_model->insert_community_event(DEBUG, $event_data);

            if (!$event_id) {
                $res_array['ok'] = false;
                $res_array['msg'] = '이벤트 등록에 실패했습니다.';
                break;
            }
        }

        echo json_encode($res_array);
    }

    public function delete_event()
    {

        // 이벤트 ID를 POST로 받습니다.
        $id = $this->input->post('id');
        $res_array = [
            'ok' => true,
            'msg' => '이벤트가 삭제되었습니다.',
        ];
        if (empty($id)) {
            $res_array['ok'] = false;
            $res_array['msg'] = '이벤트 ID가 없습니다.';
        } else {
            // 이벤트를 삭제합니다.
            $delete_result = $this->service_model->delete_community_event(DEBUG, [
                "id = '{$id}'"
            ]);

            if (!$delete_result) {
                $res_array['ok'] = false;
                $res_array['msg'] = '이벤트 삭제에 실패했습니다.';
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
            'sub_menu_code'    => 'event',
        ];
    }
}
