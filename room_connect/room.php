<?php

require_once('../../info.php');

class Room {

    protected $dbh;
    protected $table =  room_table;

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

    function CreateRoomID() {
        $roomID = random_int(0,9999);
        $code = (int)(sprintf('%04d', $roomID));
        $result = $this->RoomInfo($code);
        if(count($result) === 0) {
            return $code;
        } else {
            $this->CreateRoomID();
        }
    }

    function CreateGameID() {
        $roomID = random_int(0,9999);
        $code = (int)(sprintf('%04d', $roomID));
        $result = $this->RoomInfo($code);
        if(count($result) === 0) {
            return $code;
        } else {
            $this->CreateGameID();
        }
    }

    function AddRoom($gameID, $userID, $roomID) {
        $sql = "INSERT INTO $this->table(gameID, userID, roomID)
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

    function DeleteRoom($playerID) {
        if(empty($playerID)) {
            exit;
        }

        $stmt = $this->dbh->prepare("DELETE FROM $this->table WHERE playerID = :playerID");
        $stmt->bindValue(':playerID',$playerID);
        $stmt->execute();
    }

    function RoomInfo($roomID) {
        $stmt = $this->dbh->prepare("SELECT * FROM $this->table where roomID = :roomID");
        $stmt->bindValue(':roomID', $roomID);
        $stmt->execute();
        $result = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }

}