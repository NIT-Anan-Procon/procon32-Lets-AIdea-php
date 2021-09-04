<?php

require_once '../Const.php';

class Room
{
    protected $dbh;
    protected $table = 'room';

    public function __construct()
    {
        $dbname = db_name;
        $pass = password;
        $user = db_user;

        $dsn = "mysql:host=localhost;dbname={$dbname};charset=utf8";

        try {
            $this->dbh = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            header('Error: '.$e->getMessage());

            exit();
        }
    }

    public function CreateRoomID()
    {
        $roomID = random_int(100000, 999999);
        $code = (int) (sprintf('%04d', $roomID));
        $result = $this->RoomInfo($code);

        if (0 === count($result)) {
            return $code;
        }
        $this->CreateRoomID();
    }

    public function GetGameID()
    {
        $stmt = $this->dbh->prepare("SELECT gameID FROM {$this->table} ORDER BY gameID DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            return 0;
        }

        return $result['gameID'];
    }

    public function GameInfo($gameID)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM {$this->table} WHERE gameID = :gameID ORDER BY playerID ASC");
        $stmt->bindValue(':gameID', $gameID);
        $stmt->execute();

        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    public function PlayerInfo($gameID, $playerID)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM {$this->table} WHERE gameID = :gameID AND playerID = :playerID");
        $stmt->bindValue(':gameID', $gameID);
        $stmt->bindValue(':playerID', $playerID);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function RoomInfo($roomID)
    {
        $st = $this->dbh->prepare("SELECT * FROM {$this->table} WHERE roomID = :roomID");
        $st->bindValue(':roomID', $roomID);
        $st->execute();

        return $st->fetchall(PDO::FETCH_ASSOC);
    }

    public function getGameInfo($userID)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM {$this->table} WHERE userID = :userID");
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRoomCount($gameID)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM {$this->table} WHERE gameID = :gameID AND userID IS NULL");
        $stmt->bindValue(':gameID', $gameID);
        $stmt->execute();

        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    public function AddRoom($gameID, $playerID, $userID, $roomID, $flag, $gamemode)
    {
        $sql = "INSERT INTO {$this->table}(gameID, playerID, userID, roomID, flag, gamemode)
        VALUES
            (:gameID, :playerID, :userID, :roomID, :flag, :gamemode)";

        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':gameID', $gameID);
            $stmt->bindValue(':playerID', $playerID);
            $stmt->bindValue(':userID', $userID);
            $stmt->bindValue(':roomID', $roomID);
            $stmt->bindValue(':flag', $flag);
            $stmt->bindValue(':gamemode', $gamemode);
            $stmt->execute();
        } catch (PDOException $e) {
            header('Error: '.$e->getMessage());

            exit;
        }
    }

    public function JoinRoom($userID, $roomID)
    {
        $room = $this->RoomInfo($roomID);
        $user = $this->getGameInfo($userID);
        $count = count($room);

        if ((0 !== $count) && (false === $user) && (4 !== $count)) {
            $playerID = (int) ($room[$count - 1]['playerID']) + 1;
            $gameID = (int) ($room[$count - 1]['gameID']);
            $gamemode = (int) ($room[$count - 1]['gamemode']);

            $sql = "INSERT INTO {$this->table}(gameID, playerID, userID, roomID, flag, gamemode)
            VALUES
                (:gameID, :playerID, :userID, :roomID, :flag, :gamemode)";

            try {
                $stmt = $this->dbh->prepare($sql);
                $stmt->bindValue(':gameID', $gameID);
                $stmt->bindValue(':playerID', $playerID);
                $stmt->bindValue(':userID', $userID);
                $stmt->bindValue(':roomID', $roomID);
                $stmt->bindValue(':flag', 0);
                $stmt->bindValue(':gamemode', $gamemode);
                $stmt->execute();

                return $this->PlayerInfo($gameID, $playerID);
            } catch (PDOException $e) {
                header('Error: '.$e->getMessage());

                exit;
            }
        }
    }

    public function DeleteRoom($gameID)
    {
        $stmt = $this->dbh->prepare("DELETE FROM {$this->table} WHERE gameID = :gameID");
        $stmt->bindValue(':gameID', $gameID);
        $stmt->execute();
    }

    public function LeaveRoom($roomID, $playerID)
    {
        $stmt = $this->dbh->prepare("DELETE FROM {$this->table} WHERE roomID = :roomID AND playerID = :playerID");
        $stmt->bindValue(':roomID', $roomID);
        $stmt->bindValue(':playerID', $playerID);
        $stmt->execute();
    }

    public function updateOwner($roomID)
    {
        $playerID = (int) $this->RoomInfo($roomID)[1]['playerID'];
        $this->dbh->beginTransaction();

        try {
            $stmt = $this->dbh->prepare("UPDATE {$this->table} SET flag = :flag WHERE playerID = :playerID AND roomID = :roomID");
            $stmt->bindValue(':playerID', $playerID, PDO::PARAM_INT);
            $stmt->bindValue(':roomID', $roomID, PDO::PARAM_INT);
            $stmt->bindValue(':flag', 1, PDO::PARAM_INT);
            $stmt->execute();
            $this->dbh->commit();
        } catch (PDOException $e) {
            header('Error: '.$e->getMessage());

            exit;
        }
    }

    public function joinAgain($gameID, $userID)
    {
        $this->dbh->beginTransaction();

        try {
            $stmt = $this->dbh->prepare("UPDATE {$this->table} SET gameID = :gameID WHERE userID = :userID");
            $stmt->bindValue(':gameID', $gameID, PDO::PARAM_INT);
            $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
            $this->dbh->commit();
        } catch (PDOException $e) {
            header('Error: '.$e->getMessage());

            exit;
        }
    }

    function updateGame($roomID) {
        $roomInfo = $this->RoomInfo($roomID);
        $num = count($roomInfo);

        for($i = 0; $i < $num; $i++) {
            $userID = $roomInfo[$i]['userID'];
            try {
                $stmt = $this->dbh->prepare("UPDATE {$this->table} SET playerID = :playerID WHERE userID = :userID");
                $stmt->bindValue(':playerID', $i + 1, PDO::PARAM_INT);
                $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
                $stmt->execute();
                $this->dbh->commit();
            } catch (PDOException $e) {
                header('Error: '.$e->getMessage());
    
                exit;
            }
        }
    }
}
