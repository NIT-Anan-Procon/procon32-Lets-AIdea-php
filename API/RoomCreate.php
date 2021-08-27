<?php
header("Access-Control-Allow-Origin:http://localhost");     //localhostからのアクセスのみに制限する
header("Content-Type: application/json; charset=utf-8");    //レスポンスする形式はjson

require_once('../room_connect/room.php');

$room = new Room();

if(filter_input(INPUT_POST, 'userID')) {
    $gameID = $room->CreateGameID();
    $userID = $_POST['userID'];
    $roomID = $room->CreateRoomID();
    $room->AddRoom($gameID, $userID, $roomID);
    $result = $room->RoomInfo($roomID);
} else {
    $result = false;
}

echo json_encode($result);
http_response_code(200);
