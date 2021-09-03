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
    http_response_code(403);

    exit;
}

if (filter_input(INPUT_POST, 'gamemode')) {
    $roomID = $room->CreateRoomID();
    $gameID = $room->GetGameID() + 1;
    $playerID = 1;
    $gamemode = $_POST['gamemode'];
    $room->AddRoom($gameID, $playerID, $userID, $roomID, 1, $gamemode);
    $result = $room->PlayerInfo($gameID, $playerID);
}

echo json_encode($result);
http_response_code(200);
