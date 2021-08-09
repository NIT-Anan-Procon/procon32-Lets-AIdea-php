<?php

require_once('DB.php');

$room = $_POST;

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
    <div class="room">
        <div class="room_box">
            <p>部屋番号:<span><?php echo $result['roomID']; ?></span></p>
            <a href="room.php?roomID=<?php echo $result['roomID'] ?>">参加する</a>
        </div>
    </div>
</body>
</html>