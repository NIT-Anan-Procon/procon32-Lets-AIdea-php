<?php

ini_set('display_errors', 1);

require_once '../../lib/UserInfo.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
header('Content-Type: application/json; charset=utf-8');

$userInfo = new UserInfo();

if (false === $userInfo->checkLogin()) {
    header('Error: Login failed.');
    http_response_code(403);

    exit;
}

http_response_code(200);
