<?php

ini_set('display_errors', 1);

require_once '../../lib/Room.php';

require_once '../../lib/UserInfo.php';

require_once '../../lib/Picture.php';

require_once '../../lib/Point.php';

require_once '../../lib/Word.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
header('Content-Type: application/json; charset=utf-8');
$room = new Room();
$userInfo = new UserInfo();
$picture = new Picture();
$point = new Point();
$explanation = new Word();

if (false === $userInfo->checkLogin()) {
    header('Error: Login failed.');
    http_response_code(403);

    exit;
}

$userID = $userInfo->checkLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);

if (false === $gameInfo) {
    header('Error: The user is not in the room.');
    http_response_code(403);

    exit;
}

$gameID = $gameInfo['gameID'];
$roomID = $gameInfo['roomID'];
$count = count($room->gameInfo($gameID));
if (1 === $count) {
    $room->leaveRoom($roomID, $gameInfo['playerID']);
    $picture->deleteGameInfo($gameID);
    $point->deleteGameInfo($gameID);
    $explanation->delWord($gameID);
    $room->updateGame($roomID);
} elseif (1 === (int) $gameInfo['flag']) {
    $room->updateOwner($roomID);
    $room->leaveRoom($roomID, $gameInfo['playerID']);
    $room->updateGame($roomID);
} else {
    $room->leaveRoom($roomID, $gameInfo['playerID']);
    $room->updateGame($roomID);
}
http_response_code(200);
