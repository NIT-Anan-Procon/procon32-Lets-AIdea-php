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

function getPhoto($word) {
	$filter = [
		'query' => 'sea',
		'w'		=> '800',
		'h' 	=> '600'
	];
	$photo = Unsplash\Photo::random($filter);
	$img = $photo->download();
	return $img;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		img {
			width: 800px;
			height: 600px;
		}
	</style>
</head>
<body>
	<img src="<?php echo $img; ?>">
</body>
</html>