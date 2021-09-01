<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Word.php';

require_once '../lib/UserInfo.php';
$word = new Word();
$userInfo = new UserInfo();

if (false === $userInfo->CheckLogin()) {
    header("Error:Login failed.");
    http_response_code(403);

    exit;
}
$userID = $userInfo->CheckLogin()['userID'];
if (false === $room->getGameInfo($userID)) {
    header("Error:The user is not in the room.");
    http_response_code(403);

    exit;
}
$gameID = $room->getGameInfo($userID)['gameID'];
$playerNum = count($room->GameInfo($gameID));
$result = [];
for ($i = 1; $i <= $playerNum; ++$i) {
    $exp = $word->GetWord($gameID, $i, 0);
    $AI = $word->GetWord($gameID, $i, 1);
    $NGword = $word->GetWord($gameID, $i, 2);
    $result += [$i => ['explanation' => $exp, 'AI' => $AI, 'NGword' => $NGword]];
}

echo json_encode(200);
http_response_code($responce);
