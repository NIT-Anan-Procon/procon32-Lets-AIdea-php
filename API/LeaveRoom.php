<?php

ini_set('display_errors', 1);

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Room.php';

require_once '../lib/UserInfo.php';

require_once '../lib/Picture.php';

require_once '../lib/Point.php';

require_once '../lib/Explanation.php';

$room = new Room();
$userInfo = new UserInfo();
$picture = new Picture();
$point = new Point();
$explanation = new Explanation();

if (false === $userInfo->CheckLogin()) {
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);
$gameID = $gameInfo['gameID'];
$roomID = (int) $gameInfo['roomID'];
$count = count($room->GameInfo($gameID));
if (false !== $gameInfo) {
    if (0 === $count) {
        $room->LeaveRoom($gameID, $gameInfo['playerID']);
        $picture->deleteGameInfo($gameID);
        $point->deleteGameInfo($gameID);
        $explanation->deleteGameInfo($gameID);
    } elseif (1 === (int) $gameInfo['flag']) {
        $room->updateOwner($roomID);
        $room->LeaveRoom($gameInfo['gameID'], $gameInfo['playerID']);
    } else {
        $room->LeaveRoom($gameInfo['gameID'], $gameInfo['playerID']);
    }
    http_response_code(200);
} else {
    header('Error: a');
    http_response_code(403);

    exit;
}
