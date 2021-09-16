<?php

ini_set('display_errors', 1);

require_once '../../lib/Room.php';

require_once '../../lib/UserInfo.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
header('Content-Type: application/json; charset=utf-8');
$room = new Room();
$userInfo = new UserInfo();

$runtime = new \parallel\Runtime();

if (false === $userInfo->CheckLogin()) {
    header('Error: Login failed.');
    http_response_code(403);

    exit;
}

// ユーザーが他の部屋に入っていないかチェック
$userID = $userInfo->CheckLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);

if (false !== $gameInfo) {
    header('Error: The user is already in the other room.');
    http_response_code(403);

    exit;
}

// 部屋を追加

if (isset($_POST['gamemode'])) {
    $runtime->run(function () {
        $url = 'http://localhost/~kinoshita/procon32_Lets_AIdea_php/API/CreateQuiz.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_exec($ch);
        curl_close($ch);
    });
    $roomID = $room->CreateRoomID();
    $gameID = $room->GetGameID() + 1;
    $playerID = 1;
    $gamemode = (string) $_POST['gamemode'];
    $room->AddRoom($gameID, $playerID, $userID, $roomID, 1, $gamemode);
    $playerInfo = $room->PlayerInfo($gameID, $playerID);
    $user = $userInfo->GetUserInfo($userID);
    $result = [
        'playerID' => $playerInfo['playerID'],
        'name' => $user['name'],
        'icon' => $user['icon'],
        'badge' => $user['badge'],
        'flag' => $playerInfo['flag'],
        'gamemode' => $gamemode,
    ];
    echo json_encode($result);
    http_response_code(200);

    exit;
}

header('Error: The requested value is different from the specified format.');
http_response_code(401);
