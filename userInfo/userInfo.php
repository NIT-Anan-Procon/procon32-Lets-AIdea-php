<?php

require_once('../../userInfo_info.php');

class userInfo {
    protected $dbh;
    protected $table;

    function __construct() {

        $dbname = db_name;
        $db_password = db_password;
        $user_name = db_user;
        $this->table = table;
        $dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8";
        
        try {
            $this->dbh = new PDO($dsn, $user_name, $db_password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        }
    }

    function AddUserInfo($name, $password, $image){

        $sql = "SELECT * FROM $this->table WHERE name=:name";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if(empty($account)){    //同じ名前のアカウントが存在しないとき

            try {

            $sql = "INSERT INTO $this->table(name, password, image_icon)
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

        $stmt = $this->dbh->prepare("SELECT password FROM $this->table WHERE name = :name");
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($result)){
            return false;
        }
        if(password_verify($password, $result['password'])){
            return true;
        } else {
            return false;
        }
    }

    function GetUserID($name){

        $stmt = $this->dbh->prepare("SELECT userID FROM $this->table WHERE name = :name");
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['userID'];
    }

    function GetUserInfo($userID){

        $stmt = $this->dbh->prepare("SELECT * FROM $this->table WHERE userID = :userID");
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    function ChPassword($userID, $newPassword){

        try {
            $sql = "UPDATE $this->table SET password = :newPassword WHERE userID = :userID";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':newPassword', password_hash($newPassword, PASSWORD_DEFAULT));
            $stmt->bindValue(':userID', $userID);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
            return false;
        }
    }

    function DeleteUserInfo($userID){

        try {
            $stmt = $this->dbh->prepare("DELETE FROM $this->table WHERE userID = :userID");
            $stmt->bindValue(':userID', $userID);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
            return false;
        }
    }
}