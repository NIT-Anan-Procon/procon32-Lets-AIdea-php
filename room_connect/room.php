<?php

require_once('DB.php');
$db = new DB();

$room_number = $_GET['room_number'];

$result = $db->room_info($room_number);

if ($result === false) {
    exit('検索した部屋は存在しません。');
}
$_SESSION['room'] = $room_number;
$id = $_SESSION['id'];
$db ->join_room($id);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "$room_number"; ?></title>
    <style>
        * {
            margin: 0;
        }
    </style>
</head>
<body>
    <h1>
        <?php echo "$room_number"; ?>
    </h1>
</body>
</html>