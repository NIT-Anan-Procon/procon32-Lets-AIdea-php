<?php

require_once('../../NGword_info.php');

class NGword {
    protected $dbh;
    protected $table;

    function __construct() {

        $dbname = db_name;
        $db_password = password;
        $user_name = db_user;
        $this->table = table;
        $dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8";
        
        try {
            $this->dbh = new PDO($dsn, $user_name, $db_password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        }
    }

    function AddNGword($gameID, $playerID, $word){

        try {

            $sql = "INSERT INTO $this->table(gameID, playerID, word)
            VALUES
                (:gameID, :playerID, :word)";
            
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':playerID', $playerID);
            $stmt->bindValue(':word', $word);
            $stmt->execute();
            return true;

        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            return false;
            exit();
        }
    }

    function GetNGword($playerID){

        $stmt = $this->dbh->prepare("SELECT word FROM $this->table WHERE playerID = :playerID");
        $stmt->bindValue(':playerID', $playerID);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $result;
    }

    function DelNGword($gameID){

        try {

            $stmt = $this->dbh->prepare("DELETE FROM $this->table WHERE gameID = :gameID");
            $stmt->bindValue(':gameID', $gameID);
            $stmt->execute();
            return true;

        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            return false;
            exit();
        }
    }
}