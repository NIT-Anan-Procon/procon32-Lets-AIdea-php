<?php

require_once __DIR__.'/../Const.php';

require_once __DIR__.'/../vendor/autoload.php';

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
    $filters = [];
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

function getPhoto($search)
{
    $filters = [
        'query' => "{$search}",
    ];

    $photo = Unsplash\Photo::random($filters);

    return $photo->download();
}
