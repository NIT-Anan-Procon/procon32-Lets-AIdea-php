<?php

header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=utf-8");

require_once('../word/word.php');

$word = new Word();
$result = [];
//$gameID playerの数
$gameID = 1;
for ($i = 1; $i <= 4; $i++) {
    $exp = $word->GetWord($gameID, $i, 0);
    $AI =  $word->GetWord($gameID, $i, 1);
    $NGword = $word->GetWord($gameID, $i, 2);
    $result += [$i => ['explanation' => $exp, 'AI' => $AI, 'NGword' => $NGword]];
}

$responce = 200;
echo json_encode($result);
http_response_code($responce);
