<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../../lib/Room.php';

require_once '../../lib/UserInfo.php';

require_once '../../lib/Picture.php';

require_once '../../lib/Word.php';

$room = new Room();
$userInfo = new UserInfo();
$picture = new Picture();
$word = new Word();
$user['userInfo'] = $userInfo->CheckLogin();

if (false === $user['userInfo']) {
    header('Error: Login failed.');
    http_response_code(403);

    exit;
}
$user['game'] = $room->getGameInfo($user['userInfo']['userID']);
if (false === $user['game']) {
    header('Error: The user is not in the room.');
    http_response_code(403);

    exit;
}

$result['playerID'] = $user['game']['playerID'];
$result['AI'] = $word->getWord($user['game']['gameID'], 0, 1);
$gameInfo = $room->GameInfo($user['game']['gameID']);
$url = $picture->getPicture($user['game']['gameID'], null, 2);
if (!isset($url[0])) {
    header('Error: The requested picture does not exist.');
    http_response_code(403);

    exit;
}
$result['pictureURL'] = $url[0]['pictureURL'];
for ($i = 0; $i < count($gameInfo); ++$i) {
    $playerID = $gameInfo[$i]['playerID'];
    $playerInfo = $userInfo->GetUserInfo($gameInfo[$i]['userID']);
    $explanation = $word->getWord($user['game']['gameID'], $playerID, 0);
    $ng = $word->getWord($user['game']['gameID'], $playerID, 2);
    $result['player'][$playerID] = [
        'name' => $playerInfo['name'],
        'icon' => $playerInfo['icon'],
        'badge' => $playerInfo['badge'],
        'explanation' => $explanation,
        'ng' => $ng,
    ];
}

echo json_encode($result);
http_response_code(200);
