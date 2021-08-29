<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Library.php';
$library = new library();

if (filter_input(INPUT_POST, 'libraryID')) {
    $libraryID = (int) $_POST['libraryID'];
    $result = $library->Good($libraryID);
    $responce = 200;
} else {
    $result = ['state' => 'リクエストした値が指定している形式と異なる'];
    $responce = 400;
}
echo json_encode($result);
http_response_code($responce);
