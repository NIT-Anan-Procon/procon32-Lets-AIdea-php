<?php
header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../word/word.php');

$word = new word();

if (filter_input(INPUT_GET, 'playerID') && filter_input(INPUT_GET, 'flag')) {
    $playerID = (int)($_GET['playerID']);
    $flag = (int)($_GET['flag']);
    $result = $word->GetWord($gameID, $playerID, $flag);
    $responce = 200;
} else {
    $result = array('state'=>"リクエストした値が指定している形式と異なる");
    $responce = 400;
}
echo json_encode($result);
http_response_code($responce);
