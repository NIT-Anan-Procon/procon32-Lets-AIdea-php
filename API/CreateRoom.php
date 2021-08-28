<?php

header("Access-Control-Allow-Origin:*");     //localhostからのアクセスのみに制限する
header("Content-Type: application/json; charset=utf-8");    //レスポンスする形式はjson

require_once('../lib/Room.php');
require_once('../lib/UserInfo.php');

$room = new Room();
$userInfo = new userInfo();

if ($userInfo->CheckLogin() === false) {
    echo json_encode(array('state' => 'ログインしていません。'));
    http_response_code(403);
    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$roomID = $room->CreateRoomID();
$gameID = $room->GetGameID() + 1;
$room->AddRoom($userID, $roomID, $gameID, 1);
for ($i = 0; $i < 3; $i++) {
    $room->AddRoom(null, $roomID, $gameID, 0);
}
$result = $room->OwnerInfo($roomID);

echo json_encode($result);
http_response_code(200);
