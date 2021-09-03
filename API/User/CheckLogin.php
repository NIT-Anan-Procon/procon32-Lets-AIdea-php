<?php

header('Access-Control-Allow-Origin: *');

require_once '../../lib/UserInfo.php';

$userInfo = new UserInfo();

if (false === $userInfo->CheckLogin()) {
    header('Error:Login failed.');
    http_response_code(403);
} else {
    http_response_code(200);
}