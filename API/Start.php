<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Picture.php';

require_once '../lib/Room.php';

require_once '../lib/UnsplashApi.php';

require_once '../lib/UserInfo.php';

require_once '../lib/Word.php';

require_once '../Develop.php';

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
if (false === $gameInfo) {
    header('Error: The user is not in the room.');
    http_response_code(403);

    exit;
}

$gameID = $gameInfo['gameID'];
$gameID = $gameInfo['gameID'];
$playerID = $gameInfo['playerID'];
$gamemode = $gameInfo['gamemode'];
// $photo = InitialPhoto();
// $photos[] = [
//     'url' => $photo,
//     'answer' => 1
// ];
$photo = 'https://images.unsplash.com/photo-1566487097168-e91a4f38bee2?ixlib=rb-1.2.1&q=85&fm=jpg&crop=entropy&cs=srgb';
$mode = substr($gamemode, 1, 1);

if (ReleaseMode) {
    if ('1' === $mode) {
        $value = 0;
    } elseif ('0' === $mode) {
        $value = 1;
    }

    $data = json_encode(['url' => $photo, 'mode' => $value]);
    $ch = curl_init('');    //''にpythonのAPIのurlを記述
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close();
    $val = json_decode($response);

    $sentence = $val['sentence'];
}

$sentence = '動物園の囲いの中を歩くパンダの熊。';
// $word->AddWord($gameID, $playerID, $sentence, 1);

$val['NGword'] = ['動物園', '囲い', '中', 'パンダ', '熊'];
$val['synonyms'] = ['わたし', '拙者', '自分'];

$mode = substr($gamemode, 1, 1);
if ('1' === $mode) {
    foreach ($val['NGword'] as $ng) {
        // $word->AddWord($gameID, $playerID, $ng, 2);
    }
    $mode = substr($gamemode, 2, 1);
    if ('1' === $mode) {
        foreach ($val['synonyms'] as $synonyms) {
            // $word->AddWord($gameID, $playerID, $synonyms, 2);
        }
    }
}

$mode = substr($gamemode, 0, 1);
$val['subject'] = 'AI';
if ('1' === $mode) {
    // $imgs = getPhotos($val['subject']);
    $imgs = [
        'https://unsplash.com/photos/JVPtzP6lQtU/download',
        'https://unsplash.com/photos/0afuId7VUjw/download',
        'https://unsplash.com/photos/SBnxlHHrEwk/download',
    ];
// foreach ($imgs as $img) {
    //     $urls = $picture->GetPicture($gameID, $playerID);
    //     for ($i = 0; $i < count($urls); ++$i) {
    //         while ($img === $urls[$i]['pictureURL']) {
    //             $img = getPhoto($val['subject']);
    //         }
    //     }
    //     $photos[] = [
                // 'url' => $img,
                // 'answer' => 0
            // ];
            // shuffle($photos);
    // }
    // foreach ($images as $image) {
    //      $picture->AddPicture($gameID, $playerID, $image['url'], $image['answer']);
    // }
} else {
    foreach ($val['synonyms'] as $synonyms) {
        // $word->AddWord($gameID, $playerID, $synonyms, 3);
    }
}

$ng = $word->getWord($gameID, $playerID, 2);
$synonyms = $word->getWord($gameID, $playerID, 3);

$result = [
    'synonyms' => $synonyms,
    'ng' => $ng,
    'AI' => $sentence,
    'pictureURL' => $photo,
    'gamemode' => $gamemode,
];

echo json_encode($result);
http_response_code(200);
