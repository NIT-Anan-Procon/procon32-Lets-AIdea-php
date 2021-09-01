<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Library.php';

require_once '../lib/Room.php';
$library = new Library();
//    $userID explanation pictureURL NGword flag

if (false === $userInfo->CheckLogin()) {
    echo json_encode(['state' => 'login failed']);
    http_response_code(403);

    exit;
}
$userID = $userInfo->CheckLogin()['userID'];
$result = $library->UploadLibrary($userID, $explanation, $NGword, $pictureURL, $flag);
echo json_encode($result);
http_response_code(200);
