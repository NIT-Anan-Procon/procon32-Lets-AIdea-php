<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../../lib/Picture.php';

require_once '../../lib/Room.php';

require_once '../../lib/Unsplash_API.php';

require_once '../../lib/UserInfo.php';

require_once '../../lib/Word.php';

$picture = new Picture();
$room = new Room();
$userInfo = new UserInfo();
$word = new Word();

if (false === $userInfo->CheckLogin()) {
    header('Error: Login failed.');
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);
if (false === $response) {
    header('Error: The user is not in the room.');
    http_response_code(403);

    exit;
}

$gameID = $gameInfo['gameID'];
$playerID = $gameInfo['playerID'];
$gamemode = $gameInfo['gamemode'];

$photos = $picture->getPicture($gameID, $playerID);
$sentence = $word->GetWord($gameID, $playerID, 1);
$NG = $word->GetWord($gameID, $playerID, 2);
$synoyms = $word->GetWord($gameID, $playerID, 3);

$result = [
    'ng' => $NG,
    'AI' => $sentence,
    'pictureURL' => $photos,
    'gamemode' => $gamemode,
];

echo json_encode($result);
http_response_code(200);
