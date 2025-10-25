<?php

define('PBKDF2_COMPAT_HASH_ALGORITHM', 'sha256');
define('PBKDF2_COMPAT_ITERATIONS', 12000);
define('PBKDF2_COMPAT_SALT_BYTES', 24);
define('PBKDF2_COMPAT_HASH_BYTES', 24);
define('STRING_ENCRYPT_FUNCTION', 'create_hash');

function create_hash($password, $force_compat = false)
{
    // Generate the salt.
    $salt = base64_encode(random_bytes(PBKDF2_COMPAT_SALT_BYTES));

    // Determine the best supported algorithm and iteration count.
    $algo = strtolower(PBKDF2_COMPAT_HASH_ALGORITHM);
    $iterations = PBKDF2_COMPAT_ITERATIONS;

    if ($force_compat || !in_array($algo, hash_algos())) {
        $algo = 'sha1';
        $iterations = round($iterations / 5);
    }

    // Generate PBKDF2 hash
    $pbkdf2 = pbkdf2_default($algo, $password, $salt, $iterations, PBKDF2_COMPAT_HASH_BYTES);

    return "$algo:$iterations:$salt:" . base64_encode($pbkdf2);
}

function slow_equals($a, $b)
{
    return hash_equals($a, $b);
}

function pbkdf2_default($algo, $password, $salt, $count, $key_length)
{
    if ($count <= 0 || $key_length <= 0) {
        trigger_error('PBKDF2 ERROR: Invalid parameters.', E_USER_ERROR);
    }

    if (!in_array($algo, hash_algos())) {
        trigger_error('PBKDF2 ERROR: Hash algorithm not supported.', E_USER_ERROR);
    }

    return hash_pbkdf2($algo, $password, $salt, $count, $key_length, true);
}

function validate_password($password, $hash)
{
    $params = explode(':', $hash);
    if (count($params) < 4) return false;

    $pbkdf2 = base64_decode($params[3]);
    $pbkdf2_check = pbkdf2_default($params[0], $password, $params[2], (int)$params[1], strlen($pbkdf2));

    return slow_equals($pbkdf2, $pbkdf2_check);
}

function check_password($pass, $hash)
{
    return validate_password($pass, $hash);
}

function login_password_check($pass, $hash)
{
    return check_password($pass, $hash);
}

// 문자열 암호화
function get_encrypt_string($str)
{
    $encrypt = call_user_func(STRING_ENCRYPT_FUNCTION, $str);

    return $encrypt;
}
