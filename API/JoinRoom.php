<?php
header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../room_connect/room.php');

$room = new Room();

if(filter_input(INPUT_POST, 'roomID') && filter_input(INPUT_POST, 'userID')) {
    $roomID = $_POST['roomID'];
    $userID = $_POST['userID'];
    $result = $room->JoinRoom($userID, $roomID);
} else {
    $result = array('state' => 1);
}

echo json_encode($result);
http_response_code(200);
