<?php

ini_set('display_errors', 1);

require_once '../../lib/Point.php';

require_once '../../lib/Room.php';

require_once '../../lib/UserInfo.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
header('Content-Type: application/json; charset=utf-8');
$point = new Point();
$room = new Room();
$userInfo = new UserInfo();
$user['userInfo'] = $userInfo->checkLogin();
if (false === $user['userInfo']) {
    header('Error:Login failed.');
    http_response_code(403);

    exit;
}
$user['room'] = $room->getGameInfo($user['userInfo']['userID']);
if (false === $user['room']) {
    header('Error:The user is not in the room.');
    http_response_code(403);

    exit;
}
$playerNum = count($room->gameInfo($user['room']['gameID']));
$voter = $point->getVoter($user['room']['gameID']);
$result['playerNum'] = $playerNum - $voter;
echo json_encode($result);
http_response_code(200);
