<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Room.php';

require_once '../lib/UserInfo.php';

$room = new Room();
$userInfo = new userInfo();
/*
if (false === $userInfo->CheckLogin()) {
    echo json_encode(['state' => 'login failed']);
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID']; */
$userID = 1;
$gameID = $room->getGameInfo($userID)['gameID'];
$gameInfo = $room->GameInfo($gameID);
$playerNum = count($gameInfo);
$result = [];
for ($i = 1; $i <= $playerNum; ++$i) {
    $userID = $gameInfo[$i - 1]['userID'];
    $flag = $gameInfo[$i - 1]['flag'];
    $user = $userInfo->GetUserInfo($userID);
    if($userID !== null) {
        $result += [$i => ['userID' => $userID, 'flag' => $flag, 'name' => $user['name'], 'image_icon' => $user['image_icon'], 'badge' => '']];
    } else {
        $result += [$i => ['userID' => '0', 'flag' => $flag, 'name' => '', 'image_icon' => '', 'badge' => '']];
    }
}

echo json_encode($result);
http_response_code(200);
