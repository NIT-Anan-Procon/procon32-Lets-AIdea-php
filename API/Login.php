<?php

require_once('../Const.php');
require_once('../vendor/autoload.php');
require_once('../lib/UserInfo.php');

use Firebase\JWT\JWT;

$userInfo = new userInfo();

if (filter_input(INPUT_POST, 'username') && filter_input(INPUT_POST, 'password')) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $ok = $userInfo->userAuth($username, $password);
    if ($ok) {
        $payload = array(
            'iss' => JWT_ISSUER,
            'exp' => time() + JWT_EXPIRES,
            'userID' => $ok['userID']
        );
        $jwt = JWT::encode($payload, JWT_KEY, JWT_ALG);

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        setcookie('token', $jwt, (time() + 3600), "/");
        echo json_encode(array('token' => $jwt, 'state' => 0), false, true); //tokenを返却
    } else {
        echo json_encode(array('state' => 4));
    }
} else {
    echo json_encode(array('state' => 1));
}

http_response_code(200);
