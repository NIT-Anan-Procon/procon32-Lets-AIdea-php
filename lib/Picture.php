<?php

require_once '../Const.php';

class Picture
{
    protected $dbh;
    protected $table = 'picture';

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

    public function AddPicture($gameID, $playerID, $PictureUrl, $answer)
    {
        $sql = "INSERT INTO $this->table(gameID, playerID, pictureURL, answer)
        VALUES
            (:gameID, :playerID, :pictureURL, :answer)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':playerID', $playerID);
            $stmt->bindValue(':pictureURL', $PictureUrl);
            $stmt->bindValue(':answer', $answer);
            $stmt->execute();

            return ['state' => true];
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();

            return ['state' => 'DBとの接続エラー'];

            exit();
        }
    }

    public function GetPicture($gameID, $playerID)
    {
        $sql = "SELECT pictureURL, answer FROM $this->table WHERE gameID = :gameID AND playerID ";
        if (null === $playerID) {
            $sql .= "IS NULL";
        } else {
            $sql .= "= :playerID";
        }
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':gameID', $gameID);
        if (null !== $playerID) {
            $stmt->bindValue(':playerID', $playerID);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function deleteGameInfo($gameID) {
        $stmt = $this->dbh->prepare("DELETE FROM {$this->table} WHERE gameID = :gameID");
        $stmt->bindValue(':gameID', $gameID);
        $stmt->execute();
    }
}
