<?php

ini_set('display_errors', 1);

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
    $photo = (array) (Unsplash\Photo::random($filters));
    $array = array_combine([0, 1], $photo);
    var_dump($array[1]['user']['name']);
    var_dump($array[1]['links']['html']);
    var_dump($array[1]['links']['download']);
    var_dump($array[1]['user']['links']['html']);

    // return $photo->download();
}

function getPhotos($search)
{
    $photos = Unsplash\Search::photos($search, 1, 4);
    // $urls = [];
    // for ($i = 0; $i < 3; ++$i) {
    //     $urls += [$i => $photos[$i]['urls']['raw']];
    // }

    // var_dump($urls);
    // return $urls;
}

function getPhoto($search)
{
    $filters = [
        'query' => "{$search}",
    ];

    $photo = Unsplash\Photo::random($filters);

    return $photo->download();
}

InitialPhoto();
// getPhotos("sea");

// $photo = getPhotos('bird');
// var_dump($photo);
