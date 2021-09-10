<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Library.php';

require_once '../lib/UserInfo.php';

$library = new Library();
$userInfo = new UserInfo();
$user = $userInfo->CheckLogin();
if (false === $user) {
    header('Error:Login failed.');
    http_response_code(403);

    exit;
}
if (filter_input(INPUT_POST, 'libraryID')) {
    $libraryID = (int) $_POST['libraryID'];
    $result = $library->Good($libraryID, $user['userID']);
    if (false === $result) {
        http_response_code(400);

        exit;
    }
    echo json_encode($result);
    http_response_code(200);
} else {
    header('Error:The requested value is different from the specified format.');
    http_response_code(401);
}
