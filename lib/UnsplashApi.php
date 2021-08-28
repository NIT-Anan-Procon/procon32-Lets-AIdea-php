<?php

require_once '../Const.php';

require '../vendor/autoload.php';

$access = access;
$secret = secret;
$callback = callback;
$application = application;

Unsplash\HttpClient::init([
    'applicationId' => "{$access}",
    'secret' => "{$secret}",

    'callbackUrl' => "{$callback}",
    'utmSource' => "{$application}",
]);

function InitialPhoto()
{
    $photo = Unsplash\Photo::random($filters);

    return $photo->download();
}

function getPhotos($search)
{
    $photos = Unsplash\Search::photos($search, 1, 4);
    $urls = [];
    for ($i = 0; $i < 3; ++$i) {
        $urls += [$i => $photos[$i]['urls']['raw']];
    }

    return $urls;
}
