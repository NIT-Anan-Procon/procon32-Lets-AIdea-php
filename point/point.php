<?php

require_once('../../info.php');

class Point {

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

    function AddPoint($gameID, $playerID, $pointNum) {

        $table = point_table;
        $sql = "INSERT INTO $table(gameID, playerID, pointNum)
        VALUES
            (:gameID, :playerID, :pointNum)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':playerID', $playerID);
            $stmt->bindValue(':pointNum', $pointNum);
            $stmt->execute();
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        }

    }

    function GetPoint($pointID) {

        $table = point_table;

        $stmt = $this->dbh->prepare("SELECT SUM(pointNum) FROM $table WHERE pointID = :pointID");
        $stmt->bindValue(':pointID', $pointID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;

    }

}
