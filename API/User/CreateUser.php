<?php

ini_set('display_errors', 1);
require_once '../../lib/UserInfo.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');

$userInfo = new userInfo();

if (filter_input(INPUT_POST, 'name') && filter_input(INPUT_POST, 'password')) {
    $name = (string) $_POST['name'];
    $password = (string) $_POST['password'];
    $icon = $_POST['icon'];
    $result = $userInfo->AddUserInfo($name, $password, $icon);
    if (false === $result['character']) {
        header('Error:Your name and password must be alphanumeric.');
        http_response_code(401);
    } elseif (false === $result['name']) {
        header('Error:This name is already in use and cannot be used. You need to register with a different name.');
        http_response_code(401);
    } elseif (false === $result['state']) {
        http_response_code(400);
    } else {
        http_response_code(200);
    }
} else {
    header('Error:The requested value is different from the specified format.');
    http_response_code(401);
}
