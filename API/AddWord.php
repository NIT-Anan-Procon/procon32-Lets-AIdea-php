<?php

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=utf-8");

require_once('../lib/word.php');

$word = new Word();
$gameID = 1;
$playerID = 4;
if (filter_input(INPUT_POST, 'word') && isset($_POST['flag'])) {
    $exp = $_POST['word'];
    $flag = (int)($_POST['flag']);
    $result = $word->AddWord($gameID, $playerID, $exp, $flag);
    $responce = 200;
} else {
    $result = array('state'=>'リクエストした値が指定している形式と異なる');
    $responce = 400;
}
echo json_encode($result);
http_response_code($responce);
