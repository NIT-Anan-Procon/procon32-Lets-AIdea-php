<?php
header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../room_connect/room.php');

$room = new Room();

if(filter_input(INPUT_POST, 'playerID')) {
    $playerID = (int)($_POST['playerID']);
    $playerInfo = $room->PlayerInfo($playerID);
    if($playerInfo != false) {
        if($playerInfo['flag'] == 1) {
            $room->DeleteRoom($playerInfo['gameID']);
        } else {
            $room->LeaveRoom($playerID);
        }
        $result = array('state' => 0);
    } else{
        $result = array('state' => 2);
    }
} else {
    $result = array('state' => 1);
}

echo json_encode($result);
http_response_code(200);
