<?php

require_once '../Const.php';

require_once '../lib/UserInfo.php';

$userInfo = new userInfo();

//$userID
if (filter_input(INPUT_POST, 'name') && filter_input(INPUT_POST, 'password')) {
    $name = $_POST['name'];
    $password = $_POST['passowrd'];
    $image = $_POST['image_icon'];
    $result = $userInfo->AddUserInfo($name, $password, $image);
    if(false === $result['name']){
        http_response_code(401);

        exit;
    }
    if(false === $result['state']){
        http_response_code(400);

        exit;
    }
    unset($result['name']);
    echo json_encode($result);
    http_response_code(200);
} else {
    http_response_code(401);

    exit;
}