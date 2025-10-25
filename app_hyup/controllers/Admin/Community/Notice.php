<?php

class notice extends MY_Controller
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
            $notice_row = $this->service_model->get_community_notice('row', [
                "id = '{$id}'"
            ]);

            $view_data =  [
                'notice_row'             => $notice_row,
                'layout_data'           => $this->layout_config(),
            ];

            $this->layout->view('/Admin/Community/notice_detail_view', $view_data);
        } else {

            $notice_all = $this->service_model->get_community_notice('all', [1]);

            $view_data =  [
                'notice_all'             => $notice_all,
                'layout_data'           => $this->layout_config(),
            ];

            $this->layout->view('/Admin/Community/notice_view', $view_data);
        }
    }

    public function create()
    {
        $id = $this->input->get('id');

        if (!empty($id)) {
            $notice_row = $this->service_model->get_community_notice('row', [
                "id = '{$id}'"
            ]);

            if (empty($notice_row)) {
                show_404();
            }
        } else {
            $notice_row = [];
        }
        $view_data =  [
            'notice_row'             => $notice_row,
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('/Admin/Community/notice_create_view', $view_data);
    }

    public function create_notice()
    {

        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $content = $this->input->post('content');

        $res_array = [
            'ok' => true,
            'msg' => 'notice가 등록되었습니다.',
        ];

        foreach ([1] as $proc) {

            if (empty($title)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '질문을 입력해주세요.';
                break;
            }

            if (empty($content)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '답변을 입력해주세요.';
                break;
            }

            $notice_data = [
                'title' => $title,
                'content' => $content,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            if (!empty($id)) {

                $notice_id = $this->service_model->update_community_notice(DEBUG, $notice_data, [
                    "id = '{$id}'"
                ]);

                if (!$notice_id) {
                    $res_array['ok'] = false;
                    $res_array['msg'] = 'notice 등록에 실패했습니다.';
                    break;
                }
            } else {

                $notice_id = $this->service_model->insert_community_notice(DEBUG, $notice_data);

                if (!$notice_id) {
                    $res_array['ok'] = false;
                    $res_array['msg'] = 'notice 등록에 실패했습니다.';
                    break;
                }
            }
        }

        echo json_encode($res_array);
    }

    public function delete_notice()
    {

        // 이벤트 ID를 POST로 받습니다.
        $id = $this->input->post('id');

        $res_array = [
            'ok' => true,
            'msg' => '공지가 삭제되었습니다.',
        ];
        if (empty($id)) {
            $res_array['ok'] = false;
            $res_array['msg'] = '공지 ID가 없습니다.';
        } else {
            // 공지를 삭제합니다.
            $delete_result = $this->service_model->delete_community_notice(DEBUG, [
                "id = '{$id}'"
            ]);

            if (!$delete_result) {
                $res_array['ok'] = false;
                $res_array['msg'] = '공지 삭제에 실패했습니다.';
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
            'sub_menu_code'    => 'notice',
        ];
    }
}
