<?php
header("Access-Control-Allow-Origin:*");     //localhostからのアクセスのみに制限
header("Content-Type: application/json; charset=utf-8");
require_once('../point/point.php');

$point = new point();

if(filter_input(INPUT_POST, 'playerID')){
    $playerID = $_POST['playerID'];
//    $gameID = 
    $result = $point->GetPoint($playerID, $gameID);
} else {
    $result = false;
}

echo json_encode($result);
http_response_code(200);