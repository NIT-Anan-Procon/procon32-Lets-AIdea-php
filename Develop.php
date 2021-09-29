<?php

define('ReleaseMode', false);
if (isset($_SERVER['HTTP_ORIGIN'])) {
    define('URL', $_SERVER['HTTP_ORIGIN']);
} else {
    define('URL', 'http://localhost');
}