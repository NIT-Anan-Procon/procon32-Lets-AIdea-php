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

if ($gameInfo !== false) {
    http_response_code(403);
    exit;
}

$roomID = $room->CreateRoomID();
$gameID = $room->GetGameID() + 1;
$playerID = 1;
$room->AddRoom($gameID, $playerID, $userID, $roomID, 1);
++$playerID;
for ($i = 0; $i < 3; ++$i) {
    $room->AddRoom($gameID, $playerID, null, $roomID, 0);
    ++$playerID;
}
$result = $room->OwnerInfo($roomID);

echo json_encode($result);
http_response_code(200);
