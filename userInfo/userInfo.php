<?php

require_once('../../info.php');
require __DIR__ . '/const.php';
require __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;


class userInfo {
    protected $dbh;
    protected $table;

    function __construct() {

        $dbname = db_name;
        $db_password = password;
        $user_name = db_user;
        $this->table = userInfo_table;
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

        if(is_null($name) || is_null($password)){
            return false;
        }
        $check = $this->CheckName($name);   //同じ名前のアカウントが存在するか
        if($check){
            return false;
        }
        $sql = "INSERT INTO $this->table(name, password, image_icon)
        VALUES
            (:name, :password, :image_icon)";
        try{
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
            $stmt->bindValue(':image_icon', $image);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            return false;
            exit();
        }
    }

    function userAuth($name, $password){

        if(is_null($name) || is_null($password)){
            return false;
        }

        $stmt = $this->dbh->prepare("SELECT * FROM $this->table WHERE name = :name");
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!($result)){                             //resultがfalseのとき
            return false;
        }
        if(password_verify($password, $result['password'])){
            return $result;
        } else {
            return false;
        }
    }

    function GetUserInfo($userID){

        $stmt = $this->dbh->prepare("SELECT * FROM $this->table WHERE userID = :userID");
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function ChangeUserInfo($userID, $newName, $newImage){

        if(is_null($newName) || is_null($userID)){
            return false;
        }
        $check = $this->CheckName($newName);   //同じ名前のアカウントが存在するか
        if($check){
            return false;
        }
        try {
            $sql = "UPDATE $this->table SET name = :newName, image_icon = :newImage WHERE userID = :userID";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':newName', $newName);
            $stmt->bindValue(':newImage', $newImage);
            $stmt->bindValue(':userID', $userID);
            $stmt->execute();
            return true;
        } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
            return false;
        }
    }

    function ChangePassword($userID, $newPassword){

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

    function DelUserInfo($userID){

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

    function CheckName($name){

        $sql = "SELECT name FROM $this->table WHERE name=:name";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_COLUMN);
        if($result == $name){
            return true;
        }
        return $result;
    }

    function CheckLogin() {
        if (!is_null($_COOKIE['token'])) {
            $request = $_COOKIE['token'];
            $decode = JWT::decode($request, JWT_KEY, array('HS256'));
            $decode_array = (array)$decode;
            $result = $this->GetUserInfo($decode_array['userID']);
            if ($result) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}