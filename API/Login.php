<?php

require_once '../Const.php';

require_once '../vendor/autoload.php';

require_once '../lib/UserInfo.php';

use Firebase\JWT\JWT;

$userInfo = new userInfo();

if (filter_input(INPUT_POST, 'name') && filter_input(INPUT_POST, 'password')) {
    $name = $_POST['name'];
    $password = $_POST['password'];

    $ok = $userInfo->userAuth($name, $password);
    if ($ok) {
        $payload = [
            'iss' => JWT_ISSUER,
            'exp' => time() + JWT_EXPIRES,
            'userID' => $ok['userID'],
        ];
        $jwt = JWT::encode($payload, JWT_KEY, JWT_ALG);

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        $options = [
            'expires' => time() + 1800,
            'path' => '/',
            'secure' => false,
            'httponly' => true,
        ];
        setcookie('token', $jwt, $options);
        http_response_code(200);
    } else {
        http_response_code(401);

        exit;
    }
} else {
    http_response_code(401);

    exit;
}
