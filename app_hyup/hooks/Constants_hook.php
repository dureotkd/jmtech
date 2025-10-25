
<?php

function setup()
{
    $CI                 = &get_instance();
    $CI->load->model('/Page/service_model');

    // Sentry\init([
    //     'dsn' => 'https://108b14cfb4723f849a4956deea3ee966@o4504126769987584.ingest.us.sentry.io/4509635951329280',
    // ]);

    $HEAD_USER = $CI->service_model->get_user('row', [
        "agent = 'HEAD'",
    ]);

    if (!empty($HEAD_USER)) {

        define('HEAD_USER_ID', $HEAD_USER['id']);
    } else {

        define('HEAD_USER_ID', 1);
    }

    // mobile인지 PC인지 구분
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false) {
        define('IS_MOBILE', true);
    } else {
        define('IS_MOBILE', false);
    }

    if (IS_MOBILE) {

        if (SMARTRO_DEBUG == true) {
            define('SMARTRO_SCRIPT', 'https://tmpay.smartropay.co.kr');
        } else {
            define('SMARTRO_SCRIPT', 'https://mpay.smartropay.co.kr');
        }
    } else {

        if (SMARTRO_DEBUG == true) {

            define('SMARTRO_SCRIPT', 'https://tpay.smartropay.co.kr');
        } else {
            define('SMARTRO_SCRIPT', 'https://pay.smartropay.co.kr');
        }
    }

    if (SMARTRO_DEBUG == true) {
        define('SMARTRO_API', 'https://tapproval.smartropay.co.kr/payment');
        define('SMARTRO_MODE', 'STG');
    } else {
        define('SMARTRO_API', 'https://approval.smartropay.co.kr/payment');
        define('SMARTRO_MODE', 'REAL');
    }
}

?>