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

        $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : '';

        $user = !empty($uid) ? $this->obj->service_model->get_user('row', [
            "id" => $uid,
        ]) : [];

        return $user;
    }
}
