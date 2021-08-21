<?php

require_once('../../info.php');

class library {
    protected $dbh;
    protected $library_table;

    function __construct() {

        $dbname = db_name;
        $password = password;
        $user_name = db_user;
        $this->table = library_table;

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

    function UploadLibrary($userID, $explanation, $NGword, $pictureURL, $flag) {
        
        date_default_timezone_set('Asia/Tokyo');
        $today = date("Y/m/d H:i:s");

        $sql = "INSERT INTO $this->table(userID, explanation, NGword, pictureURL, time, flag)
        VALUES
            (:userID, :explanation, :NGword, :pictureURL, :time, :flag)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':userID', $userID);
            $stmt->bindValue(':explanation', $explanation);
            $stmt->bindValue(':NGword', $NGword);
            $stmt->bindValue(':pictureURL', $pictureURL);
            $stmt->bindValue(':time', $today);
            $stmt->bindValue(':flag', $flag);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            return false;
            exit();
        }

    }

    function GetLibrary($userID, $flag) { //特定のユーザーの作品を新しいもの順で返す

        $stmt = $this->dbh->prepare("SELECT * FROM $this->table WHERE userID = :userID AND flag = :flag ORDER BY libraryID DESC");
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':flag', $flag);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }

    function ListLibrary($flag) { //全ユーザーの作品を新着順で返す

        $stmt = $this->dbh->prepare("SELECT * FROM $this->table WHERE flag = :flag ORDER BY libraryID DESC");
        $stmt->bindValue(':flag', $flag);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }
}