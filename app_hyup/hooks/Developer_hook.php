<?php

function develope_handler()
{

    $CI                 = &get_instance();

    $is_show   = true;

    $is_developer       = in_array($_SERVER['REMOTE_ADDR'], get_developer_ip()) ? true : false;

    // AJAX 요청 감지
    $is_ajax_request = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

    if (empty($is_developer)) {

        $is_show = false;
    }

    if (!empty($is_ajax_request)) {

        $is_show = false;
    }

    // $CI->load->model('MY_MODEL');
    // $CI->MY_MODEL->force_debug('exec');
    // $CI->MY_MODEL->force_debug('all');

    if (!empty($is_developer)) {

        // $CI->output->enable_profiler($is_show);
    }
}
