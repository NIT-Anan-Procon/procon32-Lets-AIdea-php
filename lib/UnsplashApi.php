<?php

ini_set('display_errors', 1);

require_once __DIR__.'/../Const.php';

require_once __DIR__.'/../vendor/autoload.php';

class UnsplashApi
{
    private $access = access;
    private $secret = secret;
    private $callback = callback;
    private $application = application;
    private $access_key = access_key;
    private $secret_key = secret_key;

    public function init($access, $secret)
    {
        Unsplash\HttpClient::init([
            'applicationId' => "{$access}",
            'secret' => "{$secret}",
            'callbackUrl' => "{$this->callback}",
            'utmSource' => "{$this->application}",
        ]);
    }

    public function InitialPhoto()
    {
        $this->init($this->access, $this->secret);
        $key_word = ['animal'];
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
            $this->init($this->access_key, $this->secret_key);
            $photo = Unsplash\Photo::random($filters);
            $photo = (array) (Unsplash\Photo::random($filters));
            $array = array_combine([0, 1], $photo);

            return $array[1]['urls']['raw'];
        }
    }

    public function getPhoto($word)
    {
        $this->init($this->access, $this->secret);
        $filters = [
            'query' => $word,
        ];

        try {
            $photo = Unsplash\Photo::random($filters);
            $photo = (array) (Unsplash\Photo::random($filters));
            $array = array_combine([0, 1], $photo);

            return $array[1]['urls']['raw'];
        } catch (Unsplash\Exception $e) {
            $this->init($this->access_key, $this->secret_key);
            $photo = Unsplash\Photo::random($filters);
            $photo = (array) (Unsplash\Photo::random($filters));
            $array = array_combine([0, 1], $photo);

            return $array[1]['urls']['raw'];
        }
    }

    public function getPhotos($search)
    {
        $this->init($this->access, $this->secret);
        $filters = [
            'query' => "{$search}",
            'count' => 3,
        ];

        try {
            $photo = (array) Unsplash\Photo::random($filters);
            $array = array_combine([0, 1], $photo);
        } catch (Unsplash\Exception $e) {
            $this->init($this->access_key, $this->secret_key);
            $photo = (array) Unsplash\Photo::random($filters);
            $array = array_combine([0, 1], $photo);
        }

        for ($i = 0; $i < 3; ++$i) {
            $urls[$i] = $array[1][$i]['urls']['raw'];
        }

        return $urls;
    }
}
