<?php

require_once __DIR__.'/../Const.php';

class Stock
{
    protected $dbh;
    protected $table = 'stock';

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

    public function addStock($explanation, $ng, $synonyms, $pictureURL)
    {
        $sql = "INSERT INTO {$this->table}(explanation, ng, synonyms, pictureURL)
        VALUES
            (:explanation, :ng, :synonyms, :pictureURL)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':explanation', $explanation);
            $stmt->bindValue(':ng', $ng);
            $stmt->bindValue(':synonyms', $synonyms);
            $stmt->bindValue(':pictureURL', $pictureURL);
            $stmt->execute();
        } catch (PDOException $e) {
            header('Error:'.$e->getMessage());

            exit;
        }
    }

    public function getStock($stockID)
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM {$this->table} WHERE stockID = :stockID");
            $stmt->bindValue(':stockID', $stockID);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            header('Error:'.$e->getMessage());

            exit;
        }
    }

    public function getCount()
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM {$this->table}");
            $stmt->execute();

            return count($stmt->fetchall(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            header('Error:'.$e->getMessage());

            exit;
        }
    }

    public function deleteStock($stockID)
    {
        try {
            $stmt = $this->dbh->prepare("DELETE FROM {$this->table} WHERE stockID = :stockID");
            $stmt->bindValue(':stockID', $stockID);
            $stmt->execute();
        } catch (PDOException $e) {
            header('Error:'.$e->getMessage());

            exit;
        }
    }
}
