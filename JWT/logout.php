<?php

require __DIR__ . '/const.php';
require __DIR__ . '/vendor/autoload.php';

if(!empty($_COOKIE['token'])) {
    echo "ログアウト";
    $JWT = JWT::decode($jwt, JWT_KEY, ['HS256']);
    // var_dump($JWT);
    // echo json_encode($jwt);
    // $JWT = json_decode($jwt);
    setcookie('token', '', (time() + -3600), '/');
}

