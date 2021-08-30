<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Library.php';

require_once '../lib/Room.php';
$library = new Library();
//    $userID explanation pictureURL NGword flag

$result = $library->UploadLibrary($userID, $explanation, $NGword, $pictureURL, $flag);
echo json_encode($result);
http_response_code(200);
