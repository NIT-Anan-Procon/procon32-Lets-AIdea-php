<?php
/*
ini_set('display_errors', 1);

require_once '../../lib/Point.php';

require_once '../../lib/Room.php';

require_once '../../lib/UserInfo.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
header('Content-Type: application/json; charset=utf-8');
$point = new Point();
$room = new Room();
$userInfo = new UserInfo();
$user['userInfo'] = $userInfo->checkLogin();
if (false === $user['userInfo']) {
    header('Error:Login failed.');
    http_response_code(403);

    exit;
}
$user['room'] = $room->getGameInfo($user['userInfo']['userID']);
if (false === $user['room']) {
    header('Error:The user is not in the room.');
    http_response_code(403);

    exit;
}
if (2 === (int) $user['room']['status']) {
    header('Error:The user has already voted.');
    http_response_code(403);

    exit;
}
if (isset($_POST['playerID'])) {
    $playerID = (int) $_POST['playerID'];
    $result = $point->addPoint($user['room']['gameID'], $playerID, 1, 2);
    if (false === $result) {
        http_response_code(400);
    } else {
        $room->vote($user['room']['gameID'], $user['room']['playerID']);
        http_response_code(200);
    }
} else {
    header('Error:The requested value is different from the specified format.');
    http_response_code(401);
}
*/
http_response_code(200);
