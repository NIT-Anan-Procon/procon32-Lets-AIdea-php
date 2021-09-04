<?php

ini_set('display_errors', 1);

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Room.php';

require_once '../lib/UserInfo.php';

require_once '../lib/Picture.php';

require_once '../lib/Point.php';

require_once '../lib/Word.php';

$room = new Room();
$userInfo = new UserInfo();
$picture = new Picture();
$point = new Point();
$explanation = new Word();

if (false === $userInfo->CheckLogin()) {
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);

if (false === $gameInfo) {
    http_response_code(403);

    exit;
}

$gameID = $gameInfo['gameID'];
$roomID = (int) $gameInfo['roomID'];
$count = count($room->RoomInfo($roomID));
if (false !== $gameInfo) {
    if (1 === $count) {
        $room->LeaveRoom($roomID, $gameInfo['playerID']);
        $picture->deleteGameInfo($gameID);
        $point->deleteGameInfo($gameID);
        $explanation->DelWord($gameID);
        $room->updateGame($roomID);
    } elseif (1 === (int) $gameInfo['flag']) {
        $room->updateOwner($roomID);
        $room->LeaveRoom($roomID, $gameInfo['playerID']);
        $room->updateGame($roomID);
    } else {
        $room->LeaveRoom($roomID, $gameInfo['playerID']);
        $room->updateGame($roomID);
    }
    http_response_code(200);

    exit;
}

http_response_code(403);
