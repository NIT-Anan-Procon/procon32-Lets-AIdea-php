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
$game = $room->getGameInfo($userID);
if (false !== $game) {
    $gameID = $game['gameID'];
    $gameInfo = $room->GameInfo($gameID);
    $playerNum = count($gameInfo);
    $gamemode = $gameInfo[0]['gamemode'];
    $result = ['gamemode' => $gamemode];
    for ($i = 1; $i <= $playerNum; ++$i) {
        $userID = $gameInfo[$i - 1]['userID'];
        $flag = $gameInfo[$i - 1]['flag'];
        $user = $userInfo->GetUserInfo($userID);
        $playerID = $gameInfo[$i - 1]['playerID'];
        $result += [$playerID => ['flag' => $flag, 'name' => $user['name'], 'icon' => $user['icon'], 'badge' => $user['badge']]];
    }
    echo json_encode($result);
    http_response_code(200);
    exit;
}

http_response_code(403);
