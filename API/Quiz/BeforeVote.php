<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:http://localhost');
header('Content-Type: application/json; charset=utf-8');

require_once '../../lib/Room.php';

require_once '../../lib/UserInfo.php';

require_once '../../lib/Picture.php';

require_once '../../lib/Word.php';

$room = new Room();
$userInfo = new UserInfo();
$picture = new Picture();
$word = new Word();

if (false === $userInfo->CheckLogin()) {
    header('Error: Login failed.');
    http_response_code(403);

    exit;
}

// ユーザーが他の部屋に入っているかチェック
$userID = $userInfo->CheckLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);

if (false === $gameInfo) {
    header('Error: The user is not in the room.');
    http_response_code(403);

    exit;
}

$gameID = $gameInfo['gameID'];
$roomInfo = $room->RoomInfo($gameInfo['roomID']);
$result = [];
for ($i = 0; $i < count($roomInfo); ++$i) {
    $playerID = $roomInfo[$i]['playerID'];
    $userID = $roomInfo[$i]['userID'];
    $urls = $picture->GetPicture($gameID, $playerID);
    $answer = '';
    foreach ($urls as $url) {
        if (1 === $url['answer']) {
            $answer = $url['pictureURL'];
        }
    }
    $user = $userInfo->GetUserInfo($userID);
    $explanation = $word->getWord($gameID, $playerID, 0);
    $array[$playerID] = [
        'name' => $user['name'],
        'icon' => $user['icon'],
        'badge' => $user['badge'],
        'explanation' => $explanation,
        'pictureURL' => $answer,
    ];
}
$result = $array;

echo json_encode($result);
http_response_code(200);
