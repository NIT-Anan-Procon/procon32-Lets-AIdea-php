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
if (filter_input(INPUT_POST, 'word') && isset($_POST['flag'])) {
    $exp = $_POST['word'];
    $flag = (int)($_POST['flag']);
    $result = $word->AddWord($user['gameID'], $user['playerID'], $exp, $flag);
    if($result === false){
        header("Error:Connection error with DB.");
        http_response_code(400);
    } else {
        echo json_encode($result);
        http_response_code(200);
    }
} else {
    header("Error:The requested value is different from the specified format.");
    http_response_code(401);
}
