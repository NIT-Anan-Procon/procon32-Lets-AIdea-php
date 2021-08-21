<?php
header("Access-Control-Allow-Origin:*");     //localhostからのアクセスのみに制限
header("Content-Type: application/json; charset=utf-8");
require_once('../point/point.php');
require_once('../room_connect/room.php');
$point = new point();

if (filter_input(INPUT_POST, 'playerID') && filter_input(INPUT_POST, 'pointNum') && filter_input(INPUT_POST, 'flag')) {
//    $gameID =
    $playerID = (int)$_POST['playerID'];
    $pointNum = (int)$_POST['pointNum'];
    $flag = (int)$_POST['flag'];
    $result = $point->AddPoint($gameID, $playerID, $pointNum, $flag);
    $responce = 200;
} else {
    $result = array('state'=>1);
    $responce = 400;
}
echo json_encode($result);
http_response_code($responce);
