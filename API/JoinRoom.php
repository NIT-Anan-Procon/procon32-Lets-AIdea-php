<?php

ini_set('display_errors', 1);

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Room.php';

require_once '../lib/UserInfo.php';

$room = new Room();
$userInfo = new userInfo();

if (false === $userInfo->CheckLogin()) {
    echo json_encode(['state' => 'ログインしていません。']);
    http_response_code(403);

    exit;
}

if (filter_input(INPUT_POST, 'roomID')) {
    $userID = $userInfo->CheckLogin()['userID'];
    $roomID = (int) ($_POST['roomID']);
    $result = $room->JoinRoom($userID, $roomID);
    if (null !== $result['playerID']) {
        $result = $result['playerID'];
    }
} else {
    $result = ['state' => '部屋番号が入力されていません。'];
}

echo json_encode($result);
http_response_code(200);