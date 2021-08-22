<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=utf-8");
require_once('../library/library.php');
require_once('../room_connect/room.php');
$library = new library();

if (filter_input(INPUT_GET, 'search') && filter_input(INPUT_GET, 'sort') && filter_input(INPUT_GET, 'period') && filter_input(INPUT_GET, 'page')) {
    $search = (int)$_POST['search'];
    $sort = (int)$_POST['sort'];
    $period = (int)$_POST['period'];
    $page = (int)$_POST['page'];
    $userID = (int)$_POST['userID'];
    $result = $library->GetLibrary($search, $sort, $period, $page, $userID);
    $responce = 200;
} else {
    $result = array('state'=>'リクエストした値が指定している形式と異なる');
    $responce = 400;
}
echo json_encode($result);
http_response_code($responce);