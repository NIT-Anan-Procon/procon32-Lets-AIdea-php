<?php

class DB {
    function dbConnect() {      //DB接続
        $dsn = 'mysql:host=localhost;dbname=db_name;charset=utf8';  /* DBに接続するために必要な情報 */
        $user = 'user_name';                                            /* DBのデータ、構造の権限を持ったユーザー名 */
        $pass = 'password';                                           /* ユーザー名に対応したパスワード */
        try {
            $dbh = new PDO($dsn,$user,$pass,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,           /* PDOクラスのオプションを設定 */ 
            ]);
        } catch(PDOException $e) {
            echo '接続失敗'. $e->getMessage();                          /* 接続に失敗したことを示す */
            exit();                                                     /* プログラムを終了する */
        };
        return $dbh;
    }

    function CreateRoomNumber() {
        $room_number = random_int(0, 99999999);         /* 0～99999999までの数値をランダムに生成 */
        $code = (int)(sprintf('%08d', $room_number));   /* $room_numberを8桁のint型に変更 */
        if (empty($this->room_info($code))) {                  /* もし同じ部屋番号がなければ$codeを返す */
            return $code;
        } else {                                        /* もし同じ部屋番号があれば部屋番号を作り直す */
            $this->CreateRoomNumber();
        }
    }

    function create_room($room_number, $publish_status) {
        $sql = "INSERT INTO table_name(room_number, publish_status)   /* SQL文を代入 */
        VALUES
            (:room_number, :publish_status)";

        $dbh = $this->dbConnect();                             /* DB接続 */

        try {
            $stmt = $dbh->prepare($sql);                /* 文を実行する準備を行い、文オブジェクトを返す */
            $stmt->bindValue(':room_number', $room_number, PDO::PARAM_STR);     /* $room_numberを:room_numberに結びつける */
            $stmt->bindValue(':publish_status', $publish_status, PDO::PARAM_STR);    /* $publish_statusを:publish_statusに結びつける */
            $stmt->execute();                           /* SQLを実行する */
        } catch(PDOException $e) {
            exit($e);                                   /* もしDB接続に失敗すればエラーコードを示し、プログラムを終了する */
        }
    }

    function room_info($room_number) {
        $dbh = $this->dbConnect();                             /* DB接続 */
        $stmt = $dbh->prepare('SELECT * FROM table_name where room_number = :room_number');   /* roomテーブルにあるroom_numberが:room_numberと同じレコードを取得する */
        $stmt->bindValue(':room_number', $room_number, PDO::PARAM_INT);                 /* $room_numberに:room_numberを結びつける */
        $stmt->execute();                               /* SQLを実行する */
        $result = $stmt->fetch(PDO::FETCH_ASSOC);       /* 実行した結果からレコードを取得する */
        return $result;                                 /* 取得したレコードを返す */
    }
}

?>