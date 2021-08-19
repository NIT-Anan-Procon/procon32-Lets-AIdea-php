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

    function AddRoom($userID, $roomID) {
        $sql = "INSERT INTO $this->table(gameID, userID, roomID)
        VALUES
            (:gameID, :userID, :roomID)";

        $st = $this->dbh->prepare("SELECT * FROM $this->table");
        $st->execute();
        $result = $st->fetchall(PDO::FETCH_ASSOC);
        $count = count($result);
        if($count != NULL) {
            $last = $result[$count - 1]['gameID'];
            if($count % 4 === 0) {
                $gameID = $last + 1;
            } else {
                $gameID = $last;
            }
        } else {
            $gameID = 1;
        }

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

    function JoinRoom($userID, $roomID) {
        $st = $this->dbh->prepare("SELECT * FROM $this->table WHERE roomID = :roomID AND userID IS NULL");
        $st->bindValue(':roomID', $roomID);
        $st->execute();
        if( ($result = $st->fetch(PDO::FETCH_ASSOC) ) != false) {
            $playerID = (int)($result['playerID']);
            $sql ="UPDATE $this->table SET userID = :userID WHERE playerID = :playerID";
            $this->dbh->beginTransaction();
            try {
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
                $stmt->bindValue(':playerID', $playerID, PDO::PARAM_INT);
                $stmt->execute();
                $this->dbh->commit();
                $st = $this->dbh->prepare("SELECT * FROM $this->table WHERE playerID = :playerID");
                $st->bindValue(':playerID', $playerID);
                $st->execute();
                $result = $st->fetch(PDO::FETCH_ASSOC);
                return $result;
            } catch(PDOException $e) {
                $dbh->rollBack();
                return false;
                exit;
            }
        } else {
            return false;
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
