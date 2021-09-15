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
// $photo = $unsplash->InitialPhoto();
$photo = 'https://images.unsplash.com/46/bsrOzgDkQhGRKOVC7Era_9X6A3584.jpg?ixid=MnwyNDQ0MjR8MHwxfHJhbmRvbXx8fHx8fHx8fDE2MzE1OTY3MjI&ixlib=rb-1.2.1';
$picture->AddPicture($gameID, $playerID, $photo, 1);

// 部屋の設定情報を取得
$mode = substr($gamemode, 0, 1);
$ngWord = substr($gamemode, 1, 1);
$wordNum = substr($gamemode, 2, 1);

// PythonのAPIをたたく
// $data = json_encode(['url' => $photo, 'subject' => 0, 'synonyms' => 1]);
// $ch = curl_init('');    //''にpythonのAPIのurlを記述
// curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $response = curl_exec($ch);
// curl_close();
// $val = json_decode($response);

// デモ用に値を代入
$val = [
    'subject' => 'male',
    'NGword' => ['サーフボード', '波止場', '男性'],
    'synonym' => [
        [],
        [
            '波戸',
            '埠頭',
            '波戸場',
            '突堤',
            '波止',
            '桟橋',
            '岸壁',
        ],
        ['マスキュリン'],
    ],
    'AI' => 'サーフボードを持って波止場に立つ男性。',
];

// NGワード・類義語をDBに保存
if ('1' === $ngWord) {      //NGワードありと設定された場合
    if ('1' === $wordNum) {     // ワード数が多いと設定された場合
        foreach ($val['NGword'] as $ng) {
            $word->addWord($gameID, $playerID, $ng, 2);
        }
        for ($i = 0; $i < count($val['synonym']); ++$i) {
            if (0 !== count($val['synonym'][$i])) {
                $word->addWord($gameID, $playerID, $val['synonym'][$i][0], 2);
            }
        }
        for ($i = 0; $i < count($val['synonym']); ++$i) {
            for ($j = 1; $j < count($val['synonym'][$i]); ++$j) {
                $word->addWord($gameID, $playerID, $val['synonym'][$i][$j], 3);
            }
        }
    } elseif ('0' === $wordNum) {   // ワード数が普通と設定された場合
        foreach ($val['NGword'] as $ng) {
            $word->addWord($gameID, $playerID, $ng, 2);
        }
        foreach ($val['synonym'] as $synonyms) {
            foreach ($synonyms as $synonym) {
                $word->addWord($gameID, $playerID, $synonym, 3);
            }
        }
    }
} elseif ('0' === $ngWord) {        // NGワードなしと設定された場合

    foreach ($val['NGword'] as $ng) {
        $word->addWord($gameID, $playerID, $ng, 3);
    }
    foreach ($val['synonym'] as $synonyms) {
        foreach ($synonyms as $synonym) {
            $word->addWord($gameID, $playerID, $synonym, 3);
        }
    }
}

// AIの文章を保存
$word->addWord($gameID, $playerID, $val['AI'], 1);

http_response_code(200);
