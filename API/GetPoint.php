<?php
header("Access-Control-Allow-Origin:*");     //localhostからのアクセスのみに制限
header("Content-Type: application/json; charset=utf-8");
require_once('../point/point.php');

$point = new point();

if (filter_input(INPUT_POST, 'playerID') && filter_input(INPUT_POST, 'flag')) {
//    $gameID =
    $playerID = (int)$_POST['playerID'];
    $flag = (int)$_POST['flag'];
    $result = $point->GetPoint($gameID, $playerID, $flag);
    $responce = 200;
} else {
    $result = array('state'=>"リクエストした値が指定している形式と異なる");
    $responce = 400;
}

echo json_encode($result);
http_response_code($responce);
