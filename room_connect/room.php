<?php

require_once('../../info.php');

class DB {

    public $dbh;

    function __construct() {

        $dbname = db_name;
        $pass = password;
        $user = db_user;

        $dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8";

        try {
            $this->dbh = new PDO($dsn,$user,$pass,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch(PDOException $e) {
            echo '接続失敗'. $e->getMessage();
            exit();
        };
    }

    function AddRoom($gameID, $userID, $roomID) {
        $table = room_table;
        $sql = "INSERT INTO $table(gameID, userID, roomID)
        VALUES
            (:gameID, :userID, :roomID)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':userID', $userID);
            $stmt->bindValue(':roomID', $roomID);
            $stmt->execute();
       } catch(PDOException $e) {
            exit($e);
        }
    }

    function RoomInfo($roomID) {
        $table = room_table;
        
        $stmt = $this->dbh->prepare("SELECT * FROM $table where roomID = :roomID");
        $stmt->bindValue(':roomID', $roomID);
        $stmt->execute();
        $result = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }

}
