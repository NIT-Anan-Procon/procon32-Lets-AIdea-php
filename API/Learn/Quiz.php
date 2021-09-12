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

if ((int)($gameInfo['flag']) != 1) {
    header('You do not have the authority.');
    http_response_code(403);

    exit;
}

// 部屋の情報を取得
$gameID = $gameInfo['gameID'];
$playerID = $gameInfo['playerID'];
$gamemode = $gameInfo['gamemode'];

// 画像を取得し保存
$photo = InitialPhoto();
$picture->AddPicture($gameID, $playerID, $photo, 1);

// 部屋の設定情報を取得
$mode = substr($gamemode, 0, 1);
$ngWord = substr($gamemode, 1, 1);
$wordNum = substr($gamemode, 2, 1);

// PythonのAPIをたたく関数
function connect($photo, $subject, $synonyms)
{
    $data = json_encode(['url' => $photo, 'subject' => $subject, 'synonyms' => $synonyms]);
    $ch = curl_init('');    //''にpythonのAPIのurlを記述
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close();

    return json_decode($response);
}

// デモ用に値を代入
$val = [
    'subject' => 'Lamb',
    'NGword' => ['角', '雄羊', '岩', '上', '熊'],
    'synonym' => [
        ['街角', '曲がり角'],
        ['石ころ', 'ストーン'],
        ['上面', '天面'],
    ],
    'AI' => '角のある雄羊が岩の上に座っている。',
];

// NGワード・類義語をDBに保存
if ('1' === $ngWord) {      //NGワードありと設定された場合
    if ('1' === $wordNum) {     // ワード数が多いと設定された場合
        foreach ($val['NGword'] as $ng) {
            $word->addWord($gameID, $playerID, $ng, 2);
        }
        for ($i = 0; $i < count($val['synonym']); ++$i) {
            $word->addWord($gameID, $playerID, $val['synonym'][$i][0], 2);
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
$word->addWord($gameID,$playerID,$val['AI'],1);

http_response_code(200);