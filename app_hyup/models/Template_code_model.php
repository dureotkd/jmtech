<?php

class Template_code_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_tb_site_info($type, $where = [1])
    {

        $res = array();

        $sql =
            sprintf(
                "SELECT 	
                    * 
			    FROM 	
                    gamemarket.tb_site_info
			    WHERE 	
                    %s",
                join(" AND ", $where)
            );

        $res = $this->excute($sql, $type, 'main');

        return $res;
    }

    public function get_tb_member($type, $where = [1])
    {

        $res = array();

        $sql =
            sprintf(
                "SELECT 	
                    * 
			    FROM 	
                    gamemarket.tb_member
			    WHERE 	
                    %s",
                join(" AND ", $where)
            );

        $res = $this->excute($sql, $type, 'main');

        return $res;
    }

    public function get_manager_row($member_seq)
    {

        $res = array();

        if (!empty($member_seq)) {

            $sql =
                "SELECT 	
                    * 
			    FROM 	
                    gamemarket.tb_manager
			    WHERE 	
                    idx = '{$member_seq}'";

            $res = $this->excute($sql, "row", 'main');
        }

        return $res;
    }

    public function get_tb_item_order_cnt($type, $where)
    {

        $sql =
            sprintf("SELECT 	
                    COUNT(*)
			    FROM 	
                    gamemarket.tb_item_order a
			    WHERE 	
                   %s", join(" AND ", $where));

        $res = $this->excute($sql, $type, 'main');

        return $res;
    }

    public function get_tb_item_order_OR($type, $where, $debug = false)
    {

        $sql =
            sprintf("SELECT 	
                    COUNT(*)
			    FROM 	
                    gamemarket.tb_item_order a
			    WHERE 	
                   %s", join(" OR ", $where));

        $res = $this->excute($sql, $type, 'main');

        return $res;
    }

    public function get_tb_main_game_rank($type, $where)
    {

        $sql =
            sprintf(
                "SELECT 	
                    *
			    FROM 	
                    gamemarket.tb_game a
			    WHERE 	
                   %s
                ORDER BY 
                    sort ASC LIMIT 10 ",
                join(" AND ", $where)
            );

        $res = $this->excute($sql, $type, 'main');

        return $res;
    }

    public function get_tb_item_order($type, $where)
    {
        $selet = $type == 'one' ? "COUNT(*)" : "*";

        $sql =
            sprintf(
                "SELECT 	
                    {$selet}
			    FROM 	
                    gamemarket.tb_item_order a
			    WHERE 	
                   %s",
                join(" AND ", $where)
            );

        $res = $this->excute($sql, $type, 'main');

        return $res;
    }

    public function get_tb_item_cnt($type, $where)
    {

        $sql =
            sprintf("SELECT 	
                    COUNT(*)
			    FROM 	
                    gamemarket.tb_item a
			    WHERE 	
                   %s", join(" AND ", $where));

        $res = $this->excute($sql, $type, 'main');

        return $res;
    }
}
