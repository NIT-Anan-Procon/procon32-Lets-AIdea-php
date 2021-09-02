<?php

require_once '../Const.php';

class Point
{
    protected $dbh;
    protected $table = 'point';

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

    public function AddPoint($gameID, $playerID, $pointNum, $flag)
    {
        $sql = "INSERT INTO {$this->table}(gameID, playerID, pointNum, flag)
        VALUES
            (:gameID, :playerID, :pointNum, :flag)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':playerID', $playerID);
            $stmt->bindValue(':pointNum', $pointNum);
            $stmt->bindValue('flag', $flag);
            $stmt->execute();
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();

            exit();
        }
    }

    public function GetPoint($playerID, $flag)
    {
        $stmt = $this->dbh->prepare("SELECT SUM(pointNum) FROM {$this->table} WHERE playerID = :playerID AND flag = :flag");
        $stmt->bindValue(':playerID', $playerID);
        $stmt->bindValue(':flag', $flag);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function deleteGameInfo($gameID) {
        $stmt = $this->dbh->prepare("DELETE FROM {$this->table} WHERE gameID = :gameID");
        $stmt->bindValue(':gameID', $gameID);
        $stmt->execute();
    }
}
