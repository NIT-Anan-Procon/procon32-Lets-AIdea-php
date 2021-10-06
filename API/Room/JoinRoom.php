<?php

ini_set('display_errors', 1);

require_once '../../lib/Room.php';

require_once '../../lib/UserInfo.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
header('Content-Type: application/json; charset=utf-8');
$room = new Room();
$userInfo = new UserInfo();

if (false === $userInfo->checkLogin()) {
    header('Error: Login failed.');
    http_response_code(403);

    exit;
}

$userID = $userInfo->checkLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);
$user = $userInfo->getUserInfo($userID);
$room->updateDemoStatus($gameInfo['gameID']);
$result = [
    'playerID' => $playerInfo['playerID'],
    'name' => $user['name'],
    'icon' => $user['icon'],
    'badge' => $user['badge'],
    'gamemode' => $playerInfo['gamemode'],
    'roomID' => $gameInfo['roomID'],
];
echo json_encode($result);
http_response_code(200);
