
<?php

function redirect_handler()
{
    /**
     * 
     */
    @session_start();

    // * Check User Login
    $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;

    if (empty($uid)) {

        $currentPath = $_SERVER['REQUEST_URI'];

        if (
            strpos($currentPath, '/login') === false
        ) {
            header('Location: /login');
        }
    }
}

?>