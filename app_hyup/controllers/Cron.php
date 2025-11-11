<?php

class cron extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            'barobill'
        ]);

        $this->load->model('/Page/service_model');
    }

    /**
     * ^ ----------- 홈택스 API (새벽 1시) -----------
     * * 매입/매출 내역 크롤링
     */
    public function test()
    {
        $today = date('Ymd');
        $res = $this->barobill->매입세금계산서기간조회($today, $today);
    }
}
