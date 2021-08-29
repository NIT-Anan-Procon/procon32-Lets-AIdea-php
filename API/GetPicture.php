<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Picture.php';

require_once '../lib/UserInfo.php';

require_once '../lib/Room.php';

$room = new Room();
$picture = new Picture();
$userInfo = new userInfo();

if (false === $userInfo->CheckLogin()) {
    $result = ['state' => 'ログインしていません'];
    echo json_encode($result);
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$gameID = $room->getGameInfo($userID)['gameID'];
for ($i = 1; $i <= 4; ++$i) {
    $result += [$i => $picture->GetPicture($gameID, $i)];
}
echo json_encode($result);
http_response_code(200);
