<?php

require __DIR__ . '/const.php';
require __DIR__ . '/vendor/autoload.php';

$jwt = $_COOKIE['token'];

echo json_encode($jwt);

$JWT = json_decode($jwt);

var_dump($JWT);
