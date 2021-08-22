<?php
header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../word/word.php');

$word = new word();

if (filter_input(INPUT_POST, 'word') && filter_input(INPUT_POST, 'flag')) {
    $word = $_POST['word'];
    $flag = (int)($_POST['flag']);
    $result = $word->AddWord($gameID, $playerID, $word, $flag);
    $responce = 200;
} else {
    $result = array('state'=>"リクエストした値が指定している形式と異なる");
    $responce = 400;
}

echo json_encode($result);
http_response_code($responce);
