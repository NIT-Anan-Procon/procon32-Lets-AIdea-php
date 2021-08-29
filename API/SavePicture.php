<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Picture.php';

require_once '../lib/Room.php';

require_once '../lib/Unsplash_API.php';

require_once '../lib/UserInfo.php';

$picture = new Picture();
$room = new Room();
$userInfo = new userInfo();

if (false === $userInfo->CheckLogin()) {
    echo json_encode(['state' => 'ログインしていません']);
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$gameID = $room->getGameInfo($userID)['gameID'];
$infos = $room->gameInfo($gameID);
foreach ($infos as $info) {
    $playerID = $info['playerID'];

    $photo = InitialPhoto();
    $picture->AddGameInfo($gameID, $playerID, $photo, 1);

    $val = [
        'lang' => 0,
        'url' => $photo,
    ];
    $data = json_encode($val);

    //pythonと通信
    $ch = curl_init('');    //''にpythonのAPIのurlを記述
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close();
    $result = json_decode($response);

    $urls = getPhotos($result['word']);
    foreach ($urls as $url) {
        while ($url === $photo) {
            $url = getPhoto($result['word']);
        }
        $picture->AddGameInfo($gameID, $playerID, $url, 0);
    }
}

$result = $picture->GetGameInfo($playerID);

echo json_encode($result);
http_response_code(200);
