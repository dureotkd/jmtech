<?php

class benefit extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");

        $this->load->model('/Page/service_model');
    }

    public function index()
    {

        $view_data =  [

            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('/Admin/Community/benefit_view', $view_data);
    }


    private function layout_config()
    {

        $this->layout->setLayout("layout/admin");
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'benefit',
        ];
    }
}
