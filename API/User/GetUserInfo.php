<?php

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../../lib/UserInfo.php';

$userInfo = new UserInfo();

if (false === $userInfo->CheckLogin()) {
    header('Error:Login failed.');
    http_response_code(403);

    exit;
}
$result = $userInfo->CheckLogin();
if (false === $result['name']) {
    header('Error:User does not exist.');
    http_response_code(403);
    exit;
}
unset($result['userID']);
unset($result['password']);
echo json_encode($result);
http_response_code(200);
