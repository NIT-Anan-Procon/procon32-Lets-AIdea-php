<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../room_connect/room.php';

require_once '../userInfo/userInfo.php';

$room = new Room();
$userInfo = new userInfo();

if (false === $userInfo->CheckLogin()) {
    echo json_encode(['state' => 'ログインしていません。']);
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$gameID = $room->getGameInfo($userID)['gameID'];
if ($gameID) {
    $room->DeleteRoom($gameID);
    $result = ['state' => true];
} else {
    $result = ['state' => 'ユーザーは部屋に入っていません。'];
}

echo json_encode($result);
http_response_code(200);
