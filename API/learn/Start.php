<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../../lib/Picture.php';

require_once '../../lib/Room.php';

require_once '../../lib/Unsplash_API.php';

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
if (false === $response) {
    header('Error: The user is not in the room.');
    http_response_code(403);

    exit;
}

$gameID = $gameInfo['gameID'];
$playerID = $gameInfo['playerID'];
$photo = InitialPhoto();
$picture->AddGameInfo($gameID, $playerID, $photo, 1);

$data = json_encode(array('url' => $photo));
$ch = curl_init('');    //''にpythonのAPIのurlを記述
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close();
$val = json_decode($response);

$NG = $val['NGword'];
$synoyms = $val['synoyms'];
$num = count($NG);

$word->AddWord($gameID, $playerID, $val['sentence'], 1);

for($i = 0; $i < $num; $i++) {
    $word->AddWord($gameID, $playerID, $NG[$i], 2);
}

$num = count($synoyms);

for($i = 0; $i < $num; $i++) {
    $word->AddWord($gameID, $playerID, $synoyms[$i], 3);
}

$result = array(
    'synoyms'       => $synoyms,
    'ng'            => $NG,
    'AI'            => $val['sentence'],
    'pictureURL'    => $photo,
    'gamemode'      => $gameInfo['gamemode']
);

echo json_encode($result);
http_response_code(200);