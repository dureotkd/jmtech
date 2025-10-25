<?php

class setting extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");
        $this->load->library("/Service/user_service");

        $this->load->model('/Page/service_model');
    }

    public function index()
    {
        $site_meta_row = $this->service_model->get_site_meta('row', [
            "id = 1"
        ]);

        $view_data =  [
            'layout_data'           => $this->layout_config(),
            'site_meta_row'         => $site_meta_row,
        ];

        $this->layout->view('/Admin/setting_view', $view_data);
    }

    public function save_site_meta()
    {
        $meta_title = $this->input->post('meta_title');
        $meta_description = $this->input->post('meta_description');
        $meta_keywords = $this->input->post('meta_keywords');
        $account = $this->input->post('account');
        $head_point = $this->input->post('head_point');
        $agent_point = $this->input->post('agent_point');
        $store_point = $this->input->post('store_point');

        $res_array = [
            'ok' => true,
            'message' => '사이트 메타 정보가 성공적으로 저장되었습니다.',
        ];

        foreach ([1] as $proc) {

            // 사이트 메타 정보 저장 로직
            $this->service_model->update_site_meta(DEBUG, [
                'meta_title' => $meta_title,
                'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords,
                'account' => $account,
                'head_point' => $head_point,
                'agent_point' => $agent_point,
                'store_point' => $store_point,
            ], [
                "id = 1"
            ]);
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
            'sub_menu_code'    => 'setting',
        ];
    }
}
