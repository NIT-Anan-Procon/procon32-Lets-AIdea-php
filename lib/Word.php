<?php

require_once '../Const.php';

class Word
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

    public function addWord($gameID, $playerID, $word, $flag)
    {
        $sql = 'INSERT INTO word(gameID, playerID, word, flag)
        VALUES
            (:gameID, :playerID, :word, :flag)';

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':playerID', $playerID);
            $stmt->bindValue(':word', $word);
            $stmt->bindValue(':flag', $flag);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            header('Error:'.$e->getMessage());

            return false;
        }
    }

    public function getWord($gameID, $playerID, $flag)
    {
        $stmt = $this->dbh->prepare('SELECT word FROM word WHERE gameID = :gameID AND playerID = :playerID AND flag = :flag');
        $stmt->bindValue(':gameID', $gameID);
        $stmt->bindValue(':playerID', $playerID);
        $stmt->bindValue(':flag', $flag);
        $stmt->execute();
        if (2 === $flag) {
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } else {
            $result = $stmt->fetch(PDO::FETCH_COLUMN);
        }
        if (false === $result) {
            return null;
        }

        return $result;
    }

    public function delWord($gameID)
    {
        try {
            $stmt = $this->dbh->prepare('DELETE FROM word WHERE gameID = :gameID');
            $stmt->bindValue(':gameID', $gameID);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            header('Error:'.$e->getMessage());

            return false;
        }
    }
}
