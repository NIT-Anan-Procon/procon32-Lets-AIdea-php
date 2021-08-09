<?php

require_once('../../info.php');

class DB {

    function dbConnect() {

        $dbname = db_name;
        $password = password;
        $user_name = db_user;

        $dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8";
        $user = "$user_name";
        $pass = "$password";

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

    function create_room($room_number) {
        $table = table;
        $sql = "INSERT INTO $table(roomID)
        VALUES
            (:roomID)";

        $dbh = $this->dbConnect();

        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':roomID', $room_number, PDO::PARAM_STR);
            $stmt->execute();
       } catch(PDOException $e) {
            exit($e);
        }
    }

    function add_account($room_number, $userID) {
        $table = table;
        $sql = "INSERT INTO $table(roomID, userID)
        VALUES
            (:roomID, :userID)";

        $dbh = $this->dbConnect();

        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':roomID', $room_number);
            $stmt->bindValue(':userID', $userID);
            $stmt->execute();
       } catch(PDOException $e) {
            exit($e);
        }
    }

    function room_info($room_number) {
        $table = table;
        
        $dbh = $this->dbConnect();
        $stmt = $dbh->prepare("SELECT * FROM $table where roomID = :roomID");
        $stmt->bindValue(':roomID', $room_number, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

}

?>