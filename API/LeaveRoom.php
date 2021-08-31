<?php

ini_set('display_errors', 1);

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Room.php';

require_once '../lib/UserInfo.php';

$room = new Room();
$userInfo = new UserInfo();

if (false === $userInfo->CheckLogin()) {
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);
if (false !== $gameInfo) {
    if (1 == $gameInfo['flag']) {
        $room->DeleteRoom($gameInfo['gameID']);
    } else {
        $room->LeaveRoom($gameInfo['gameID'], $gameInfo['playerID']);
    }
    $result = ['state' => true];
    echo json_encode($result);
    http_response_code(200);
} else {
    http_response_code(403);

    exit;
}
