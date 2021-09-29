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

if (false !== $gameInfo) {
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
}
if (filter_input(INPUT_POST, 'roomID')) {
    $roomID = $_POST['roomID'];

    $playerInfo = $room->joinRoom($userID, $roomID);

    $user = $userInfo->getUserInfo($userID);
    $result = [
        'playerID' => $playerInfo['playerID'],
        'name' => $user['name'],
        'icon' => $user['icon'],
        'badge' => $user['badge'],
        'gamemode' => $playerInfo['gamemode'],
        'roomID' => $roomID,
    ];
    echo json_encode($result);
    http_response_code(200);

    exit;
}

header('Error: The requested value is different from the specified format.');
http_response_code(401);
