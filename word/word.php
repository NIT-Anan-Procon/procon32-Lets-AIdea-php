<?php

require_once('../../info.php');

class word {

    protected $dbh;
    protected $table;

    function __construct() {

        $dbname = db_name;
        $password = password;
        $user_name = db_user;
        $this->table = word_table;

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

    function AddWord($gameID, $playerID, $word, $flag) {

        $sql = "INSERT INTO $this->table(gameID, playerID, word, flag)
        VALUES
            (:gameID, :playerID, :word, :flag)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':playerID', $playerID);
            $stmt->bindValue(':word', $word);
            $stmt->bindValue(':flag', $flag);
            $stmt->execute();
            $result = array('state'=>true);
            return $result;
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            $result = array('state'=>'DBとの接続エラー');
            return $result;
            exit();
        }

    }

    function GetWord($playerID, $flag) {
        
        $stmt = $this->dbh->prepare("SELECT word FROM $this->table WHERE playerID = :playerID AND flag = :flag");
        $stmt->bindValue(':playerID', $playerID);
        $stmt->bindValue(':flag', $flag);
        $stmt->execute();
        if($flag == 2){
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } else {
            $result = array("explanation"=>$stmt->fetch(PDO::FETCH_COLUMN));
        }
        if($result == false){
            $result = array('state'=>"指定されたplayerID・flagに値が存在しません");
        } 
        return $result;
    }

    function Delword($gameID){

        try {

            $stmt = $this->dbh->prepare("DELETE FROM $this->table WHERE gameID = :gameID");
            $stmt->bindValue(':gameID', $gameID);
            $stmt->execute();
            $result = array('state'=>0);
            return $result;

        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            $result = array('state'=>'DBとの接続エラー');
            return $result;
            exit();
        }
    }
}