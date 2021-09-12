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
    try {
        $photo = Unsplash\Photo::random($filters);
        $photo = (array) (Unsplash\Photo::random($filters));
        $array = array_combine([0, 1], $photo);
    
        return $array[1]['urls']['raw'];
    } catch (Unsplash\Exception $e) {
        $access_key = access_key;
        $secret_key = secret_key;
        $callback = callback;
        $application = application;
        Unsplash\HttpClient::init([
            'applicationId' => "{$access_key}",
            'secret' => "{$secret_key}",
            'callbackUrl' => "{$callback}",
            'utmSource' => "{$application}",
        ]);
        $photo = Unsplash\Photo::random($filters);
        $photo = (array) (Unsplash\Photo::random($filters));
        $array = array_combine([0, 1], $photo);
    
        return $array[1]['urls']['raw'];
    }
    
}

function getPhoto($word)
{
    $filters = [
        'query' => $word,
    ];
    try {
        $photo = Unsplash\Photo::random($filters);
        $photo = (array) (Unsplash\Photo::random($filters));
        $array = array_combine([0, 1], $photo);
    
        return $array[1]['urls']['raw'];
    } catch (Unsplash\Exception $e) {
        $access_key = access_key;
        $secret_key = secret_key;
        $callback = callback;
        $application = application;
        Unsplash\HttpClient::init([
            'applicationId' => "{$access_key}",
            'secret' => "{$secret_key}",
            'callbackUrl' => "{$callback}",
            'utmSource' => "{$application}",
        ]);
        $photo = Unsplash\Photo::random($filters);
        $photo = (array) (Unsplash\Photo::random($filters));
        $array = array_combine([0, 1], $photo);
    
        return $array[1]['urls']['raw'];
    }
}

function getPhotos($search)
{
    $filters = [
        'query' => "{$search}",
        'count' => 3,
    ];

    try {
        $photo = (array) Unsplash\Photo::random($filters);
        $array = array_combine([0, 1], $photo);
    } catch (Unsplash\Exception $e) {
        $access_key = access_key;
        $secret_key = secret_key;
        $callback = callback;
        $application = application;
        Unsplash\HttpClient::init([
            'applicationId' => "{$access_key}",
            'secret' => "{$secret_key}",
            'callbackUrl' => "{$callback}",
            'utmSource' => "{$application}",
        ]);
        $photo = (array) Unsplash\Photo::random($filters);
        $array = array_combine([0, 1], $photo);
    }

    for ($i = 0; $i < 3; ++$i) {
        $urls[$i] = $array[1][$i]['urls']['raw'];
    }

    return $urls;
}
