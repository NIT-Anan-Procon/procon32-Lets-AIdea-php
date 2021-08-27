<?php
header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../room_connect/room.php');
require_once('../userInfo/userInfo.php');

$room = new Room();
$userInfo = new userInfo();

if ($userInfo->CheckLogin() === false) {
    echo json_encode(array('state' => 'ログインしていません。'));
    http_response_code(403);
    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$playerID = $room->getGameInfo($userID)['playerID'];
$playerInfo = $room->PlayerInfo($playerID);
if ($playerInfo != false) {
    if ($playerInfo['flag'] == 1) {
        $room->DeleteRoom($playerInfo['gameID']);
    } else {
        $room->LeaveRoom($playerID);
    }
} else {
    $result = array('state' => 'ユーザーは部屋に入っていません。');
}

echo json_encode($result);
http_response_code(200);
