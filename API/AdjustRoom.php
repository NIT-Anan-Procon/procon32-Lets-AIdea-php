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

if ($gameInfo === false) {
    http_response_code(403);
}

$gameID = $room->getGameInfo($userID)['gameID'];
$count = count($room->getRoomCount($gameID));
$num = 4 - $count;

for ($i = 0; $i < $num; ++$i) {
    $room->deleteRecord(4 - $i);
}

http_response_code(200);
