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

// userIDでプレイヤーの情報を取得
$userID = $userInfo->CheckLogin()['userID'];
$userInfo = $room->getGameInfo($userID);

if (false === $userInfo) {
    http_response_code(403);

    exit;
}

$gameID = $userInfo['gameID'];
$playerID = $userInfo['playerID'];

// roomIDで部屋の情報を取得
$roomID = $userInfo['roomID'];
$roomInfo = $room->RoomInfo($roomID);

$count = count($roomInfo);
$flag = 0;
for ($i = 0; $i < $count; ++$i) {
    if ($gameID < $roomInfo[$i]['gameID']) {
        $gameID = $roomInfo[$i]['gameID'];
        $flag = 1;
    }
}

if (0 === $flag) {
    $gameID = $room->GetGameID() + 1;
    $room->joinAgain($gameID, $userID);
} else {
    $room->joinAgain($gameID, $userID);
}

$result = $room->PlayerInfo($gameID, $playerID);

echo json_encode($result);
http_response_code(200);
