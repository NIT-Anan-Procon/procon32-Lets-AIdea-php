<?php
header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../word/word.php');

$word = new word();

if(filter_input(INPUT_POST,'playerID') && filter_input(INPUT_POST,'flag')) {
    $playerID = (int)($_POST['playerID']);
    $flag = (int)($_POST['flag']);
    $result = $word->GetWord($gameID,$playerID, $flag);
    $responce = 200;
}
else{
    $result = array('state'=>1);
    $responce = 400;
}
echo json_encode($result);
http_response_code($responce);