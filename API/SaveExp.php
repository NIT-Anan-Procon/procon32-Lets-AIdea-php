<?php

header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../explanation/explanation.php');

$explanation = new Explanation();

if (filter_input(INPUT_POST, 'gameID') && filter_input(INPUT_POST, 'playerID') && filter_input(INPUT_POST, 'explanation')) {

    
    $gameID = (int)($_POST['gameID']);
    $playerID = (int)($_POST['playerID']);
    $exp = $_POST['explanation'];
    $flag = (int)($_POST['flag']);
    $explanation->AddExplanation($gameID, $playerID, $exp, $flag);
    $result = true
}

echo json_encode($result);
http_response_code(200);
