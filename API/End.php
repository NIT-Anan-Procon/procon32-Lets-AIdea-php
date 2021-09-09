<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Point.php';

require_once '../lib/Word.php';

require_once '../lib/Picture.php';

require_once '../lib/Library.php';

require_once '../lib/Room.php';

require_once '../lib/UserInfo.php';

$point = new Point();
$word = new Word();
$picture = new Picture();
$library = new Library();
$room = new Room();
$userInfo = new UserInfo();
$user['userInfo'] = $userInfo->CheckLogin();
if (false === $user['userInfo']) {
    header('Error:Login failed.');
    http_response_code(403);

    exit;
}
$user['room'] = $room->getGameInfo($user['userInfo']['userID']);
if (false === $user['room']) {
    header('Error:The user is not in the room.');
    http_response_code(403);

    exit;
}
$game = $room->GameInfo($user['room']['gameID']);
$playerNum = count($game);
$max = -1;
for ($i = 0; $i <= $playerNum; ++$i) {
    $vote[$i] = $point->getPoint($user['room']['gameID'], $i, 2);
    if ($max <= $vote[$i]) {
        $max = $vote[$i];
        $winner = $i;
    }
}
var_dump($winner);
for ($i = 0; $i <= $playerNum; ++$i) {
    if ($vote[$i] === $max && $i !== $winner) {
        //引き分け！！
    }
}
if (0 === $winner) {
    $result = $userInfo->getUserInfo(1);     //AI
} else {
    $result = $userInfo->getUserInfo($game[$winner - 1]['userID']);
}
$result['explanation'] = $word->getWord($user['room']['gameID'], $winner, 0);
$result['ng'] = $word->getWord($user['room']['gameID'], $winner, 2);
if ((int) $user['room']['gamemode'] >= 1000) {
    $result['pictureURL'] = $picture->getPicture($user['room']['gameID'], $winner, 1)[0]['pictureURL'];
} else {
    $result['pictureURL'] = $picture->getPicture($user['room']['gameID'], null, 0)[0]['pictureURL'];
}
if (1 === (int) $user['room']['flag']) {
    $ng = $result['ng'][0];
    for ($i = 1; $i < count($result['ng']); ++$i) {
        $ng .= ','.$result['ng'][$i];
    }
    $flag = (int) ((int) $user['room']['gamemode'] / 1000);
    $lib = $library->UploadLibrary($result['userID'], $result['explanation'], $ng, $result['pictureURL'], $flag);
    if (false === $lib) {
        http_response_code(400);

        exit;
    }
}
unset($result['userID'], $result['password']);

echo json_encode($result);
http_response_code(200);
