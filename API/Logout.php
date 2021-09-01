<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../Const.php';

require_once '../vendor/autoload.php';


if (!empty($_COOKIE['token'])) {
    setcookie('token', '', (time() + -3600), '/', false, true);
    http_response_code(200);

    exit;
}
    http_response_code(401);
    header('Error: Login failed.');

    exit;
