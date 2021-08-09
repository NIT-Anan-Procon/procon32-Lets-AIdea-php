<?php

require_once('DB.php');

$userID = $_POST['ID'];

$db = new DB();

$code = $db->CreateRoomNumber();
$db->create_room($code);
$result = $db->room_info($code);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>部屋詳細</title>
    <link rel="stylesheet" href="css/create_room.css">
</head>
<body>
    <div class="create">部屋を作成しました。</div>
    <form class="room" action="room.php?roomID=<?php echo $result['roomID']; ?>"  method="POST">
        <div class="room_box">
            <p>部屋番号:<span><?php echo $result['roomID']; ?></span></p>
            <input type="hidden" name="ID" value="<?php echo $userID; ?>" >
            <input type="submit" class="btn" value="参加する">
        </div>
    </form>
</body>
</html>