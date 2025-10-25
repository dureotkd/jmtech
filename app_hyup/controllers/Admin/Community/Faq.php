<?php

class faq extends MY_Controller
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
            $faq_row = $this->service_model->get_community_faq('row', [
                "id = '{$id}'"
            ]);

            $view_data =  [
                'faq_row'             => $faq_row,
                'layout_data'           => $this->layout_config(),
            ];

            $this->layout->view('/Admin/Community/faq_detail_view', $view_data);
        } else {

            $faq_all = $this->service_model->get_community_faq('all', [1]);

            $view_data =  [
                'faq_all'             => $faq_all,
                'layout_data'           => $this->layout_config(),
            ];

            $this->layout->view('/Admin/Community/faq_view', $view_data);
        }
    }

    public function create()
    {
        $id = $this->input->get('id');

        if (!empty($id)) {
            $faq_row = $this->service_model->get_community_faq('row', [
                "id = '{$id}'"
            ]);

            if (empty($faq_row)) {
                show_404();
            }
        } else {
            $faq_row = [];
        }
        $view_data =  [
            'faq_row'             => $faq_row,
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('/Admin/Community/faq_create_view', $view_data);
    }

    public function create_faq()
    {

        $id = $this->input->post('id');
        $question = $this->input->post('question');
        $content = $this->input->post('content');
        $category = $this->input->post('category');

        $res_array = [
            'ok' => true,
            'msg' => 'FAQ가 등록되었습니다.',
        ];

        foreach ([1] as $proc) {

            if (empty($question)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '질문을 입력해주세요.';
                break;
            }

            if (empty($category)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '카테고리를 선택해주세요.';
                break;
            }
            if (empty($content)) {
                $res_array['ok'] = false;
                $res_array['msg'] = '답변을 입력해주세요.';
                break;
            }

            $faq_data = [
                'question' => $question,
                'category' => $category,
                'answer' => $content,
                'created_at' => date('Y-m-d H:i:s'),
            ];


            if (!empty($id)) {

                $faq_id = $this->service_model->update_community_faq(DEBUG, $faq_data, [
                    "id = '{$id}'"
                ]);

                if (!$faq_id) {
                    $res_array['ok'] = false;
                    $res_array['msg'] = 'FAQ 등록에 실패했습니다.';
                    break;
                }
            } else {

                $faq_id = $this->service_model->insert_community_faq(DEBUG, $faq_data);

                if (!$faq_id) {
                    $res_array['ok'] = false;
                    $res_array['msg'] = 'FAQ 등록에 실패했습니다.';
                    break;
                }
            }
        }


        echo json_encode($res_array);
    }

    public function delete_faq()
    {

        // 이벤트 ID를 POST로 받습니다.
        $id = $this->input->post('id');
        $res_array = [
            'ok' => true,
            'msg' => 'FAQ가 삭제되었습니다.',
        ];
        if (empty($id)) {
            $res_array['ok'] = false;
            $res_array['msg'] = 'FAQ ID가 없습니다.';
        } else {
            // FAQ를 삭제합니다.
            $delete_result = $this->service_model->delete_community_faq(DEBUG, [
                "id = '{$id}'"
            ]);

            if (!$delete_result) {
                $res_array['ok'] = false;
                $res_array['msg'] = 'FAQ 삭제에 실패했습니다.';
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
            'sub_menu_code'    => 'faq',
        ];
    }
}
