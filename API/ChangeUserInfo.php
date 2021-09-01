<?php

require_once '../Const.php';

require_once '../lib/UserInfo.php';

$userInfo = new userInfo();

//$userID
$name = $_POST['name'];
$password = $_POST['passowrd'];
$image = $_POST['image_icon'];
$result = [];
if(isset($name)){
    $result += ['name' => $userInfo->ChangeUserName($userID, $name)];
}
if(isset($password)){
    $result += ['password' => $userInfo->ChangePassword($userID, $password)];
}
if(isset($image)){
    $result += ['image_icon' => $userInfo->ChangeUserName($userID, $image)];
}

json_encode($result);