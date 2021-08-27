<?php
header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../room_connect/room.php');
require_once ('../userInfo/userInfo.php');

$room = new Room();
$userInfo = new userInfo();

if ($userInfo->CheckLogin() === false) {
    echo json_encode(array('State' => 'ログインしていません'));
    http_response_code(403);
    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$gameID = $room->getGameInfo($userID);
$room->DeleteRoom($gameID);

//echo json_encode($result);
http_response_code(200);
