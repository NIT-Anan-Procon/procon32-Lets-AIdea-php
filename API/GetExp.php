<?php
header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../explanation/explanation.php');

$explanation = new Explanation();

if(filter_input(INPUT_POST,'gameID') && filter_input(INPUT_POST,'playerID')) {
    $gameID = (int)($_POST['gameID']);
    $playerID = (int)($_POST['playerID']);
    $result = $explanation->GetExplanation($gameID,$playerID);
    $count = count($explanation->GetExplanation($gameID,$playerID));
    if($count === 0) {
        $result = false;
    }
} else {
    $result = false;
}

echo json_encode($result);
http_response_code(200);
