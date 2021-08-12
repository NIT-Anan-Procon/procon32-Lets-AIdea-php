<?php

require_once('../../info.php');

class Picture {

    public $dbh;

    function __construct() {

        $dbname = db_name;
        $password = password;
        $user_name = db_user;
        $dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8";
        
        try {
            $this->dbh = new PDO($dsn, $user_name, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        };
    
    }
    
    function AddGameInfo($gameID, $playerID, $PictureUrl, $answer) {
    
        $table = picture_table;
        $sql = "INSERT INTO $table(gameID, playerID, pictureURL, answer)
        VALUES
            (:gameID, :playerID, :pictureURL, :answer)";
    
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':playerID', $playerID);
            $stmt->bindValue(':pictureURL', $PictureUrl);
            $stmt->bindValue(':answer', $answer);
            $stmt->execute();
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        }
    }
    
    function GetGameInfo($pictureID) {
        $table = picture_table;
    
        $stmt = $this->dbh->prepare("SELECT * FROM $table WHERE pictureID = :pictureID");
        $stmt->bindValue(':pictureID', $pictureID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

}
