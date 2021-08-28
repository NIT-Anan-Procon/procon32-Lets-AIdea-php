<?php

header("Access-Control-Allow-Origin:http://localhost");
header("Content-Type: application/json; charset=utf-8");

require_once('../picture/picture.php');
require_once('../unsplash_API/unsplash_API.php');

$picture = new Picture();

if (filter_input(INPUT_POST, 'gameID') && filter_input(INPUT_POST, 'playerID') && filter_input(INPUT_POST, 'answer') && filter_input(INPUT_POST, 'word')) {
    $gameID = $_POST['gameID'];
    $playerID = $_POST['playerID'];
    $answer = $_POST['answer'];
    $word = $_POST['word'];
    $flag = 0;

    for ($i = 0; $i < 4; $i++) {
        $PictureURL = getPhoto($word);
        $url = $picture->GetGameInfo($playerID);
        for ($j = 0; $j < count($url); $j++) {
            if ($picture === $url[$j]['pictureURL']) {
                $flag = 1;
            }
        }
        if ($flag === 0) {
            $picture->AddGameInfo($gameID, $playerID, $PictureURL, $answer);
        } else {
            $i -= 1;
        }
        $flag = 0;
    }

    $result = $picture->GetGameInfo($playerID);
} else {
    $result = false;
    echo json_encode($result);
    exit;
}

echo json_encode($result);
http_response_code(200);
