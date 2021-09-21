<?php

ini_set('display_errors', 1);

require_once '../../lib/Picture.php';

require_once '../../lib/Room.php';

require_once '../../lib/UnsplashApi.php';

require_once '../../lib/UserInfo.php';

require_once '../../lib/Word.php';

require_once '../../Develop.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
header('Content-Type: application/json; charset=utf-8');
$picture = new Picture();
$room = new Room();
$userInfo = new UserInfo();
$word = new Word();
$unsplash = new UnsplashApi();

//ユーザーがログインしているかチェック
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

// 部屋の情報を取得
$gameID = $gameInfo['gameID'];
$playerID = $gameInfo['playerID'];
$gamemode = $gameInfo['gamemode'];

// 値を返す
$photo = $picture->GetPicture($gameID, $playerID, 1);
$sentence = $word->getWord($gameID, $playerID, 1);
$ngWords = $word->getWord($gameID, $playerID, 2);
$synonyms = $word->getWord($gameID, $playerID, 3);

$result = [
    'synonyms' => $synonyms,
    'ng' => $ngWords,
    'AI' => $sentence,
    'pictureURL' => $photo[0]['pictureURL'],
    'gamemode' => $gamemode,
];

echo json_encode($result);
http_response_code(200);
