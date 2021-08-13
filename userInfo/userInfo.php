<?php

require_once('../../userInfo_info.php');

class userInfo {
    public $dbh;

    function __construct() {

        $dbname = db_name;
        $db_password = db_password;
        $user_name = db_user;
        $dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8";
        
        try {
            $this->dbh = new PDO($dsn, $user_name, $db_password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        };
    }

    function AddUserInfo($name, $password, $image){

        $table = table;
        $sql = "SELECT * FROM $table WHERE name=:name";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if(empty($account)){    //同じ名前のアカウントが存在しないとき

            try {

            $sql = "INSERT INTO $table(name, password, image_icon)
            VALUES
                (:name, :password, :image_icon)";

            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
            $stmt->bindValue(':image_icon', $image);
            $stmt->execute();

            } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
            }
            return true;
        } else {
            return false;
        }
    }

    function userAuth($name, $password){

        $table = table;
        $stmt = $this->dbh->prepare("SELECT password FROM $table WHERE name = :name");
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(password_verify($password, $result['password'])){
            return true;
        } else {
            return false;
        }
    }

    function GetUserID($name){

        $table = table;
        $stmt = $this->dbh->prepare("SELECT userID FROM $table WHERE name = :name");
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['userID'];
    }

    function GetUserInfo($userID){

        $table = table;
        $stmt = $this->dbh->prepare("SELECT * FROM $table WHERE userID = :userID");
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

}