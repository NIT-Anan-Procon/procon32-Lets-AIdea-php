<?php

require __DIR__ . '/const.php';
require __DIR__ . '/vendor/autoload.php';

$jwt = $_COOKIE['token'];
if($jwt != null) {
    echo "ログアウト";
}
echo json_encode($jwt);

$JWT = json_decode($jwt);

setcookie('token', '', (time() + -3600), '/');
