<?php

require_once '../Const.php';

require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;

class UserInfo
{
    protected $dbh;

    public function __construct()
    {
        $dbname = db_name;
        $db_password = password;
        $user_name = db_user;
        $dsn = "mysql:host=localhost;dbname={$dbname};charset=utf8";

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
        if (null === $name || null === $password) {
            return false;
        }
        $check = $this->CheckName($name);   //同じ名前のアカウントが存在するか
        if ($check) {
            return false;
        }
        $sql = 'INSERT INTO userinfo(name, password, image_icon)
        VALUES
            (:name, :password, :image_icon)';

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
        if (null === $name || null === $password) {
            return false;
        }

        $stmt = $this->dbh->prepare('SELECT * FROM userinfo WHERE name = :name');
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!($result)) {                             //resultがfalseのとき
            return false;
        }
        if (password_verify($password, $result['password'])) {
            return $result;
        }

        return false;
    }

    public function GetUserInfo($userID)
    {
        $stmt = $this->dbh->prepare('SELECT * FROM userinfo WHERE userID = :userID');
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ChangeUserName($userID, $name)
    {
        $sql = 'UPDATE userinfo SET name = :name WHERE userID = :userID';

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':userID', $userID);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();

            exit();

            return false;
        }
    }

    public function ChangePassword($userID, $Password)
    {
        $sql = 'UPDATE userinfo SET password = :Password WHERE userID = :userID';

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':Password', password_hash($Password, PASSWORD_DEFAULT));
            $stmt->bindValue(':userID', $userID);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();

            exit();

            return false;
        }
    }

    public function ChangeUserIcon($userID, $image)
    {
        $sql = 'UPDATE userinfo SET image = :image WHERE userID = :userID';

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':image', $image);
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
            $stmt = $this->dbh->prepare('DELETE FROM userinfo WHERE userID = :userID');
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
        $sql = 'SELECT name FROM userinfo WHERE name=:name';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_COLUMN);
        if ($result === $name) {
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
                $decode = JWT::decode($request, JWT_KEY, ['HS256']);
                $decode_array = (array) $decode;
                $result = $this->GetUserInfo($decode_array['userID']);
                $decode_array['exp'] = time() + JWT_EXPIRES;
                $jwt = JWT::encode($decode_array, JWT_KEY, JWT_ALG);
                echo '成功';
                if ($result) {
                    setcookie('token', $jwt, (time() + 50), '/', false, true);
                } else {
                    $result = false;
                }
            } catch (Exception $e) {
                echo '失敗';
                $result = false;
            }
        } else {
            $result = false;
        }

        return $result;
    }
}
