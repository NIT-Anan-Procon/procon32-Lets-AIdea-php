<?php

require_once __DIR__.'/../Const.php';

require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../Develop.php';

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
            header('Error:'.$e->getMessage());

            exit();
        }
    }

    public function AddUserInfo($name, $password, $icon)
    {
        if ($this->CheckName($name)) {
            $result['name'] = false;

            return $result;
        }
        $result['name'] = true;
        $sql = 'INSERT INTO userinfo(name, password, icon)
        VALUES
            (:name, :password, :icon)';

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindValue(':icon', $icon);
            $stmt->execute();
            $result['state'] = true;

            return $result;
        } catch (PDOException $e) {
            header('Error:'.$e->getMessage());
            $result['state'] = false;

            return $result;
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
        if ($this->CheckName($name)) {
            $result['name'] = false;

            return $result;
        }
        $result['name'] = true;
        $sql = 'UPDATE userinfo SET name = :name WHERE userID = :userID';

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':userID', $userID);
            $stmt->execute();
            $result['state'] = true;

            return $result;
        } catch (PDOException $e) {
            header('Error:'.$e->getMessage());
            $result['state'] = false;

            return $result;
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
            header('Error:'.$e->getMessage());

            return false;
        }
    }

    public function ChangeUserIcon($userID, $icon)
    {
        $sql = 'UPDATE userinfo SET icon = :icon WHERE userID = :userID';

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':icon', $icon);
            $stmt->bindValue(':userID', $userID);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            header('Error:'.$e->getMessage());

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
            header('Error:'.$e->getMessage());

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

        return false;
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
                if ($result) {
                    $options = [
                        'expires' => time() + 3600,
                        'path' => '/',
                        'secure' => false,
                        'httponly' => true,
                    ];
                    setcookie('token', $jwt, $options);
                } else {
                    $result = false;
                }
            } catch (Exception $e) {
                header('Error: '.$e->getMessage());

                exit;
            }
        } else if (ReleaseMode === false) {
            $result = $this->GetUserInfo(2);
            return $result;
        } else {
            $result = false;
        }

        return $result;
    }
}
