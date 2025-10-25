<?php
class member
{

    public function __construct()
    {

        $this->obj = &get_instance();
        $this->obj->load->model("/Page/service_model");
    }


    public function get_login_member()
    {

        @session_start();

        $mseq = $_SESSION['mseq'] ?? '';

        $tb_member_row = !empty($mseq) ? $this->obj->service_model->get_tb_user('row', [
            "seq = '{$mseq}'"
        ]) : [];

        return $tb_member_row;
    }
}
