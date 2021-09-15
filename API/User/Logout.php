<?php

require_once '../../Const.php';
require_once '../../Develop.php';
require_once '../../vendor/autoload.php';
header('Access-Control-Allow-Origin:'.URL);
header('Access-Control-Allow-Credentials:true');
setcookie('token', '', (time() + -3600), '/', false, true);
http_response_code(200);
