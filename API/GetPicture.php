<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Picture.php';

require_once '../lib/UserInfo.php';

require_once '../lib/Room.php';

$picture = new Picture();
$room = new Room();
$userInfo = new userInfo();

if (false === $userInfo->CheckLogin()) {
    header('Error:Login failed.');
    http_response_code(403);

    exit;
}
$userID = $userInfo->CheckLogin()['userID'];
if (false === $room->getGameInfo($userID)) {
    header('Error:The user is not in the room.');
    http_response_code(403);

    exit;
}
$gameID = $room->getGameInfo($userID)['gameID'];
$judge = $picture->GetPicture($gameID, null);

if (false !== $judge) {
    if (2 === (int) ($judge[0]['answer'])) {
        $result = ['pictureURL' => $judge[0]['pictureURL']];
        echo json_encode($result);
        http_response_code(200);

        exit;
    }
}

$result = [];
for ($i = 1; $i <= 4; ++$i) {
    $result += [$i => $picture->GetPicture($gameID, $i)];
}
echo json_encode($result);
http_response_code(200);
