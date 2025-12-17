<?php

function develope_handler()
{

    $CI = &get_instance();

    $is_show = true;

    $is_developer = in_array($_SERVER['REMOTE_ADDR'], get_developer_ip()) ? true : false;

    // AJAX 요청 감지
    $is_ajax_request = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

    if (!$is_developer) {
        $is_show = false;
    }

    // ajax면 is_show = false로
    if ($is_ajax_request) {
        $is_show = false;
    }

    if ($is_show) {
        // * output profiler on
        // $CI->output->enable_profiler(true);
    }


    // $_SESSION['uid'] = 1;
}
