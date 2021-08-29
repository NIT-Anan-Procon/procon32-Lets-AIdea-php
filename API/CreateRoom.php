<?php

header('Access-Control-Allow-Origin:*');     //localhostからのアクセスのみに制限する
header('Content-Type: application/json; charset=utf-8');    //レスポンスする形式はjson

require_once '../lib/Room.php';

require_once '../lib/UserInfo.php';

$room = new Room();
$userInfo = new userInfo();

if (false === $userInfo->CheckLogin()) {
    echo json_encode(['state' => 'ログインしていません。']);
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$roomID = $room->CreateRoomID();
$gameID = $room->GetGameID() + 1;
$playerID = 1;
$room->AddRoom($userID, $playerID, $roomID, $gameID, 1);
$playerID++;
for ($i = 0; $i < 3; ++$i) {
    $room->AddRoom(null, $playerID, $roomID, $gameID, 0);
    $playerID++;
}
$result = $room->OwnerInfo($roomID)['playerID'];

echo json_encode($result);
http_response_code(200);
