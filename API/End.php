<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Point.php';

require_once '../lib/Word.php';

require_once '../lib/Picture.php';

//require_once '../lib/Library.php';

require_once '../lib/Room.php';

require_once '../lib/UserInfo.php';

$point = new Point();
$word = new Word();
$picture = new Picture();
//$library = new Library();
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
for($i = 1; $i <= $playerNum; $i++){
    $vote[$i] = $point->getPoint($user['room']['gameID'], $i, 2);
    if($max < $vote[$i]){
        $max = $vote;
        $winner = $i;
    }
}
for($i = 1; $i <= $playerNum; $i++){
    if($vote[$i] == $max && $i != $winner){
        //引き分け！！
    }
}
if($winner == 0){
    $result = $userInfo(1);     //AI
} else {
    $result = $userInfo->getUserInfo($game[$winner - 1]['userID']);
}
unset($result['userID']);
unset($result['password']);
$result['explanation'] = $word->getWord($user['room']['gameID'], $winner, 0);
$result['ng'] = $word->getWord($user['room']['gameID'], $winner, 2);
if ($user['room']['gamemode'] >= 100) {
    $result['pictureURL'] = $picture->getPicture($user['room']['gameID'], null, 0)[0];
} else {
    $result['pictureURL'] = $picture->getPicture($user['room']['gameID'], $winner, 1)[0];
}
if (1 === (int)$user['room']['flag']) {
//    $library->UploadLibrary();
}
echo json_encode($result);
http_response_code(200);