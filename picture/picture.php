<?php

require_once('../../info.php');

class Picture {

    protected $dbh;
    protected $table = picture_table;

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
    
        $sql = "INSERT INTO $this->table(gameID, playerID, pictureURL, answer)
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
    
    function GetGameInfo($playerID) {    
        $stmt = $this->dbh->prepare("SELECT * FROM $this->table WHERE playerID = :playerID");
        $stmt->bindValue(':playerID', $playerID);
        $stmt->execute();
        $result = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $result;
    }

}