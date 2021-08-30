<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Room.php';

require_once '../lib/UserInfo.php';

$room = new Room();
$userInfo = new userInfo();

if (false === $userInfo->CheckLogin()) {
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$gameID = $room->getGameInfo($userID)['gameID'];
if ($gameID) {
    $room->DeleteRoom($gameID);
    $result = ['state' => true];
} else {
    http_response_code(403);
    exit;
}

echo json_encode($result);
http_response_code(200);
