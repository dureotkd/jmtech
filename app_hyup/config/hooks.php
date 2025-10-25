<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'][] = [
    'function' => 'redirect_handler',
    'filename' => 'Auth_hook.php',
    'filepath' => 'hooks'
];

$hook['post_controller_constructor'][] = [
    'function' => 'develope_handler',
    'filename' => 'Developer_hook.php',
    'filepath' => 'hooks'
];

$hook['post_controller_constructor'][] = [
    'function' => 'setup',
    'filename' => 'Constants_hook.php',
    'filepath' => 'hooks'
];

// $hook['post_controller_constructor'][] = [
//     'function' => 'proc',
//     'filename' => 'Ip_check_hook.php',
//     'filepath' => 'hooks'
// ];
