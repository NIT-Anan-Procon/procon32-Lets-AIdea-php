<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../../lib/UserInfo.php';

$userInfo = new userInfo();

if (filter_input(INPUT_POST, 'name') && filter_input(INPUT_POST, 'password')) {
    $name = (string)$_POST['name'];
    $password = (string)$_POST['password'];
    $icon = $_POST['icon'];
    $result = $userInfo->AddUserInfo($name, $password, $icon);
    if (false === $result['name']) {
        header('Error:This name is already in use and cannot be used. You need to register with a different name.');
        http_response_code(401);
    } else if (false === $result['state']) {
        http_response_code(400);
    } else {
        http_response_code(200);
    }
} else {
    header('Error:The requested value is different from the specified format.');
    http_response_code(401);
}
