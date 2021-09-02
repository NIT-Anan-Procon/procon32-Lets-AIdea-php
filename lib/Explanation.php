<?php

require_once '../Const.php';             //DBのログイン情報を取得

class Explanation
{
    protected $dbh;
    protected $table = 'word';

    public function __construct()
    {
        // 3行目で取得したログイン情報を変数に代入
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

    public function AddExplanation($gameID, $playerID, $explanation, $flag)
    {
        $sql = "INSERT INTO {$this->table}(gameID, playerID, explanation, flag)
        VALUES
            (:gameID, :playerID, :explanation, :flag)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':playerID', $playerID);
            $stmt->bindValue(':explanation', $explanation);
            $stmt->bindValue(':flag', $flag);
            $stmt->execute();
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();

            exit();
        }
    }

    public function GetExplanation($gameID, $playerID)
    {
        // 3行目で取得したログイン情報を変数に代入
        $table = explanation_table;

        $stmt = $this->dbh->prepare("SELECT * FROM {$this->table} WHERE gameID = :gameID AND playerID = :playerID");
        $stmt->bindValue(':gameID', $gameID);
        $stmt->bindValue(':playerID', $playerID);
        $stmt->execute();

        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    public function deleteGameInfo($gameID)
    {
        $stmt = $this->dbh->prepare("DELETE FROM {$this->table} WHERE gameID = :gameID");
        $stmt->bindValue(':gameID', $gameID);
        $stmt->execute();
    }
}
