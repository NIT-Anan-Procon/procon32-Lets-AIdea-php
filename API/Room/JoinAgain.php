<?php

ini_set('display_errors', 1);

require_once '../../lib/Room.php';

require_once '../../lib/UserInfo.php';

require_once '../../lib/Picture.php';

require_once '../../lib/Point.php';

require_once '../../lib/Word.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
header('Content-Type: application/json; charset=utf-8');
$room = new Room();
$userInfo = new UserInfo();
$picture = new Picture();
$point = new Point();
$explanation = new Word();

if (false === $userInfo->CheckLogin()) {
    header('Error: Login failed.');
    http_response_code(403);

    exit;
}

// userIDでプレイヤーの情報を取得
$userID = $userInfo->CheckLogin()['userID'];
$player = $room->getGameInfo($userID);

// ユーザーが部屋に入っているかチェック
if (false === $player) {
    header('Error: The user is not in the room.');
    http_response_code(403);

    exit;
}

$gameID = $player['gameID'];
$playerID = $player['playerID'];
$gameInfo = $room->GameInfo($gameID);

if (1 === count($gameInfo)) {
    $picture->deleteGameInfo($gameID);
    $point->deleteGameInfo($gameID);
    $explanation->DelWord($gameID);
}

// roomIDで部屋の情報を取得
$roomID = $player['roomID'];
$roomInfo = $room->RoomInfo($roomID);

$count = count($roomInfo);
$flag = 0;
for ($i = 0; $i < $count; ++$i) {
    if ($gameID < $roomInfo[$i]['gameID']) {
        $gameID = $roomInfo[$i]['gameID'];
        $flag = 1;
    }
}

// gameIDを更新されているかチェック
if (0 === $flag) {
    $gameID = $room->GetGameID() + 1;
    $room->joinAgain($gameID, $userID);
} else {
    $room->joinAgain($gameID, $userID);
}

$playerInfo = $room->PlayerInfo($gameID, $playerID);
$user = $userInfo->GetUserInfo($userID);
$result = [
    'playerID' => $playerInfo['playerID'],
    'name' => $user['name'],
    'icon' => $user['icon'],
    'badge' => $user['badge'],
    'gamemode' => $playerInfo['gamemode'],
    'roomID' => $roomID,
];

echo json_encode($result);
http_response_code(200);
