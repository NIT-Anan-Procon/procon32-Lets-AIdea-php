<?php

require_once('../../game_info.php');

class Game {

    function DbConnect() {

        $dbname = db_name;
        $password = password;
        $user_name = db_user;
        $dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8";
        
        try {
            $dbh = new PDO($dsn, $user_name, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        };
    
        return $dbh;
    
    }
    
    function AddGameInfo($roomID, $gameID, $userID, $PictureUrl, $answer) {
    
        $table = table;
        $sql = "INSERT INTO $table(roomID, gameID, userID, pictureURL, answer)
        VALUES
            (:roomID, :gameID, :userID, :pictureURL, :answer)";
    
        $dbh = $this->DbConnect();
    
        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':roomID', $roomID);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':userID', $userID);
            $stmt->bindValue(':pictureURL', $PictureUrl);
            $stmt->bindValue(':answer', $answer);
            $stmt->execute();
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        }
    }
    
    function GetGameInfo($roomID, $gameID, $userID) {
        $table = table;
    
        $dbh = $this->DbConnect();
        $stmt = $dbh->prepare("SELECT * FROM $table WHERE roomID = :roomID AND gameID = :gameID AND userID = :userID");
        $stmt->bindValue(':roomID', $roomID);
        $stmt->bindValue(':gameID', $gameID);
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

}

$game = new Game();

$result = $game->GetGameInfo(111, 1, "AB");

var_dump($result);