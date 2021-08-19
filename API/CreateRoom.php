<?php
header("Access-Control-Allow-Origin:http://localhost");     //localhostからのアクセスのみに制限する
header("Content-Type: application/json; charset=utf-8");    //レスポンスする形式はjson

require_once('../room_connect/room.php');

$room = new Room();

if(filter_input(INPUT_POST, 'userID')) {
    $userID = (int)($_POST['userID']);
    $roomID = $room->CreateRoomID();
    $gameID = $room->GetGameID() + 1;
    $room->AddRoom($userID, $roomID, $gameID, 1);
    for($i = 0; $i < 3; $i++) {
        $room->AddRoom(NULL, $roomID, $gameID, 0);
    }
    $result = $room->OwnerInfo($roomID);
    $result += array('state' => 0);
} else {
    $result = array('state' => 1);
}

echo json_encode($result);
http_response_code(200);
