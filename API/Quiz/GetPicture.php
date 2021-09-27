<?php

ini_set('display_errors', 1);

require_once '../../lib/Picture.php';

require_once '../../lib/UserInfo.php';

require_once '../../lib/Room.php';

require_once '../../lib/Word.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
header('Content-Type: application/json; charset=utf-8');
$picture = new Picture();
$room = new Room();
$userInfo = new UserInfo();
$word = new Word();

if (false === $userInfo->checkLogin()) {
    header('Error:Login failed.');
    http_response_code(403);

    exit;
}
$userID = $userInfo->checkLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);
if (false === $gameInfo) {
    header('Error:The user is not in the room.');
    http_response_code(403);

    exit;
}
$gameID = $gameInfo['gameID'];
$playerID = $gameInfo['playerID'];
$roomInfo = $room->gameInfo($gameID);
$result['playerID'] = $playerID;
for ($i = 0; $i < count($roomInfo); ++$i) {
    $playerID = $roomInfo[$i]['playerID'];
    $photos = $picture->getPicture($gameID, $playerID, 0);
    $user = $userInfo->getUserInfo($roomInfo[$i]['userID']);
    $explanation = $word->getWord($gameID, $playerID, 0);
    $array = [
        'name' => $user['name'],
        'icon' => $user['icon'],
        'badge' => $user['badge'],
        'explanation' => $explanation,
    ];
    $img = [];
    for ($j = 0; $j < count($photos); ++$j) {
        $img_array = [
            'pictureURL' => $photos[$j]['pictureURL'],
            'answer' => $photos[$j]['answer'],
        ];
        $img[] = $img_array;
    }
    $array['picture'] = $img;
    $val[$playerID] = $array;
}
$result['player'] = $val;

echo json_encode($result);
http_response_code(200);
