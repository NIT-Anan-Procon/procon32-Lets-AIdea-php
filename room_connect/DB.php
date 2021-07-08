<?php
require_once('../../info.php');


class DB {

    function dbConnect() {

        $dbname = db_name;
        $password = password;
        $user_name = db_user;

        $dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8";
        $user = "$user_name";
        $pass = "$password";

        try {
            $dbh = new PDO($dsn,$user,$pass,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch(PDOException $e) {
            echo '接続失敗'. $e->getMessage();
            exit();
        };
        return $dbh;
    }

    function CreateRoomNumber() {
        $room_number = random_int(0, 99999999);
        $code = (int)(sprintf('%08d', $room_number));
        if (empty($this->room_info($code))) {
            return $code;
        } else {
            $this->CreateRoomNumber();
        }
    }

    function create_room($room_number, $publish_status) {
        $table = table;
        $sql = "INSERT INTO $table(room_number, publish_status)
        VALUES
            (:room_number, :publish_status)";

        $dbh = $this->dbConnect();

        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':room_number', $room_number, PDO::PARAM_STR);
            $stmt->bindValue(':publish_status', $publish_status, PDO::PARAM_STR);
            $stmt->execute();
       } catch(PDOException $e) {
            exit($e);
        }
    }

    function room_info($room_number) {
        $table = table;
        
        $dbh = $this->dbConnect();
        $stmt = $dbh->prepare("SELECT * FROM $table where room_number = :room_number");
        $stmt->bindValue(':room_number', $room_number, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function join_room($result){
        $table = table;
     /*   for($order = 0; $order <= 4; $order++){
            $join = 'account1';
            if(empty($result['account1']))
                break;
            else if($order = 4){
                echo '参加人数が上限に達しています';
                exit;
            }
    */    
        $dbh = $this->dbConnect();
    //    $order = 'account'.$order;
        $sql = "UPDATE $table SET account1 = :account1 where room_number = :room_number";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':account1', $_SESSION['id']);
        $stmt->bindValue(':room_number', $_SESSION['room']);
        $stmt->execute();
    }
}

?>