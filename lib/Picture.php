<?php

require_once '../Const.php';

class Picture
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
            echo '接続失敗'.$e->getMessage();

            exit();
        }
    }

    public function AddPicture($gameID, $playerID, $PictureUrl, $answer)
    {
        $sql = 'INSERT INTO picture(gameID, playerID, pictureURL, answer)
        VALUES
            (:gameID, :playerID, :pictureURL, :answer)';

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
        $stmt = $this->dbh->prepare('SELECT pictureURL, answer FROM picture WHERE gameID = :gameID AND playerID = :playerID');
        $stmt->bindValue(':gameID', $gameID);
        $stmt->bindValue(':playerID', $playerID);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
