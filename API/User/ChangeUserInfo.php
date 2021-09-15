<?php

require_once '../../lib/UserInfo.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');

$userInfo = new UserInfo();

if (false === $userInfo->CheckLogin()) {
    header('Error:Login failed.');
    http_response_code(403);

    exit;
}
$userID = $userInfo->CheckLogin()['userID'];
$name = $_POST['name'];
$password = $_POST['password'];
$icon = $_POST['icon'];
if (isset($name)) {
    $result = $userInfo->ChangeUserName($userID, $name);
    if (false === $result['character']) {
        header('Error:Your name and password must be alphanumeric.');
        http_response_code(401);

        exit;
    }
    if (false === $result['name']) {
        header('Error:This name is already in use and cannot be used. You need to register with a different name.');
        http_response_code(401);

        exit;
    }
    if (false === $result['state']) {
        http_response_code(400);

        exit;
    }
}
if (isset($password)) {
    $result = $userInfo->ChangePassword($userID, $password);
    if (false === $result['character']) {
        header('Error:Your name and password must be alphanumeric.');
        http_response_code(401);

        exit;
    }
    if (false === $result['state']) {
        http_response_code(400);

        exit;
    }
}
if (isset($icon)) {
    $result = $userInfo->ChangeUserIcon($userID, $icon);
    if (false === $result) {
        http_response_code(400);

        exit;
    }
}
if (empty($result)) {
    header('Error:The requested value is different from the specified format.');
    http_response_code(401);

    exit;
}
http_response_code(200);
