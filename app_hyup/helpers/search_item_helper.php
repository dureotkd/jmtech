<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_search_item_v2')) {

    function get_search_item_v2($params)
    {

        $ori_array         = $params['vo'];
        $add_array         = !empty($params['add']) ? $params['add']  : [];
        $select_code    = $params['select'];
        $select_tag     = $params['tag'];
        $stat_cnt         = !empty($params['stat_cnt']) ? $params['stat_cnt'] : array();

        $search_item        = array();

        $select_tag_array    = array(
            "s"            => "selected",
            "selected"    => "selected",
            "c"            => "checked",
            "checked"    => "checked",
            "active"    => "active",
            "a"            => "active",
            "on"        => "on",
        );

        // 전체 키 정의
        $all_array            = array(
            "all"    => "chk_part_all",
        );

        if (!empty($add_array)) {

            $ori_array = $add_array + $ori_array;
        }

        /**
         * 다중과 단일의 선택값 비교의 단일화를 위해 배열이 아닌 경우 배열화
         */
        $select_code    = is_array($select_code) ? $select_code : array($select_code);

        if (!empty($ori_array)) {

            $select_tag_val    = !empty($select_tag_array[$select_tag]) ? $select_tag_array[$select_tag] : null;

            foreach ($ori_array as $code => $val) {

                $chk_class            = !empty($all_array[$code]) ? $all_array[$code] : "chk_part";

                /**
                 * 전체는 all 등의 명확한 KEY로 정의함을 권장
                 */
                if (empty($code)) {

                    $chk_class    = "chk_part_all";
                }

                $return_code    = $code;
                if (!empty($key_code) && array_key_exists($key_code, $val)) {
                    $return_code    = $val[$key_code];
                }

                $return_name    = $val;
                if (!empty($value_code) && array_key_exists($value_code, $val)) {
                    $return_name    = $val[$value_code];
                }

                $select    = (in_array($return_code, $select_code) ? " {$select_tag_val}" : '');

                $cnt     = !empty($stat_cnt[$code]) ? $stat_cnt[$code] : 0;

                $tmp_item    = array(
                    "name"        => $return_name,
                    "select"    => $select,
                    "chk_class"    => $chk_class,
                    "cnt"        => $cnt,
                );

                $search_item[$return_code]    = $tmp_item;
            }
        }

        return $search_item;
    }
}
