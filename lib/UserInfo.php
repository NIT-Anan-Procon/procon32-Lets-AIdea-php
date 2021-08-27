<?php

require_once('../../info.php');
require_once('../JWT/const.php');
require_once('../JWT/vendor/autoload.php');

use Firebase\JWT\JWT;

class userInfo
{
    protected $dbh;
    protected $table;

    public function __construct()
    {
        $dbname = db_name;
        $db_password = password;
        $user_name = db_user;
        $this->table = userInfo_table;
        $dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8";

        try {
            $this->dbh = new PDO($dsn, $user_name, $db_password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        }
    }

    public function AddUserInfo($name, $password, $image)
    {
        if (is_null($name) || is_null($password)) {
            return false;
        }
        $check = $this->CheckName($name);   //同じ名前のアカウントが存在するか
        if ($check) {
            return false;
        }
        $sql = "INSERT INTO $this->table(name, password, image_icon)
        VALUES
            (:name, :password, :image_icon)";
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
            $stmt->bindValue(':image_icon', $image);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            return false;
            exit();
        }
    }

    public function userAuth($name, $password)
    {
        if (is_null($name) || is_null($password)) {
            return false;
        }

        $stmt = $this->dbh->prepare("SELECT * FROM $this->table WHERE name = :name");
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!($result)) {                             //resultがfalseのとき
            return false;
        }
        if (password_verify($password, $result['password'])) {
            return $result;
        } else {
            return false;
        }
    }

    public function GetUserInfo($userID)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM $this->table WHERE userID = :userID");
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function ChangeUserInfo($userID, $newName, $newImage)
    {
        if (is_null($newName) || is_null($userID)) {
            return false;
        }
        $check = $this->CheckName($newName);   //同じ名前のアカウントが存在するか
        if ($check) {
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
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
            return false;
        }
    }

    public function ChangePassword($userID, $newPassword)
    {
        try {
            $sql = "UPDATE $this->table SET password = :newPassword WHERE userID = :userID";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':newPassword', password_hash($newPassword, PASSWORD_DEFAULT));
            $stmt->bindValue(':userID', $userID);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
            return false;
        }
    }

    public function DelUserInfo($userID)
    {
        try {
            $stmt = $this->dbh->prepare("DELETE FROM $this->table WHERE userID = :userID");
            $stmt->bindValue(':userID', $userID);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
            return false;
        }
    }

    public function CheckName($name)
    {
        $sql = "SELECT name FROM $this->table WHERE name=:name";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_COLUMN);
        if ($result == $name) {
            return true;
        }
        return $result;
    }

    public function CheckLogin()
    {
        date_default_timezone_set('Asia/Tokyo');
        if (filter_input(INPUT_COOKIE, 'token')) {
            $request = $_COOKIE['token'];
            try {
                $decode = JWT::decode($request, JWT_KEY, array('HS256'));
                $decode_array = (array)$decode;
                $result = $this->GetUserInfo($decode_array['userID']);
                $decode_array['exp'] = time() + JWT_EXPIRES;
                $jwt = JWT::encode($decode_array, JWT_KEY, JWT_ALG);
                echo "成功";
                if ($result) {
                    setcookie('token', $jwt, (time() + 50), '/', false, true);
                } else {
                    $result = false;
                }
            } catch (Exception $e) {
                echo "失敗";
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }
}
