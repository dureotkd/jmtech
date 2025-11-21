<?php

class recipe_iframe extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('layout');
    }

    public function index()
    {

        $view_data =  [
            'layout_config' => $this->layout_config(),
        ];

        $this->layout->view('recipe_iframe_view', $view_data);
    }

    private function layout_config()
    {

        $this->layout->setLayout("layout/none");
        $this->layout->setCss([]);
        $this->layout->setScript([]);
        $this->layout->setHeader(false);
        $this->output->enable_profiler(false);
    }
}
