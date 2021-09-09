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
    $key_word = ['animal', 'scenery', 'people'];
    $key = array_rand($key_word, 1);
    $filters = [
        'query' => $key_word[$key],
    ];
    $photo = Unsplash\Photo::random($filters);
    // $photo = (array) (Unsplash\Photo::random($filters));
    // $array = array_combine([0, 1], $photo);
    // var_dump($array[1]['user']['name']);
    // var_dump($array[1]['links']['html']);
    // var_dump($array[1]['links']['download']);
    // var_dump($array[1]['user']['links']['html']);

    return $photo->download();
}

function getPhoto($search)
{
    $filters = [
        'query' => "{$search}",
        'count' => 3,
    ];

    $photo = (array) Unsplash\Photo::random($filters);
    $array = array_combine([0, 1], $photo);

    for ($i = 0; $i < 3; ++$i) {
        $urls[$i] = $array[1][$i]['links']['download'];
    }

    return $urls;
}

// var_dump(InitialPhoto());
// getPhotos("sea");

// $photo =
// getPhoto('bird');
// var_dump($photo);

// var_dump(getPhoto("Landscape"));
