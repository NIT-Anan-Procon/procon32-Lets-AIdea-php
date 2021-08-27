<?php

require_once('../Const.php');

class library
{
    protected $dbh;
    protected $library_table;
    protected $now;

    public function __construct()
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
        $con = "";

        if ($search > 0) {
            $con .= "WHERE flag = :flag ";
            $flag = $search - 1;
            $p = 1;
        }
        if ($period > 0) {
            $time = date("Y/m/d H:i:s", strtotime("-$period day"));
            if ($p = 0) {
                $con .= "WHERE ";
            } else {
                $con .= "AND ";
            }
            $con .= "time > :time ";
            $p = 1;
        }
        if(!is_null($userID)){
            if ($p = 0) {
                $con .= "WHERE ";
            } else {
                $con .= "AND ";
            }
            $con .= "userID = :userID ";
        }
        $con .= "ORDER BY ";
        switch($sort){
            case 0:
                $con .= "libraryID DESC ";
                break;
            case 1:
                $con .= "like DESC, libraryID DESC ";
                break;
            case 2:
                $con .= "LENGTH(explanation) DESC, libraryID DESC ";
                break;
            case 3:
                $con .= "LENGTH(explanation) ASC, libraryID DESC ";
                break;
        }
        $con .= "LIMIT 20 OFFSET :offset";

        $sqlData = "SELECT explanation, pictureURL, NGword, time, good, flag FROM library ";
        $stmt = $this->dbh->prepare($sqlData.$con);
        if($search > 0){
            $stmt->bindValue(':flag', $flag);
        }
        if($period > 0){
            $stmt->bindValue(':time', $time);
        }
        if(!is_null($userID)){
            $stmt->bindValue(':userID', $userID);
        }
        $stmt->bindValue(':limit', $limit);
        $stmt->bindValue(':offset', 3, PDO::PARAM_INT);        
        $stmt->execute();
        $values = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sqlData = "SELECT libraryID FROM library";
        $stmt = $this->dbh->prepare($sqlData.$sql);
        if($search > 0){
            $stmt->bindValue(':flag', $flag);
        }
        if($period > 0){
            $stmt->bindValue(':time', $time);
        }
        if(!is_null($userID)){
            $stmt->bindValue(':userID', $userID);
        }
        $stmt->bindValue(':limit', $limit);
        $stmt->bindValue(':offset', ($page - 1) * 20, PDO::PARAM_INT);        
        $stmt->execute();
        $libraryID = $stmt->fetchAll(PDO::FETCH_COLUMN);
 /*       $keys = [];
        for($i = 0; $i < $limit; $i++){
            $keys += [$result[$i]['libraryID'] => [[explanation], [pictureURL], [NGword], [time], [like], [flag]]];
        }
        $result = array_combine($keys, $values);
        return $result;*/
    }
}
$obj = new library();
$result = $obj->GetLibrary(1, 0, 0, 1, null);
print_r($result);