<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Library.php';
$library = new Library();

if (filter_input(INPUT_GET, 'search') && filter_input(INPUT_GET, 'sort') && filter_input(INPUT_GET, 'period') && filter_input(INPUT_GET, 'page')) {
    $search = (int) $_GET['search'];
    $sort = (int) $_GET['sort'];
    $period = (int) $_GET['period'];
    $page = (int) $_GET['page'];
    $userID = (int) $_GET['userID'];
    $result = $library->GetLibrary($search, $sort, $period, $page, $userID);
    $responce = 200;
} else {
    $result = ['state' => 'リクエストした値が指定している形式と異なる'];
    $responce = 400;
}
echo json_encode($result);
http_response_code($responce);
