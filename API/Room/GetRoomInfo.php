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
$game = $room->getGameInfo($userID);
$playerID = $game['playerID'];
$gamemode = $game['gamemode'];
$roomID = $game['roomID'];
$status = $game['status'];

if (false !== $game) {
    $gameID = $game['gameID'];
    $gameInfo = $room->gameInfo($gameID);
    $playerNum = count($gameInfo);
    $gamemode = $gameInfo[0]['gamemode'];
    $result = [
        'playerID' => $playerID,
        'gamemode' => $gamemode,
        'roomID' => $roomID,
        'status' => $status,
    ];
    for ($i = 0; $i < $playerNum; ++$i) {
        $userID = $gameInfo[$i]['userID'];
        $flag = $gameInfo[$i]['flag'];
        $user = $userInfo->getUserInfo($userID);
        $playerID = $gameInfo[$i]['playerID'];
        $result['player'][$i] = [
            'name' => $user['name'],
            'icon' => $user['icon'],
            'badge' => $user['badge'],
            'flag' => $flag,
        ];
    }
    echo json_encode($result);
    http_response_code(200);

    exit;
}

header('Error: The user is not in the room.');
http_response_code(403);
