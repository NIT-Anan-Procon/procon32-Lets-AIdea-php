<?php

require_once('../../info.php');

$access = access;
$secret = secret;
$callback = callback;
$application = application;

$json = json_decode('composer.json');

Unsplash\HttpClient::init([
	'applicationId'	=> "$access",
	'secret'	=> "$secret",
	'callbackUrl'	=> "$callback",
	'utmSource' => "$application"
]);

?>