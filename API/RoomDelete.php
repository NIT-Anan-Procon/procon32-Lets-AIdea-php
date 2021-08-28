<?php

header('Access-Control-Allow-Origin:http://localhost');
header('Content-Type: application/json; charset=utf-8');

require_once '../room_connect/room.php';

$room = new Room();

if (filter_input(INPUT_POST, 'playerID')) {
    $playerID = $_POST['playerID'];
    $room->DeleteRoom($playerID);
    $result = true;
} else {
    $result = false;
}

echo json_encode($result);
http_response_code(200);
