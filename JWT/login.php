<?php

//他ファイルから実行された時に相対パスではエラーが起きるため
require __DIR__ . '/const.php';
require __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

//strtoupper・・・文字列を大文字にする
//POST通信であれば
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {

    // JSON 文字列取得
    $inputString = file_get_contents('php://input');

    //@json_decode()・・・JSON文字列をデコードする
    $input = @json_decode($inputString, true);

    if (is_array($input)) {

        $username = $input['username'];
        $password = $input['password'];
        
        $ok = ($username == 'test' && $password == 'test'); // username = test, password = test で認証 OK とする (仮)

        if ($ok) {
            $payload = array(
                'iss' => JWT_ISSUER,
                'exp' => time() + JWT_EXPIRES,
                'username' => $username,
            );
            $jwt = JWT::encode($payload, JWT_KEY, JWT_ALG);
    
            header('Content-Type: application/json');//レスポンスする形式はJSONファイル
            header('Access-Control-Allow-Origin: *'); // CORS
            echo json_encode(array('token' => $jwt)); // token を返却
            return;
        }
    }
    // JSON 取得失敗、認証に失敗した場合は 401
    http_response_code(401);
}
