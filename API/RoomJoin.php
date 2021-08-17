<?php
header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../room_connect/room.php');

$room = new Room();

if(filter_input(INPUT_POST, 'roomID') && filter_input(INPUT_POST, 'userID')) {
    $roomID = $_POST['roomID'];
    $userID = $_POST['userID'];
    $count = count($room->RoomInfo($roomID));
    if($count != 0) {
        $room->JoinRoom($userID, $roomID);
        $result = $room->RoomInfo($roomID);
    } else {
        $result = false;
    }
} else {
    $result = false;
}

echo json_encode($result);
http_response_code(200);
