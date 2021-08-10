<?php

require_once('../explanation_info.php');

class Explanation {

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

    function AddExplanation($gameID, $userID, $explanation, $flag) {

        $table = table;
        $sql = "INSERT INTO $table(gameID, userID, explanation, flag)
        VALUES
            (:gameID, :userID, :explanation, :flag)";

        $dbh = $this->DbConnect();

        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':userID', $userID);
            $stmt->bindValue(':explanation', $explanation);
            $stmt->bindValue(':flag', $flag);
            $stmt->execute();
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        }

    }

    function GetExplanation($gameID, $userID) {
        
        $table = table;

        $dbh = $this->DbConnect();
        $stmt = $dbh->prepare("SELECT * FROM $table WHERE gameID = :gameID AND userID = :userID");
        $stmt->bindValue(':gameID', $gameID);
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;

    }

}

$explanation = new Explanation();

$a = $explanation->GetExplanation(3, "A");
var_dump($a);
