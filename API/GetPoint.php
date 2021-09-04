<?php

header('Access-Control-Allow-Origin:*');     //localhostからのアクセスのみに制限
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/point.php';

$point = new Point();

$gameID = 1;
$result = [];
for ($i = 1; $i <= 4; ++$i) {
    $exp = $point->GetPoint($gameID, $i, 0);
    $ans = $point->GetPoint($gameID, $i, 1);
    $vot = $point->GetPoint($gameID, $i, 2);
    $result += [$i => ['exp' => $exp, 'ans' => $ans, 'vot' => $vot]];
}
$responce = 200;
echo json_encode($result);
http_response_code($responce);
