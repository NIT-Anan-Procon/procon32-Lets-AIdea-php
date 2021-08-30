<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Room.php';

require_once '../lib/UserInfo.php';

$room = new Room();
$userInfo = new userInfo();

if (false === $userInfo->CheckLogin()) {
    echo json_encode(['state' => 'ログインしていません。']);
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$playerID = $room->getGameInfo($userID)['playerID'];
$playerInfo = $room->PlayerInfo($playerID);
if (false !== $playerInfo) {
    if (1 === $playerInfo['flag']) {
        $room->DeleteRoom($playerInfo['gameID']);
    } else {
        $room->LeaveRoom($playerID);
    }
    $result = ['state' => true];
} else {
    $result = ['state' => 'ユーザーは部屋に入っていません。'];
}

echo json_encode($result);
http_response_code(200);
