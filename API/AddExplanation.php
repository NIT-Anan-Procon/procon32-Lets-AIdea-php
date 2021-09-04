<?php

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=utf-8");

require_once '../lib/Word.php';
require_once '../lib/Room.php';
require_once '../lib/UserInfo.php';
$word = new Word();
$room = new Room();
$userInfo = new UserInfo();

if (false === $userInfo->CheckLogin()) {
    header("Error:Login failed.");
    http_response_code(403);

    exit;
}
$userID = $userInfo->CheckLogin()['userID'];
if (false === $room->getGameInfo($userID)) {
    header("Error:The user is not in the room.");
    http_response_code(403);

    exit;
}
$user = $room->getGameInfo($userID);
if (filter_input(INPUT_POST, 'explanation')) {
    $explanation = $_POST['explanation'];
    $result = $word->AddWord($user['gameID'], $user['playerID'], $explanation, 0);
    if($result === false){
        http_response_code(400);
    } else {
        http_response_code(200);
    }
} else {
    header("Error:The requested value is different from the specified format.");
    http_response_code(401);
}
