<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Picture.php';

require_once '../lib/Room.php';

require_once '../lib/Unsplash_API.php';

require_once '../lib/UserInfo.php';

require_once '../lib/Explanation.php';

$picture = new Picture();
$room = new Room();
$userInfo = new UserInfo();
$explanation = new Explanation();

if (false === $userInfo->CheckLogin()) {
    echo json_encode('ログインしていません');
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$gameID = $room->getGameInfo($userID)['gameID'];
$infos = $room->gameInfo($gameID);
$result = [];
$i = 0;
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

    //画像の説明文、NGワードを保存する
    $explanation->AddExplanation($gameID, $playerID, $result['sentence'], 1);
    $word = "";
    foreach($result['NGword'] as $NG) {
        $explanation->AddExplanation($gameID, $playerID, $NG, 2);
        $word += $NG;
    }

    $urls = getPhotos($word);
    foreach ($urls as $url) {
        while ($url === $photo) {
            $url = getPhoto($result['word']);
        }
        $picture->AddGameInfo($gameID, $playerID, $url, 0);
    }
    $result += [$i => $picture->GetGameInfo($playerID)];
    ++$i;
}

echo json_encode($result);
http_response_code(200);
