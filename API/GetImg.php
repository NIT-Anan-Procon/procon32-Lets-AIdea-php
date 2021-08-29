<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Picture.php';
require_once '../lib/UserInfo.php';
require_once '../lib/Room.php';

$room = new Room();
$picture = new Picture();
$userInfo = new userInfo();

if (false === $userInfo->CheckLogin()) {
    echo json_encode(['state' => 'ログインしていません']);
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$playerID = $room->getGameInfo($userID)['playerID'];
$result = $picture->GetGameInfo($playerID);

echo json_encode($result);
http_response_code(200);
