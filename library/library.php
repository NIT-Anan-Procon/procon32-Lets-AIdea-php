<?php

require_once('../../library_info.php');

class library {
    protected $dbh;
    protected $library_table;
    protected $wordLibrary_table;

    function __construct() {

        $dbname = db_name;
        $password = password;
        $user_name = db_user;
        $this->library_table = library_table;
        $this->wordLibrary_table = wordLibrary_table;

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

    function UploadLibrary($userID, $explanation, $pictureURL) {

        date_default_timezone_set('Asia/Tokyo');
        $today = date("Y/m/d H:i:s");

        $sql = "INSERT INTO $this->library_table(userID, explanation, pictureURL, time)
        VALUES
            (:userID, :explanation, :pictureURL, :time)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':userID', $userID);
            $stmt->bindValue(':explanation', $explanation);
            $stmt->bindValue(':pictureURL', $pictureURL);
            $stmt->bindValue(':time', $today);
            $stmt->execute();
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        }

    }

    function GetLibrary($userID) { //特定のユーザーの作品を新しいもの順で返す

        $stmt = $this->dbh->prepare("SELECT * FROM $this->library_table WHERE userID = :userID ORDER BY libraryID DESC");
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }

    function ListLibrary() { //全ユーザーの作品を新着順で返す

        $stmt = $this->dbh->prepare("SELECT * FROM $this->library_table ORDER BY libraryID DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }

    function AddWordLibrary($libraryID, $word){

        $sql = "INSERT INTO $this->wordLibrary_table(libraryID, word)
        VALUES
            (:libraryID, :word)";
        try {            
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':libraryID', $libraryID);
            $stmt->bindValue(':word', $word);
            $stmt->execute();
            return true;

        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            return false;
            exit();
        }
    }

    function GetWordLibrary($libraryID){

        $stmt = $this->dbh->prepare("SELECT word FROM $this->wordLibrary_table WHERE libraryID = :libraryID");
        $stmt->bindValue(':libraryID', $libraryID);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $result;
    }
}