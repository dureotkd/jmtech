<?php

class agreement extends MY_Controller
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
        $sec = $this->input->get('sec');

        $view_data =  [

            'layout_data'           => $this->layout_config(),
            'sec'                   => $sec,
        ];

        $this->layout->view('agreement_view', $view_data);
    }


    private function layout_config()
    {

        $this->layout->setLayout("layout/blank");
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'banner',
        ];
    }
}
