<?php

require_once('../Const.php');
require('../vendor/autoload.php');

$access = access;
$secret = secret;
$callback = callback;
$application = application;

Unsplash\HttpClient::init([
	'applicationId'	=> "$access",
	'secret' => "$secret",
	'callbackUrl' => "$callback",
	'utmSource' => "$application"
]);

function getPhoto($word) {
	$filters = [
		'query'    => "$word",
		'w'        => 600,
		'h'        => 400
	];
	$photo = Unsplash\Photo::random($filters);

	$img = $photo->download();
	return $img;
}

$photo = getPhoto('snow');
var_dump($photo);

?>