<?php

require __DIR__ . '/const.php';
require __DIR__ . '/vendor/autoload.php';
require_once('../userInfo/userInfo.php');

use \Firebase\JWT\JWT;

$userInfo = new userInfo();

if($_COOKIE['token']) {
    $token = $_COOKIE['token'];
    $jwt = JWT::decode($token, JWT_KEY, array('HS256'));
    $decode = (array)$jwt;
    $user = $userInfo->GetUserInfo($decode['userID']);
    var_dump($user);
}