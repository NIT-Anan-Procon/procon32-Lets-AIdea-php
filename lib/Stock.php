<?php

ini_set('display_errors', 1);

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

    public function getStock()
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM {$this->table} WHERE flag = 0 LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result == false){
                return false;
            }

            $stmt = $this->dbh->prepare("UPDATE {$this->table} SET flag = 1 WHERE stockID = :stockID");
            $stmt->bindValue(':stockID', $result['stockID']);
            $stmt->execute();

            return $result;
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
