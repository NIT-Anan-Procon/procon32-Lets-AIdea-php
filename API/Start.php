<?php

ini_set('display_errors', 1);
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=utf-8');

require_once '../lib/Picture.php';

require_once '../lib/Room.php';

require_once '../lib/UnsplashApi.php';

require_once '../lib/UserInfo.php';

require_once '../lib/Word.php';

require_once '../Develop.php';

$picture = new Picture();
$room = new Room();
$userInfo = new UserInfo();
$word = new Word();

if (false === $userInfo->CheckLogin()) {
    header('Error: Login failed.');
    http_response_code(403);

    exit;
}

$userID = $userInfo->CheckLogin()['userID'];
$gameInfo = $room->getGameInfo($userID);
if (false === $gameInfo) {
    header('Error: The user is not in the room.');
    http_response_code(403);

    exit;
}

$gameID = $gameInfo['gameID'];
$playerID = $gameInfo['playerID'];
$gamemode = $gameInfo['gamemode'];
$photo = InitialPhoto();
$photos[] = [
    'url' => $photo,
    'answer' => 1,
];
$mode = substr($gamemode, 0, 1);
$ngWord = substr($gamemode, 1, 1);
$wordNum = substr($gamemode, 2, 1);

function connect($photo, $subject, $ng, $synonyms)
{
    $data = json_encode(['url' => $photo, 'subject' => $subject, 'ng' => $ng, 'synonyms' => $synonyms]);
    $ch = curl_init('');    //''にpythonのAPIのurlを記述
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close();

    return json_decode($response);
}

$val = [
    'subject' => 'Lamb',
    'ng' => ['角', '雄羊', '岩', '上', '熊'],
    'synonyms' => [
        ['街角', '曲がり角'], ['石ころ', 'ストーン'], ['上面', '天面'],
    ],
    'sentence' => '角のある雄羊が岩の上に座っている。',
];
if ('1' === $mode) {
    if ('1' === $wordNum) {
        // $val = connect($photo,1,1,1);
        foreach ($val['ng'] as $ng) {
            $word->addWord($gameID, $playerID, $ng, 2);
        }
        foreach ($val['synonyms'] as $synonyms) {
            $word->addWord($gameID, $playerID, $synonyms[0], 2);
        }
    } elseif ('0' === $wordNum) {
        // $val = connect($photo,1,1,0);
        foreach ($val['ng'] as $ng) {
            $word->addWord($gameID, $playerID, $ng, 2);
        }
    }
    $imgs = getPhotos($val['subject']);
    foreach ($imgs as $img) {
        $urls = $picture->getPictures($gameID, $playerID);
        foreach ($urls as $url) {
            while ($img === $url['pictureURL']) {
                $img = getPhoto($val['subject']);
            }
        }
        $photos[] = [
            'url' => $img,
            'answer' => 0,
        ];
    }
    shuffle($photos);
    foreach ($photos as $image) {
        $picture->AddPicture($gameID, $playerID, $image['url'], $image['answer']);
    }
} elseif ('0' === $mode) {
    // $val = connect($photo,0,1,1);
    if ('1' === $ngWord) {
        if ('1' === $wordNum) {
            foreach ($val['ng'] as $ng) {
                $word->addWord($gameID, $playerID, $ng, 2);
            }
            for ($i = 0; $i < count($val['synonyms']); ++$i) {
                $word->addWord($gameID, $playerID, $val['synonyms'][$i][0], 2);
            }
            for ($i = 0; $i < count($val['synonyms']); ++$i) {
                for ($j = 1; $j < count($val['synonyms'][$i]); ++$j) {
                    $word->addWord($gameID, $playerID, $val['synonyms'][$i][$j], 3);
                }
            }
        } elseif ('0' === $wordNum) {
            foreach ($val['ng'] as $ng) {
                $word->addWord($gameID, $playerID, $ng, 2);
            }
            foreach ($val['synonyms'] as $synonyms) {
                foreach ($synonyms as $synonym) {
                    $word->addWord($gameID, $playerID, $synonym, 3);
                }
            }
        }
    } elseif ('0' === $ngWord) {
        foreach ($val['ng'] as $ng) {
            $word->addWord($gameID, $playerID, $ng, 3);
        }
        foreach ($val['synonyms'] as $synonyms) {
            foreach ($synonyms as $synonym) {
                $word->addWord($gameID, $playerID, $synonym, 3);
            }
        }
    }
}
$word->addWord($gameID, $playerID, $val['sentence'], 1);

$sentence = $word->getWord($gameID, $playerID, 1);
$ngWords = $word->getWord($gameID, $playerID, 2);
$synonyms = $word->getWord($gameID, $playerID, 3);

$result = [
    'synonyms' => $synonyms,
    'ng' => $ngWords,
    'AI' => $sentence,
    'pictureURL' => $photo,
    'gamemode' => $gamemode,
];

echo json_encode($result);
http_response_code(200);
