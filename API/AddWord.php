<?php
header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../word/word.php');

$word = new word();

if(filter_input(INPUT_POST, 'playerID') && filter_input(INPUT_POST, 'word') && filter_input(INPUT_POST, 'flag')) {
//    $gameID = (int)($_POST['gameID']);
    $playerID = (int)($_POST['playerID']);
    $word = $_POST['word'];
    $flag = (int)($_POST['flag']);
    $result = $word->AddWord($gameID,$playerID,$word,$flag);
    $responce = 200;
} else {
    $result = array('state'=>1);
    $responce = 400;
}

echo json_encode($result);
http_response_code($responce);