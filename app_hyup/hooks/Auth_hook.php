
<?php

function redirect_handler()
{
    /**
     * 
     */
    @session_start();

    // * Check User Login
    $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;

    if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '::1') {
        // 로컬호스트인 경우 세션 강제 설정 (개발용)
        $uid = 1;
        $_SESSION['uid'] = $uid;
    }

    if (empty($uid)) {

        $currentPath = $_SERVER['REQUEST_URI'];

        if (
            strpos($currentPath, '/login') === false
            && strpos($currentPath, '/api') === false
        ) {
            header('Location: /login');
        }
    }
}

?>