<?php

require_once('../../info.php');
require('vendor/autoload.php');

$access = access;
$secret = secret;
$callback = callback;
$application = application;

Unsplash\HttpClient::init([
	'applicationId'	=> "$access",
	'secret'	=> "$secret",
	'callbackUrl'	=> "$callback",
	'utmSource' => "$application"
]);

?>