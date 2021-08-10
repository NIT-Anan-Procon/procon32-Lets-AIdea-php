<?php

require_once('../../library_info.php');

class library {

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

    function UploadLibrary($userID, $explanation) {
        $today = date("Y/m/d H:i");
        $table = table;
        $sql = "INSERT INTO $table(userID, explanation, time)
        VALUES
            (:userID, :explanation, :time)";

        $dbh = $this->DbConnect();

        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':userID', $userID);
            $stmt->bindValue(':explanation', $explanation);
            $stmt->bindValue(':time', $today);
            $stmt->execute();
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        }

    }

    function GetLibrary($userID) { //特定のユーザーの作品を新しいもの順で返す
        
        $table = table;

        $dbh = $this->DbConnect();
        $stmt = $dbh->prepare("SELECT * FROM $table WHERE userID = :userID ORDER BY time DESC");
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;

    }

    function ListLibrary() { //全ユーザーの作品を新着順で返す
        
        $table = table;

        $dbh = $this->DbConnect();
        $stmt = $dbh->prepare("SELECT * FROM $table ORDER BY time DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;

    }

}