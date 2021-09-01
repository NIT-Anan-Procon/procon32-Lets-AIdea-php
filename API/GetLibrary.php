<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Library.php';
$library = new Library();
if (false === $userInfo->CheckLogin()) {
    echo json_encode(['state' => 'login failed']);
    http_response_code(403);

    exit;
}
if (isset($_GET['search'], $_GET['sort'], $_GET['period']) && filter_input(INPUT_GET, 'page') && isset($_GET['userID'])) {
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
