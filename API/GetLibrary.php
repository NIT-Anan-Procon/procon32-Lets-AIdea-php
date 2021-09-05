<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Library.php';

require_once '../lib/UserInfo.php';

$library = new Library();
$userInfo = new UserInfo();
if (false === $userInfo->CheckLogin()) {
    header('Error:Login failed.');
    http_response_code(403);

    exit;
}
if (isset($_GET['search'], $_GET['sort'], $_GET['period'], $_GET['userID']) && filter_input(INPUT_GET, 'page')) {
    $search = (int) $_GET['search'];
    $sort = (int) $_GET['sort'];
    $period = (int) $_GET['period'];
    $page = (int) $_GET['page'];
    $userID = (int) $_GET['userID'];
    $result = $library->GetLibrary($search, $sort, $period, $page, $userID);
    for ($i = 0; $i < count($result); ++$i) {
        $user = $userInfo->getUserInfo($result[$i]['userID']);
        unset($user['password']);
        $result[$i] += $user;
    }
    echo json_encode($result);
    http_response_code(200);
} else {
    header('Error:The requested value is different from the specified format.');
    http_response_code(401);
}
