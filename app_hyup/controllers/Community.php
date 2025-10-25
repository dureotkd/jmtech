<?php

class community extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");

        $this->load->model('/Page/service_model');
    }

    # 커뮤니티
    public function notice()
    {
        $id = $this->input->get('id');

        $notices = $this->service_model->get_community_notice('all', [1]);

        $view_data =  [
            'layout_data'           => $this->layout_config(),
            'notices'               => $notices,
        ];

        if (!empty($id)) {

            $notice = $this->service_model->get_community_notice('row', [
                "id = '{$id}'"
            ]);

            $view_data['notice'] = $notice;

            $this->layout->view('Community/notice_detail_view', $view_data);
            return;
        }

        $this->layout->view('Community/notice_view', $view_data);
    }

    # 추출 가이드
    public function guide()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('Community/guide_view', $view_data);
    }

    private function layout_config($params = [])
    {
        $title = $params['title'] ?? '커뮤니티 | 제이엠테크';
        $description = $params['description'] ?? '제이엠테크 커뮤니티 페이지입니다. 다양한 커뮤니티 정보를 확인하고 참여할 수 있습니다.';

        $this->layout->setLayout("layout/template");
        $this->layout->setTitle($title);
        $this->layout->setDescription($description);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'banner',
        ];
    }
}
