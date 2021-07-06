<?php

class DB {
    function dbConnect() {
        $dsn = 'mysql:host=localhost;dbname=db_name;charset=utf8';
        $user = 'user_name';
        $pass = 'password';
        try {
            $dbh = new PDO($dsn,$user,$pass,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch(PDOException $e) {
            echo '接続失敗'. $e->getMessage();
            exit();
        };
        return $dbh;
    }

    function CreateRoomNumber() {
        $room_number = random_int(0, 99999999);
        $code = (int)(sprintf('%08d', $room_number));
        if (empty($this->room_info($code))) {
            return $code;
        } else {
            $this->CreateRoomNumber();
        }
    }

    function create_room($room_number, $publish_status) {
        $sql = "INSERT INTO table_name(room_number, publish_status)
        VALUES
            (:room_number, :publish_status)";

        $dbh = $this->dbConnect();

        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':room_number', $room_number, PDO::PARAM_STR);
            $stmt->bindValue(':publish_status', $publish_status, PDO::PARAM_STR);
            $stmt->execute();
       } catch(PDOException $e) {
            exit($e);
        }
    }

    function room_info($room_number) {
        $dbh = $this->dbConnect();
        $stmt = $dbh->prepare('SELECT * FROM table_name where room_number = :room_number');
        $stmt->bindValue(':room_number', $room_number, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}

?>