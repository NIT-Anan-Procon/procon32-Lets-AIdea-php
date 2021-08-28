<?php

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=utf-8");
require_once('../lib/Library.php');
require_once('../room_connect/room.php');
$library = new library();
//    $userID explanation pictureURL NGword flag
$result = $point->AddPoint($gameID, $playerID, $pointNum, $flag);
echo json_encode($result);
http_response_code(200);
