<?php

require_once '../../info.php';

class Point
{
    protected $dbh;

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
            header('Error:'.$e->getMessage());

            exit();
        }
    }

    public function AddPoint($gameID, $playerID, $pointNum, $flag)
    {
        $sql = "INSERT INTO point(gameID, playerID, pointNum, flag)
        VALUES
            (:gameID, :playerID, :pointNum, :flag)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':playerID', $playerID);
            $stmt->bindValue(':pointNum', $pointNum);
            $stmt->bindValue('flag', $flag);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            header('Error:'.$e->getMessage());
            return false;
        }
    }

    public function GetPoint($gameID, $playerID, $flag)
    {
        $stmt = $this->dbh->prepare('SELECT SUM(pointNum) FROM point WHERE gameID = :gameID AND playerID = :playerID AND flag = :flag');
        $stmt->bindValue(':gameID', $gameID);
        $stmt->bindValue(':playerID', $playerID);
        $stmt->bindValue(':flag', $flag);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_COLUMN);
        if (false === $result) {
            $result = 0;
        }

        return (int)$result;
    }
}
