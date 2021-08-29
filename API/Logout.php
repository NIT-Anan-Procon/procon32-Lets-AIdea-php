<?php

require_once '../Const.php';

require_once '../vendor/autoload.php';


if (!empty($_COOKIE['token'])) {
    setcookie('token', '', (time() + -3600), '/', false, true);
    echo json_encode(['state' => true]);
} else {
    echo json_encode(['state' => 'ログインしていません。']);
}

http_response_code(200);
