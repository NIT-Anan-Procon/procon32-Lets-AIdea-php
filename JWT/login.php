<?php

//他ファイルから実行された時に相対パスではエラーが起きるため
require __DIR__ . '/const.php';
require __DIR__ . '/vendor/autoload.php';
require_once('../userInfo/userInfo.php');

use \Firebase\JWT\JWT;

$userInfo = new userInfo();

//strtoupper・・・文字列を大文字にする
//POST通信であれば
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

        header('Content-Type: application/json');//レスポンスする形式はJSONファイル
        header('Access-Control-Allow-Origin: *'); //アクセスを許可するURL
        echo json_encode(array('token' => $jwt, 'state' => 0)); //tokenを返却
        setcookie('token', $jwt, (time() + 60), "/");
    } else {
        echo json_encode(array('state' => 4));
    }
} else {
    echo json_encode(array('state' => 1));
}

http_response_code(200);
