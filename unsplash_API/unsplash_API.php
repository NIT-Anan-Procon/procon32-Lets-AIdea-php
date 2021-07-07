<?php

require_once('../../info.php');

Unsplash\HttpClient::init([
	'applicationId'	=> 'YOUR ACCESS KEY',
	'secret'	=> 'YOUR APPLICATION SECRET',
	'callbackUrl'	=> 'https://your-application.com/oauth/callback',
	'utmSource' => 'NAME OF YOUR APPLICATION'
]);

?>