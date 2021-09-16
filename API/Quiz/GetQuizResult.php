<?php

ini_set('display_errors', 1);

require_once '../../lib/Room.php';

require_once '../../lib/UserInfo.php';

require_once '../../lib/Point.php';

require_once '../../lib/Word.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
header('Content-Type: application/json; charset=utf-8');
$room = new Room();
$userInfo = new UserInfo();
$point = new Point();
$word = new Word();

if (false === $userInfo->CheckLogin()) {
    header('Error: Login failed.');
    http_response_code(403);

    exit;
}

// ユーザーが部屋に入っているかチェック
$userID = $userInfo->CheckLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);

if (false === $gameInfo) {
    header('Error: The user is not in the room.');
    http_response_code(403);

    exit;
}

function sortByKey($key_name, $sort_order, $array)
{
    foreach ($array as $key => $value) {
        $standard_key_array[$key] = $value[$key_name];
    }

    array_multisort($standard_key_array, $sort_order, $array);

    return $array;
}

$gameID = $gameInfo['gameID'];
$roomInfo = $room->RoomInfo($gameInfo['roomID']);
$result = [];
for ($i = 0; $i < count($roomInfo); ++$i) {
    $explanation = $word->getWord($gameID, $roomInfo[$i]['playerID'], 0);
    $exp = $point->GetPoint($gameID, $roomInfo[$i]['playerID'], 0);
    $ans = $point->GetPoint($gameID, $roomInfo[$i]['playerID'], 1);
    $user = $userInfo->GetUserInfo($roomInfo[$i]['userID']);
    $array = [
        'name' => $user['name'],
        'icon' => $user['icon'],
        'badge' => $user['badge'],
        'explanation' => $explanation,
        'exp' => $exp,
        'ans' => $ans,
        'sum' => $exp + $ans,
    ];
    $result[] = $array;
}

$result = sortByKey('sum', SORT_DESC, $result);
for ($i = 0; $i < count($roomInfo); ++$i) {
    unset($result[$i]['sum']);
}

echo json_encode($result);
http_response_code(200);
