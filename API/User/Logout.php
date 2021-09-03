<?php

header('Access-Control-Allow-Origin:*');

require_once '../../Const.php';

require_once '../../vendor/autoload.php';

setcookie('token', '', (time() + -3600), '/', false, true);
http_response_code(200);
