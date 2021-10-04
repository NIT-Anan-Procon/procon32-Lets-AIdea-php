<?php

ini_set('display_errors', 1);

require_once '../../lib/Word.php';

require_once '../../lib/Picture.php';

require_once '../../lib/Stock.php';

require_once '../../lib/Room.php';

require_once '../../lib/UserInfo.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
header('Content-Type: application/json; charset=utf-8');
$word = new Word();
$picture = new Picture();
$stock = new Stock();
$room = new Room();
$userInfo = new UserInfo();

if (false === $userInfo->checkLogin()) {
    header('Error:Login failed.');
    http_response_code(403);

    exit;
}
$userID = $userInfo->checkLogin()['userID'];
$user['room'] = $room->getGameInfo($userID);
if (false === $user['room']) {
    header('Error:The user is not in the room.');
    http_response_code(403);

    exit;
}
$mode = (int) substr($user['room']['gamemode'], 0, 1);
$ngWord = (int) substr($user['room']['gamemode'], 1, 1);
$wordNum = (int) substr($user['room']['gamemode'], 2, 1);
if (0 === (int) $user['room']['flag'] && 0 === $mode) {
    http_response_code(200);

    exit;
}
$gameID = $user['room']['gameID'];
if (0 === $mode) {
    $playerID = 0;
} else {
    $playerID = $user['room']['playerID'];
}
$gameInfo = $room->gameInfo($gameID);
do {
    $stockInfo = $stock->getStock();
    if (false === $stockInfo) {
        header('Error:We have run out of stock. Please create an quiz.');
        http_response_code(403);

        exit;
    }
    $stockInfo['pictureURL'] = explode(',', $stockInfo['pictureURL']);
    if (0 === $mode) {
        break;
    }
} while ($picture->checkPlayerPicture($gameID, $stockInfo['pictureURL'][0]));
if (0 === $mode) {
    $picture->addPicture($gameID, null, $stockInfo['pictureURL'][0], 2);
} else {
    $r = random_int(1, 4);
    for ($i = 1; $i < count($stockInfo['pictureURL']); ++$i) {
        if ($i === $r) {
            $picture->addPicture($gameID, $playerID, $stockInfo['pictureURL'][0], 1);
        }
        $picture->addPicture($gameID, $playerID, $stockInfo['pictureURL'][$i], 0);
    }
    if (4 === $r) {
        $picture->addPicture($gameID, $playerID, $stockInfo['pictureURL'][0], 1);
    }
}
$stockInfo['ng'] = explode(',', $stockInfo['ng']);
$stockInfo['synonym'] = explode(':', $stockInfo['synonym']);
for ($i = 0; $i < count($stockInfo['synonym']); ++$i) {
    $stockInfo['synonym'][$i] = explode(',', $stockInfo['synonym'][$i]);
}
$word->addWord($gameID, $playerID, $stockInfo['explanation'], 1);
if (1 === $ngWord) {
    if (1 === $wordNum) {
        for ($i = 0; $i < count($stockInfo['synonym']); ++$i) {
            if (isset($stockInfo['synonym'][$i][0])) {
                $stockInfo['ng'][] = $stockInfo['synonym'][$i][0];
            }
            unset($stockInfo['synonym'][$i][0]);
        }
    }
    foreach ($stockInfo['ng'] as $value) {
        $word->addWord($gameID, $playerID, $value, 2);
    }
    unset($value);
}
if (0 === $mode) {
    if (0 === $ngWord) {
        array_unshift($stockInfo['synonym'], $stockInfo['ng']);
    }
    foreach ($stockInfo['synonym'] as $key) {
        foreach ($key as $value) {
            if (isset($value)) {
                $word->addWord($gameID, $playerID, $value, 3);
            }
        }
        unset($value);
    }
    unset($key);
}
http_response_code(200);
