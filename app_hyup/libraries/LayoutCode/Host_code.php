<?php

class host_code
{

    public function __construct()
    {
        $this->obj = &get_instance();

        $this->obj->load->model('template_code_model');
    }

    public function getUseData($aside = false)
    {
        @session_start();

        $midx = $_SESSION['midx'] ?? '';

        $krgame = $_REQUEST['krgame'] ?? '';
        $krserver = $_REQUEST['krserver'] ?? '';
        $type = $_REQUEST['type'] ?? 'sell';

        $search_game_txt = !empty($krgame) && !empty($krserver) ? "{$krgame} > $krserver" : "";

        $tb_site_info_row = $this->obj->template_code_model->get_tb_site_info('row', [1]);

        $login_member = !empty($midx) ? $this->obj->template_code_model->get_tb_member('row', [
            "idx = '{$midx}'"
        ]) : [];

        $tb_main_game_rank_all =  $this->obj->template_code_model->get_tb_main_game_rank('all', [1]);

        $datas =  [
            'tb_site_info_row' => $tb_site_info_row,
            'login_member' => $login_member,
            'tb_main_game_rank_all' => $tb_main_game_rank_all,
            'search_game_txt' => $search_game_txt,
            'type' => $type,
        ];

        if ($aside) {

            $판매중인거래 = !empty($midx) ? $this->obj->template_code_model->get_tb_item_order_OR('one', [
                "(gubun = 1 AND midx = {$midx} AND status IN (1,2,3,5))",
                "(gubun = 2 AND buy_midx = {$midx} AND status IN (1,2,3,5))",
            ]) : [];

            $구매중인거래 = !empty($midx) ? $this->obj->template_code_model->get_tb_item_order_OR('one', [
                "(gubun = 2 AND midx = {$midx} AND status IN (1,2,3,5))",
                "(gubun = 1 AND buy_midx = {$midx} AND status IN (1,2,3,5))",
            ]) : [];

            $datas['판매중인거래'] = $판매중인거래;
            $datas['구매중인거래'] = $구매중인거래;
        }

        return $datas;
    }
}
