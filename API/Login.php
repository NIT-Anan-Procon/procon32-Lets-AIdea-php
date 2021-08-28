<?php

<<<<<<< HEAD
require_once('../Const.php');
require_once('../vendor/autoload.php');
require_once('../lib/UserInfo.php');
=======
require __DIR__.'../Const.php';

require __DIR__.'../vendor/autoload.php';

require_once '../lib/userInfo.php';
>>>>>>> main

use Firebase\JWT\JWT;

$userInfo = new userInfo();

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
<<<<<<< HEAD
        setcookie('token', $jwt, (time() + 3600), "/");
        echo json_encode(array('token' => $jwt, 'state' => 0), false, true); //tokenを返却
=======
        setcookie('token', $jwt, (time() + 1800), '/', false, true);
        echo json_encode(['token' => $jwt, 'state' => 0]); //tokenを返却
>>>>>>> main
    } else {
        echo json_encode(['state' => 4]);
    }
} else {
    echo json_encode(['state' => 1]);
}

http_response_code(200);
