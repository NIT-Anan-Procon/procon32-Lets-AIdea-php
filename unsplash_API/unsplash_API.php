<?php

require_once('../../info.php');

$access = access;
$secret = secret;
$callback = callback;
$application = application;

$json = file_get_contents("composer.json");

Unsplash\HttpClient::init([
	'applicationId'	=> "$access",
	'secret'	=> "$secret",
	'callbackUrl'	=> "$callback",
	'utmSource' => "$application"
]);

?>