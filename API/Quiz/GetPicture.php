<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../../lib/Picture.php';

require_once '../../lib/UserInfo.php';

require_once '../../lib/Room.php';

require_once '../../lib/Word.php';

$picture = new Picture();
$room = new Room();
$userInfo = new userInfo();
$word = new Word();

if (false === $userInfo->CheckLogin()) {
    header('Error:Login failed.');
    http_response_code(403);

    exit;
}
$userID = $userInfo->CheckLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);
if (false === $gameInfo) {
    header('Error:The user is not in the room.');
    http_response_code(403);

    exit;
}
$gameID = $gameInfo['gameID'];
$roomInfo = $room->GameInfo($gameID);
$result = [];
for ($i = 0; $i < count($roomInfo); ++$i) {
    $playerID = $roomInfo[$i]['playerID'];
    $photos = $picture->GetPicture($gameID, $playerID);
    $user = $userInfo->getUserInfo($roomInfo[$i]['userID']);
    $explanation = $word->getWord($gameID, $playerID, 0);
    $array = [
        'name' => $user['name'],
        'icon' => $user['icon'],
        'badge' => $user['badge'],
        'explanation' => $explanation,
    ];
    for ($j = 0; $j < count($photos); ++$j) {
        $img_array = [
            'pictureURL' => $photos[$j]['pictureURL'],
            'answer' => $photos[$j]['answer'],
        ];
        $array[] = $img_array;
    }
    $result[] = $array;
}

echo json_encode($result);
http_response_code(200);
