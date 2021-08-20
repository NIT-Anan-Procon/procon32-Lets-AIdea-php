<?php

require __DIR__ . '/const.php';
require __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

if(!empty($_COOKIE['token'])) {
    $jwt = $_COOKIE['token'];
    $JWT = JWT::decode($jwt, JWT_KEY, array('HS256'));
    print_r($JWT);
    $decode_array = (array)$JWT;
    var_dump($decode_array);
    setcookie('token', '', (time() + -3600), '/');
}

