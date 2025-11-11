
<?php

function redirect_handler()
{
    /**
     * 
     */

    @session_start();
    $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 1;

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