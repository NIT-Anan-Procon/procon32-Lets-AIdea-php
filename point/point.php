<?php

require_once('../point_info.php');

class Point {

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

    function AddPoint($userID, $gameID, $pointNum) {

        $table = table;
        $sql = "INSERT INTO $table(userID, gameID, pointNum)
        VALUES
            (:userID, :gameID, :pointNum)";

        $dbh = $this->DbConnect();

        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':userID', $userID, PDO::PARAM_STR);
            $stmt->bindValue(':gameID', $gameID, PDO::PARAM_INT);
            $stmt->bindValue(':pointNum', $pointNum, PDO::PARAM_INT);
            $stmt->execute();
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        }

    }

    function GetPoint($userID, $gameID) {

        $table = table;

        $dbh = $this->DbConnect();
        $stmt = $dbh->prepare("SELECT SUM(pointNum) FROM $table WHERE userID = :userID AND gameID = :gameID");
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':gameID', $gameID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;

    }

}
