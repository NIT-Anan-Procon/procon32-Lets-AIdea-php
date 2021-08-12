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
            $result = 1;
            try {

            $sql = "INSERT INTO $table(name, password, image_icon)
            VALUES
                (:name, :password, :image_icon)";

            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':image_icon', $image);
            $stmt->execute();

            } catch(PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
            }
        } else {    //同じ名前のアカウントが存在する (失敗)
            $result = 0;
        }
        return $result;     //アカウント作成成功なら1、失敗なら0を返す
    }

    function GetUserInfo($name, $password){

        $table = table;
        $stmt = $this->dbh->prepare("SELECT * FROM $table WHERE name = :name AND password = :password");
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':password', $password);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($result)){
            return 0;
        } else {
            return $result;
        }
    }

}