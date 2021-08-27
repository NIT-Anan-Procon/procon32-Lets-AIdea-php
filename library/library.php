<?php

require_once('../Const.php');

class library
{
    protected $dbh;
    protected $library_table;
    protected $now;

    public function __Construct()
    {
        $dbname = db_name;
        $password = password;
        $user_name = db_user;
        $this->table = 'library';
        date_default_timezone_set('Asia/Tokyo');
        $dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8";

        try {
            $this->dbh = new PDO($dsn, $user_name, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            exit();
        };
    }

    public function UploadLibrary($userID, $explanation, $NGword, $pictureURL, $flag)
    {
        $sql = "INSERT INTO $this->table(userID, explanation, NGword, pictureURL, time, flag)
        VALUES
            (:userID, :explanation, :NGword, :pictureURL, :time, :flag)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':userID', $userID);
            $stmt->bindValue(':explanation', $explanation);
            $stmt->bindValue(':NGword', $NGword);
            $stmt->bindValue(':pictureURL', $pictureURL);
            $stmt->bindValue(':time', date("Y/m/d H:i:s"));
            $stmt->bindValue(':flag', $flag);
            $stmt->execute();
            $result = array('state'=>true);
            return $result;
        } catch (PDOException $e) {
            echo '接続失敗'.$e->getMessage();
            $result = array('state'=>'データベースとの接続に失敗');
            return $result;
            exit();
        }
    }

    public function GetLibrary($search, $sort, $period, $page, $userID)
    { //全ユーザーの作品を新着順で返す

        $limit = 20;
        $p = 0;
        $sql = "SELECT * FROM library ";

        if ($search > 0) {
            $sql .= "WHERE flag = :flag ";
            $flag = $search - 1;
            $p = 1;
        }
        if ($period > 0) {
            $time = date("Y/m/d H:i:s", strtotime("-$period day"));
            if ($p = 0) {
                $sql .= "WHERE ";
            } else {
                $sql .= "AND ";
            }
            $sql .= "time > :time ";
            $p = 1;
        }
        if(!is_null($userID)){
            if ($p = 0) {
                $sql .= "WHERE ";
            } else {
                $sql .= "AND ";
            }
            $sql .= "userID = :userID ";
        }
        $sql .= "ORDER BY ";
        switch($sort){
            case 0:
                $sql .= "libraryID DESC ";
                break;
            case 1:
                $sql .= "like DESC, libraryID DESC ";
                break;
            case 2:
                $sql .= "LENGTH(explanation) DESC, libraryID DESC ";
                break;
            case 3:
                $sql .= "LENGTH(explanation) ASC, libraryID DESC ";
                break;
        }
        $sql .= "LIMIT :limit OFFSET :offset";
        $stmt = $this->dbh->prepare($sql);
        if($search > 0){
            $stmt->bindValue(':flag', $flag);
        }
        if($period > 0){
            $stmt->bindValue(':time', $time);
        }
        if(!is_null($userID)){
            $stmt->bindValue(':userID', $userID);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * 20, PDO::PARAM_INT);   
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
$obj = new library();
$result = $obj->GetLibrary(1, 0, 0, 1, null);
var_dump($result);