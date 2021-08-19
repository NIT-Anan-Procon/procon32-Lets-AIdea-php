<?php

require_once('../../info.php');             //DBのログイン情報を取得

class Explanation {

    protected $dbh;
    protected $table = word_table;

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

    function AddExplanation($gameID, $playerID, $explanation, $flag) {

        $sql = "INSERT INTO $this->table(gameID, playerID, explanation, flag)
        VALUES
            (:gameID, :playerID, :explanation, :flag)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':playerID', $playerID);
            $stmt->bindValue(':explanation', $explanation);
            $stmt->bindValue(':flag', $flag);
            $stmt->execute();
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        }

    }

    function GetExplanation($gameID, $playerID) {

        $stmt = $this->dbh->prepare("SELECT * FROM $this->table WHERE gameID = :gameID AND playerID = :playerID");
        $stmt->bindValue(':gameID', $gameID);
        $stmt->bindValue(':playerID', $playerID);
        $stmt->execute();
        $result = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $result;

    }

}
