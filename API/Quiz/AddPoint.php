<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/point.php';

require_once '../lib/Room.php';

require_once '../lib/UserInfo.php';

$point = new Point();
$room = new Room();
$userInfo = new UserInfo();
if (false === $userInfo->CheckLogin()) {
    header('Error:Login failed.');
    http_response_code(403);

    exit;
}
$userID = $userInfo->CheckLogin()['userID'];
if (false === $room->getGameInfo($userID)) {
    header('Error:The user is not in the room.');
    http_response_code(403);

    exit;
}
$user = $room->getGameInfo($userID);
if (filter_input(INPUT_POST, 'playerID')) {
    $playerID = (int) $_POST['playerID'];
    $result['ans'] = $point->AddPoint($user['gameID'], $playerID, 1, 0);
    $result['exp'] = $point->AddPoint($user['gameID'], $user['playerID'], 1, 1);
    if (false === $result['ans'] || false === $result['exp']) {
        http_response_code(400);
    } else {
        http_response_code(200);
    }
} else {
    header('Error:The requested value is different from the specified format.');
    http_response_code(401);
}
