
<?php
header("Access-Control-Allow-Origin:*");     //localhostからのアクセスのみに制限
header("Content-Type: application/json; charset=utf-8");
require_once('../point/point.php');
require_once('../room_connect/room.php');
$point = new point();

if(filter_input(INPUT_POST, 'playerID') && filter_input(INPUT_POST, 'pointNum') && filter_input(INPUT_POST, 'flag')){
    $playerID = $_POST['playerID'];
    $pointNum = $_POST['pointNum'];

    $result = $point->AddPoint($gameID, $playerID, $pointNum, $flag);
} else {
    $result = false;
}
echo json_encode($result);
http_response_code(200);