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

// 正解画像の取得
$photo = $unsplash->InitialPhoto();
$photos[] = [
    'url' => $photo,
    'answer' => 1,
];

// 部屋の設定情報を取得
$mode = substr($gamemode, 0, 1);
$ngWord = substr($gamemode, 1, 1);
$wordNum = substr($gamemode, 2, 1);

// PythonのAPIをたたく関数
function connect($photo, $subject, $synonyms)
{
    $data = json_encode(['url' => $photo, 'subject' => $subject, 'synonym' => $synonyms]);
    $url = 'http://localhost:5000/test';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $val = (array)(json_decode($response));

    return $val;
}

// NGワード・類義語をDBに保存
if ('1' === $wordNum) {     // ワード数が多いと設定された場合
    // Pythonと通信
    $val = connect($photo,1,1);

    // NGワードの設定
    foreach ($val['NGword'] as $ng) {
        $word->addWord($gameID, $playerID, $ng, 2);
    }
    foreach ($val['synonym'] as $synonyms) {
        if ($synonyms != null) {
            $word->addWord($gameID, $playerID, $synonyms[0], 2);
        }
    }
} elseif ('0' === $wordNum) {   //ワード数が普通と設定された場合
    // Pythonと通信
    $val = connect($photo,1,0);

    // NGワードの設定
    foreach ($val['NGword'] as $ng) {
        $word->addWord($gameID, $playerID, $ng, 2);
    }
}

// 同じ写真が含まれないように類似画像を取得
$imgs = $unsplash->getPhotos($val['subject']);
foreach ($imgs as $img) {
    $urls = $picture->getPictures($gameID, $playerID);
    foreach ($urls as $url) {
        while ($img === $url['pictureURL']) {
            $img = $unsplash->getPhoto($val['subject']);
        }
    }
    $photos[] = [
        'url' => $img,
        'answer' => 0,
    ];
}

// 写真をランダムに並び替え
shuffle($photos);

// 写真を保存
foreach ($photos as $image) {
    $picture->AddPicture($gameID, $playerID, $image['url'], $image['answer']);
}

// AIの文章を保存
$word->addWord($gameID, $playerID, $val['AI'], 1);

// 値を返す
$sentence = $word->getWord($gameID, $playerID, 1);
$ngWords = $word->getWord($gameID, $playerID, 2);
$synonyms = $word->getWord($gameID, $playerID, 3);

$result = [
    'synonyms' => $synonyms,
    'ng' => $ngWords,
    'AI' => $sentence,
    'pictureURL' => $photo,
    'gamemode' => $gamemode,
];

echo json_encode($result);
http_response_code(200);
