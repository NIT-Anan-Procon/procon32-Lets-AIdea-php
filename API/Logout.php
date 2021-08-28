<?php

require __DIR__.'/const.php';

require __DIR__.'/vendor/autoload.php';


if (!empty($_COOKIE['token'])) {
    setcookie('token', '', (time() + -3600), '/', false, true);
    echo json_encode(['state' => 0]);
} else {
    echo json_encode(['state' => 1]);
}

http_response_code(200);
