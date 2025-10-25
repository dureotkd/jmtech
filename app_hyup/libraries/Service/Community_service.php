<?php
class Community_service
{
    protected $obj;
    protected $loginUser = false;

    public function __construct()
    {
        $this->obj = &get_instance();
        $this->obj->load->library("ajax");

        $this->obj->load->model("/Page/service_model");
    }

    public function create_benfit($payloads)
    {

        $this->obj->service_model->insert_community_benefit($payloads);
    }

    public function create_faq($payloads)
    {
        $this->obj->service_model->insert_community_faq($payloads);
    }

    public function create_notice($payloads)
    {
        $this->obj->service_model->insert_community_notice($payloads);
    }

    public function create_event($payloads)
    {
        $this->obj->service_model->insert_community_event($payloads);
    }
}
