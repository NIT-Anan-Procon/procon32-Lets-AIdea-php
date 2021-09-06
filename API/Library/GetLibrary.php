<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Library.php';

require_once '../lib/UserInfo.php';

$library = new Library();
$userInfo = new UserInfo();
$loginUser = $userInfo->CheckLogin();
if (false === $loginUser) {
    header('Error:Login failed.');

    exit;
}
if (isset($_GET['search'], $_GET['sort'], $_GET['period']) && filter_input(INPUT_GET, 'page')) {
    $search = (int) $_GET['search'];
    $sort = (int) $_GET['sort'];
    $period = (int) $_GET['period'];
    $page = (int) $_GET['page'];
    if (filter_input(INPUT_GET, 'userID')) {
        $userID = (int) $_GET['userID'];
    } else {
        $userID = 0;
    }
    $result = $library->GetLibrary($search, $sort, $period, $page, $userID);
    for ($i = 0; $i < count($result); ++$i) {
        $user = $userInfo->getUserInfo($result[$i]['userID']);
        unset($user['password']);
        $result[$i] += $user;

        $check = $library->check($result[$i]['libraryID'], $loginUser['userID']);
        if (false === $check) {
            $result[$i]['check'] = 0;
        } else {
            $result[$i]['check'] = 1;
        }
    }
    echo json_encode($result);
    http_response_code(200);
} else {
    header('Error:The requested value is different from the specified format.');
    http_response_code(401);
}
