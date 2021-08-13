<?php
header("Access-Control-Allow-Origin:http://localhost");     //localhostからのアクセスのみに制限
header("Content-Type: application/json; charset=utf-8");
require_once('../point/point.php');

$point = new point();

if($_POST != null) {
    $playerID = $_POST['playerID'];
    $gameID = $_POST['gameID'];
    $pointNum = $_POST['pointNum'];
    $point->AddPoint($playerID, $gameID, $pointNum);
    $result = true;
} else {
    $result = false;
}

echo json_encode($result);
http_response_code(200);