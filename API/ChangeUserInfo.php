<?php

require_once '../Const.php';

require_once '../lib/UserInfo.php';

$userInfo = new userInfo();

//$userID
$name = $_POST['name'];
$password = $_POST['passowrd'];
$image = $_POST['image_icon'];
if (isset($name)) {
    $result = $userInfo->ChangeUserName($userID, $name);
    if(false === $result['name']){
        http_response_code(401);

        exit;
    }
    if(false === $result['state']){
        http_response_code(400);

        exit;
    }
    unset($result['name']);
}
if (isset($password)) {
    $result = $userInfo->ChangePassword($userID, $password);
    if(false === $result){
        http_response_code(400);

        exit;
    }
}
if (isset($image)) {
    $result = $userInfo->ChangeUserName($userID, $image);
    if(false === $result){
        http_response_code(400);

        exit;
    }
}
if (empty($result)) {
    http_response_code(401);

    exit;
}

echo json_encode($result);
http_response_code(200);