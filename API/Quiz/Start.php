<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../../lib/Picture.php';

require_once '../../lib/Room.php';

require_once '../../lib/UnsplashApi.php';

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
if (false === $gameInfo) {
    header('Error: The user is not in the room.');
    http_response_code(403);

    exit;
}

$gameID = $gameInfo['gameID'];
$gameID = $gameInfo['gameID'];
$playerID = $gameInfo['playerID'];
$gamemode = $gameInfo['gamemode'];
$photo = InitialPhoto();
$picture->AddPicture($gameID, $playerID, $photo, 1);

// $data = json_encode(['url' => $photo]);
// $ch = curl_init('');    //''にpythonのAPIのurlを記述
// curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $response = curl_exec($ch);
// curl_close();
// $val = json_decode($response);

// $sentence = $val['sentence'];
$sentence = '私はAIです。';
$word->AddWord($gameID, $playerID, $sentence, 1);

$val['NGword'] = ['私', 'AI'];
foreach ($val['NGword'] as $ng) {
    $word->AddWord($gameID, $playerID, $ng, 2);
}

$val['synonyms'] = ['わたし', '拙者', '自分'];
foreach ($val['synonyms'] as $synonyms) {
    $word->AddWord($gameID, $playerID, $synonyms, 3);
}

$val['subject'] = 'AI';
$imgs = getPhotos($val['subject']);
foreach ($imgs as $img) {
    $urls = $picture->GetPicture($gameID, $playerID);
    for ($i = 0; $i < count($urls); ++$i) {
        while ($img === $urls[$i]['pictureURL']) {
            $img = getPhoto($val['subject']);
        }
    }
    $picture->AddPicture($gameID, $playerID, $img, 0);
}

$photos = $picture->GetPicture($gameID, $playerID);

$result = [
    'synonyms' => $synonyms,
    'ng' => $val['NGword'],
    'AI' => $sentence,
    'pictureURL' => $photo,
    'gamemode' => $gamemode,
];

echo json_encode($result);
http_response_code(200);
