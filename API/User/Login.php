<?php

header('Access-Control-Allow-Origin:*');

require_once '../../lib/UserInfo.php';

use Firebase\JWT\JWT;

$userInfo = new UserInfo();

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

        header('Access-Control-Allow-Origin:http://localhost:3030/');
        header('Access-Control-Allow-Credentials:true');
        $options = [
            'expires' => time() + 3600,
            'path' => '/',
            'secure' => false,
            'httponly' => true,
        ];
        setcookie('token', $jwt, $options);
        http_response_code(200);
    } else {
        header('Error:The user name or password is incorrect.');
        http_response_code(401);
    }
} else {
    header('Error:The requested value is different from the specified format.');
    http_response_code(401);
}
