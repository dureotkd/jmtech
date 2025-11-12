<?php

class iframe extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
        ]);
    }

    public function index()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('main_view', $view_data);
    }

    public function search_calendar()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config(),
        ];

        $this->layout->view('/iframe/search_calendar_view', $view_data);
    }

    private function layout_config()
    {

        $this->layout->setLayout("layout/blank");
        $this->layout->setCss([]);
        $this->layout->setScript([]);
        $this->layout->setHeader(false);
        $this->output->enable_profiler(false);
        return [
            'top_menu_code'    => 'sales',
            'sub_menu_code'    => 'estimate',
        ];
    }
}
