<?php

require_once '../../library_info.php';

class Library
{
    public $dbh;

    public function __construct()
    {
        $dbname = db_name;
        $password = password;
        $user_name = db_user;
        $dsn = "mysql:host=localhost;dbname={$dbname};charset=utf8";

        try {
            $this->dbh = new PDO($dsn, $user_name, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();

            exit();
        }
    }

    public function UploadLibrary($userID, $explanation, $pictureURL)
    {
        date_default_timezone_set('Asia/Tokyo');
        $today = date('Y/m/d H:i:s');

        $table = table;
        $sql = "INSERT INTO {$table}(userID, explanation, pictureURL, time)
        VALUES
            (:userID, :explanation, :pictureURL, :time)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':userID', $userID);
            $stmt->bindValue(':explanation', $explanation);
            $stmt->bindValue(':pictureURL', $pictureURL);
            $stmt->bindValue(':time', $today);
            $stmt->execute();
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();

            exit();
        }
    }

    public function GetLibrary($userID)
    { //特定のユーザーの作品を新しいもの順で返す
        $table = table;

        $stmt = $this->dbh->prepare("SELECT * FROM {$table} WHERE userID = :userID ORDER BY time DESC");
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function ListLibrary()
    { //全ユーザーの作品を新着順で返す
        $table = table;

        $stmt = $this->dbh->prepare("SELECT * FROM {$table} ORDER BY libraryID DESC");
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
