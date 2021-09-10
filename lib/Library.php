<?php

ini_set('display_errors', 1);

require_once __DIR__.'/../Const.php';

class Library
{
    protected $dbh;

    public function __construct()
    {
        $dbname = db_name;
        $password = password;
        $user_name = db_user;
        date_default_timezone_set('Asia/Tokyo');
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

    public function UploadLibrary($userID, $explanation, $ng, $pictureURL, $flag)
    {
        $sql = "INSERT INTO library(userID, explanation, ng, pictureURL, time, flag, likedUser)
        VALUES
            (:userID, :explanation, :ng, :pictureURL, :time, :flag, '|')";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':userID', $userID);
            $stmt->bindValue(':explanation', $explanation);
            $stmt->bindValue(':ng', $ng);
            $stmt->bindValue(':pictureURL', $pictureURL);
            $stmt->bindValue(':time', date('Y/m/d H:i:s'));
            $stmt->bindValue(':flag', $flag);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            header('Error:'.$e->getMessage());

            return false;
        }
    }

    public function GetLibrary($search, $sort, $period, $page, $userID)
    {
        $limit = 20;
        $p = 0;
        $sql = 'SELECT libraryID, userID, explanation, pictureURL, ng, time, good, flag FROM library ';
        if ($search > 0) {
            $sql .= 'WHERE flag = :flag ';
            $flag = $search - 1;
            $p = 1;
        }
        if ($period > 0) {
            $date = new DateTime();
            $time = $date->modify('-'.$period.' days')->format('Y/m/d H:i:s');
            if (0 === $p) {
                $sql .= 'WHERE ';
                $p = 1;
            } else {
                $sql .= 'AND ';
            }
            $sql .= 'time >= :time ';
        }
        if (0 !== $userID) {
            if ($p = 0) {
                $sql .= 'WHERE ';
            } else {
                $sql .= 'AND ';
            }
            $sql .= 'userID = :userID ';
        }
        $sql .= 'ORDER BY ';

        switch ($sort) {
            case 0:
                $sql .= 'libraryID DESC ';

                break;

            case 1:
                $sql .= 'good DESC, libraryID DESC ';

                break;

            case 2:
                $sql .= 'LENGTH(explanation) DESC, libraryID DESC ';

                break;

            case 3:
                $sql .= 'LENGTH(explanation) ASC, libraryID DESC ';

                break;
        }
        $sql .= 'LIMIT :limit OFFSET :offset';
        $stmt = $this->dbh->prepare($sql);
        if ($search > 0) {
            $stmt->bindValue(':flag', $flag);
        }
        if ($period > 0) {
            $stmt->bindValue(':time', $time);
        }
        if (0 !== $userID) {
            $stmt->bindValue(':userID', $userID);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $limit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (false === $result) {
            $result = null;
        }

        return $result;
    }

    public function Good($libraryID, $userID)
    {
        $check = $this->check($libraryID, $userID);
        $sql = 'UPDATE library SET good = good ';
        if (false === $check) {
            $result['check'] = 1;
            $sql .= '+ 1, likedUser = CONCAT(likedUser, :userID) ';
        } else {
            $result['check'] = 0;
            $userID = '|'.$userID;
            $sql .= "- 1, likedUser = replace(likedUser, :userID, '|') ";
        }
        $sql .= 'WHERE libraryID = :libraryID';

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':libraryID', $libraryID);
            $stmt->bindValue(':userID', $userID.'|');
            $stmt->execute();
        } catch (PDOException $e) {
            header('Error:'.$e->getMessage());

            return false;
        }
        $stmt = $this->dbh->prepare('SELECT good FROM library WHERE libraryID = :libraryID');
        $stmt->bindValue(':libraryID', $libraryID);
        $stmt->execute();
        $result['good'] = (int) $stmt->fetch(PDO::FETCH_COLUMN);

        return $result;
    }

    public function check($libraryID, $userID)
    {
        $stmt = $this->dbh->prepare('SELECT good FROM library WHERE libraryID = :libraryID AND likedUser like :userID');
        $stmt->bindValue(':libraryID', $libraryID);
        $stmt->bindValue(':userID', '%|'.$userID.'|%');
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_COLUMN);
    }
}
