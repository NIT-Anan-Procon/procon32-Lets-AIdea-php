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
// $photo = 'https://images.unsplash.com/46/bsrOzgDkQhGRKOVC7Era_9X6A3584.jpg?ixid=MnwyNDQ0MjR8MHwxfHJhbmRvbXx8fHx8fHx8fDE2MzE1OTY3MjI&ixlib=rb-1.2.1';
$picture->AddPicture($gameID, 0, $photo, 2);

// 部屋の設定情報を取得
$mode = substr($gamemode, 0, 1);
$ngWord = substr($gamemode, 1, 1);
$wordNum = substr($gamemode, 2, 1);

// PythonのAPIをたたく
$data = json_encode(['url' => $photo, 'subject' => 0, 'synonym' => 1]);
$url = 'http://localhost:5000/test';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$val = (array) (json_decode($response));

// // NGワード・類義語をDBに保存
if ('1' === $ngWord) {      //NGワードありと設定された場合
    if ('1' === $wordNum) {     // ワード数が多いと設定された場合
        foreach ($val['NGword'] as $ng) {
            $word->addWord($gameID, 0, $ng, 2);
        }
        for ($i = 0; $i < count($val['synonym']); ++$i) {
            if (null !== $val['synonym'][$i]) {
                $word->addWord($gameID, 0, $val['synonym'][$i][0], 2);
            }
        }
        for ($i = 0; $i < count($val['synonym']); ++$i) {
            if (null !== $val['synonym'][$i]) {
                for ($j = 1; $j < count($val['synonym'][$i]); ++$j) {
                    $word->addWord($gameID, 0, $val['synonym'][$i][$j], 3);
                }
            }
        }
    } elseif ('0' === $wordNum) {   // ワード数が普通と設定された場合
        foreach ($val['NGword'] as $ng) {
            $word->addWord($gameID, 0, $ng, 2);
        }
        for ($i = 0; $i < count($val['synonym']); ++$i) {
            if (null !== $val['synonym'][$i]) {
                for ($j = 1; $j < count($val['synonym'][$i]); ++$j) {
                    $word->addWord($gameID, 0, $val['synonym'][$i][$j], 3);
                }
            }
        }
    }
} elseif ('0' === $ngWord) {        // NGワードなしと設定された場合

    foreach ($val['NGword'] as $ng) {
        $word->addWord($gameID, 0, $ng, 3);
    }
    for ($i = 0; $i < count($val['synonym']); ++$i) {
        if (null !== $val['synonym'][$i]) {
            for ($j = 1; $j < count($val['synonym'][$i]); ++$j) {
                $word->addWord($gameID, 0, $val['synonym'][$i][$j], 3);
            }
        }
    }
}

// AIの文章を保存
$word->addWord($gameID, 0, $val['AI'], 1);

http_response_code(200);
