<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../../lib/Picture.php';

require_once '../../lib/Room.php';

require_once '../../lib/UnsplashApi.php';

require_once '../../lib/UserInfo.php';

require_once '../../lib/Word.php';

require_once '../../Develop.php';

$picture = new Picture();
$room = new Room();
$userInfo = new UserInfo();
$word = new Word();
$unsplash = new UnsplashApi();

// ログインしているかチェック
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

if (1 !== (int) ($gameInfo['flag'])) {
    header('You do not have the authority.');
    http_response_code(403);

    exit;
}

// 部屋の情報を取得
$gameID = $gameInfo['gameID'];
$playerID = $gameInfo['playerID'];
$gamemode = $gameInfo['gamemode'];

// 画像を取得し保存
$photo = $unsplash->InitialPhoto();

// 部屋の設定情報を取得
$mode = substr($gamemode, 0, 1);
$ngWord = substr($gamemode, 1, 1);
$wordNum = substr($gamemode, 2, 1);

// PythonのAPIをたたく
$data = json_encode(['url' => $photo, 'subject' => 1, 'synonym' => 1]);
$url = 'http://localhost:5000/test';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$val = (array) (json_decode($response));
