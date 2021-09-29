<?php

ini_set('display_errors', 1);

require_once '../../lib/Room.php';

require_once '../../lib/UserInfo.php';

require_once '../../lib/Picture.php';

require_once '../../lib/Word.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
header('Content-Type: application/json; charset=utf-8');
$room = new Room();
$userInfo = new UserInfo();
$picture = new Picture();
$word = new Word();

if (false === $userInfo->checkLogin()) {
    header('Error: Login failed.');
    http_response_code(403);

    exit;
}

// ユーザーが他の部屋に入っているかチェック
$userID = $userInfo->checkLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);

if (false === $gameInfo) {
    header('Error: The user is not in the room.');
    http_response_code(403);

    exit;
}

$gameID = $gameInfo['gameID'];
$player = $gameInfo['playerID'];
$roomInfo = $room->roomInfo($gameInfo['roomID']);
$result = [];
for ($i = 0; $i < count($roomInfo); ++$i) {
    $playerID = $roomInfo[$i]['playerID'];
    $userID = $roomInfo[$i]['userID'];
    $url = $picture->getPicture($gameID, $playerID, 1)[0]['pictureURL'];
    $user = $userInfo->getUserInfo($userID);
    $explanation = $word->getWord($gameID, $playerID, 0);
    $ng = $word->getWord($gameID, $playerID, 2);
    $array[$playerID] = [
        'ng' => $ng,
        'name' => $user['name'],
        'icon' => $user['icon'],
        'badge' => $user['badge'],
        'explanation' => $explanation,
        'pictureURL' => $url,
    ];
}
$result['player'] = $array;
$result['playerID'] = $player;

echo json_encode($result);
http_response_code(200);
