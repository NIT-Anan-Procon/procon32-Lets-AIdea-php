<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=utf-8");

require_once('../word/word.php');

$word = new word();

//$playerID
$result = $word->GetWord($gameID, $playerID);
$responce = 200;

echo json_encode($result);
http_response_code($responce);
