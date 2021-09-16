<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/UnsplashApi.php';

require_once '../lib/Stock.php';

$stock = new Stock();
$unsplash = new UnsplashApi();

// 正解画像の取得
$photo = $unsplash->InitialPhoto();
$photos[] = $photo;

// PythonのAPIをたたく
$data = json_encode(['url' => $photo, 'subject' => 1, 'synonym' => 1]);
$url = 'http://localhost:5000/test';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$val = (array) (json_decode($response));

$ng = implode(',',$val['NGword']);

foreach ($val['synonym'] as $word) {
    if ($word != null && count($word) != 0) {
        $synonym[] = implode(',',$word);
    }
}
$synonyms = implode(':',$synonym);

// 同じ写真が含まれないように類似画像を取得
$imgs = $unsplash->getPhotos($val['subject']);
foreach ($imgs as $img) {
    $urls = $photos;
    foreach ($photos as $url) {
        while ($img === $url) {
            $img = $unsplash->getPhoto($val['subject']);
        }
    }
    $photos[] = $img;
}
$pictureURL = implode(',',$photos);

$stock->addStock($val['AI'], $ng, $synonyms, $pictureURL);
