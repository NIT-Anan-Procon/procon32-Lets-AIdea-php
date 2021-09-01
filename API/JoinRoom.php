<?php

ini_set('display_errors', 1);

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Room.php';

require_once '../lib/UserInfo.php';

$room = new Room();
$userInfo = new UserInfo();

if (false === $userInfo->CheckLogin()) {
    http_response_code(403);
    header('Error: Login failed.');

    exit;
}

if (filter_input(INPUT_POST, 'roomID')) {
    $userID = $userInfo->CheckLogin()['userID'];
    $roomID = (int) ($_POST['roomID']);
    $result = $room->JoinRoom($userID, $roomID);
    if (false !== $result) {
        $result = $result['playerID'];
        echo json_encode($result);
        http_response_code(200);

        exit;
    }
    http_response_code(403);
    header('Error: The maximum number of people in the room has been reached.');

    exit;
}
    http_response_code(401);
    header('Error: The requested value is different from the specified format.');

    exit;
