<?php
header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../room_connect/room.php');
require_once('../userInfo/userInfo.php');

$room = new Room();
$userInfo = new userInfo();

if ($userInfo->CheckLogin() === false) {
    echo json_encode(array('state' => 'ログインしていません。'));
    http_response_code(403);
    exit;
}

if (filter_input(INPUT_POST, 'roomID')) {
    $userID = $userInfo->CheckLogin()['userID'];
    $roomID = (int)($_POST['roomID']);
    $result = $room->JoinRoom($userID, $roomID);
} else {
    $result = array('state' => 1);
}

echo json_encode($result);
http_response_code(200);
