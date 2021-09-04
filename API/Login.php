<?php

ini_set('display_errors', 1);

require_once '../Const.php';

require_once '../vendor/autoload.php';

require_once '../lib/UserInfo.php';

use Firebase\JWT\JWT;

$userInfo = new UserInfo();

if (filter_input(INPUT_POST, 'username') && filter_input(INPUT_POST, 'password')) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $ok = $userInfo->userAuth($username, $password);
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
            'expires' => time() + 3600,
            'path' => '/',
            'secure' => false,
            'httponly' => true,
        ];
        setcookie('token', $jwt, $options);
        http_response_code(200);
    } else {
        header('Error: The user name or password is incorrect.');
        http_response_code(403);

        exit;
    }
} else {
    header('Error: The requested value is different from the specified format.');
    http_response_code(401);

    exit;
}
