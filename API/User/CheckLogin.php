<?php

require_once '../../lib/UserInfo.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
$userInfo = new UserInfo();

if (false === $userInfo->checkLogin()) {
    header('Error:Login failed.');
    http_response_code(403);
} else {
    http_response_code(200);
}
