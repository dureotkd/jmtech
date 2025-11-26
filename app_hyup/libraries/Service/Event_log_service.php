<?php
class Event_log_service
{
    public function __construct()
    {

        $this->obj = &get_instance();

        $this->obj->load->library("/Service/user_service");

        $this->obj->load->model("/Page/service_model");
    }

    public function 견적서등록($target_id = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "견적서 등록",
            "event_action"      => "{$login_user['name']}님이 견적서를 등록했습니다.",
            "target_id"         => $target_id,
            "target_table"      => "estimate",
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 견적서수정($target_id = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "견적서 수정",
            "event_action"      => "{$login_user['name']}님이 견적서를 수정했습니다.",
            "target_id"         => $target_id,
            "target_table"      => "estimate",
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 견적서삭제($target_id = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "견적서 삭제",
            "event_action"      => "{$login_user['name']}님이 견적서를 삭제했습니다.",
            "target_id"         => $target_id,
            "target_table"      => "estimate",
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 수주서등록($target_id = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "수주서 등록",
            "event_action"      => "{$login_user['name']}님이 수주서를 등록했습니다.",
            "target_id"         => $target_id,
            "target_table"      => "estimate",
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 수주서수정($target_id = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "수주서 수정",
            "event_action"      => "{$login_user['name']}님이 수주서를 수정했습니다.",
            "target_id"         => $target_id,
            "target_table"      => "estimate",
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 수주서삭제($target_id = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "수주서 삭제",
            "event_action"      => "{$login_user['name']}님이 수주서를 삭제했습니다.",
            "target_id"         => $target_id,
            "target_table"      => "estimate",
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 매출거래명세표등록($target_id = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "매출거래명세표 등록",
            "event_action"      => "{$login_user['name']}님이 매출거래명세표를 등록했습니다.",
            "target_id"         => $target_id,
            "target_table"      => "statement",
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 매출거래명세표수정($target_id = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "매출거래명세표 수정",
            "event_action"      => "{$login_user['name']}님이 매출거래명세표를 수정했습니다.",
            "target_id"         => $target_id,
            "target_table"      => "statement",
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 매출거래명세표삭제($target_id = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "매출거래명세표 삭제",
            "event_action"      => "{$login_user['name']}님이 매출거래명세표를 삭제했습니다.",
            "target_id"         => $target_id,
            "target_table"      => "statement",
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 견적서상태변경($target_id = '', $status = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $STATUS = unserialize(ESTIMATE_STATUS);
        $status_label = $STATUS[$status] ?? $status;

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "견적서 상태변경",
            "event_action"      => "{$login_user['name']}님이 견적서 상태를 {$status_label}(으)로 변경했습니다.",
            "target_id"         => $target_id,
            "target_table"      => "estimate",
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 수주서상태변경($target_id = '', $status = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $STATUS = unserialize(SUJU_STATUS);
        $status_label = $STATUS[$status] ?? $status;

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "수주서 상태변경",
            "event_action"      => "{$login_user['name']}님이 수주서 상태를 {$status_label}(으)로 변경했습니다.",
            "target_id"         => $target_id,
            "target_table"      => "estimate",
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 세금계산서발행($target_id = '')
    {

        // $login_user = $this->obj->user_service->getLoginUser();

        // if (empty($login_user)) {
        //     throw new Error('로그인 정보가 존재하지 않습니다');
        // }

        // $STATUS = unserialize(SUJU_STATUS);
        // $status_label = $STATUS[$status] ?? $status;

        // $this->obj->service_model->insert_admin_event_log([
        //     "admin_id"          => $login_user['id'],
        //     "admin_name"        => $login_user['name'],
        //     "event_type"        => "수주서 상태변경",
        //     "event_action"      => "{$login_user['name']}님이 수주서 상태를 {$status_label}(으)로 변경했습니다.",
        //     "target_id"         => $target_id,
        //     "target_table"      => "estimate",
        //     'ip_address'        => $_SERVER['REMOTE_ADDR'],
        //     'created_at'        => date('Y-m-d H:i:s')
        // ]);
    }

    public function PDF출력($target_id = '', $target_table = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "PDF 출력",
            "event_action"      => "{$login_user['name']}님이 PDF를 출력했습니다.",
            "target_id"         => $target_id,
            "target_table"      => $target_table,
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 인쇄($target_id = '', $target_table = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "인쇄",
            "event_action"      => "{$login_user['name']}님이 인쇄했습니다.",
            "target_id"         => $target_id,
            "target_table"      => $target_table,
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    public function 엑셀출력($target_id = '', $target_table = '')
    {

        $login_user = $this->obj->user_service->getLoginUser();

        if (empty($login_user)) {
            throw new Error('로그인 정보가 존재하지 않습니다');
        }

        $this->obj->service_model->insert_admin_event_log([
            "admin_id"          => $login_user['id'],
            "admin_name"        => $login_user['name'],
            "event_type"        => "엑셀출력",
            "event_action"      => "{$login_user['name']}님이 엑셀을 출력했습니다.",
            "target_id"         => $target_id,
            "target_table"      => $target_table,
            'ip_address'        => $_SERVER['REMOTE_ADDR'],
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }
}
