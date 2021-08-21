<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=utf-8");
require_once('../library/library.php');
require_once('../room_connect/room.php');
$library = new library();

if (filter_input(INPUT_POST, 'playerID')) {
//    $userID explanation pictureURL NGword flag
    $playerID = (int)$_POST['playerID'];
    $flag = (int)$_POST['flag'];
    $result = $point->AddPoint($gameID, $playerID, $pointNum, $flag);
    $responce = 200;
} else {
    $result = array('state'=>1);
    $responce = 400;
}
echo json_encode($result);
http_response_code($responce);