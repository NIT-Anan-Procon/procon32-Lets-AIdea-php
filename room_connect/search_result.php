<?php

require_once('DB.php');

$room_number = (int)(h($_POST['roomID']));

session_start();

if (empty($room_number)) {
    exit("検索された値が空です。");
}

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

$db = new DB();

$result = $db->room_info($room_number);
if ($result === false) {
    echo "<a href=join_form.php>検索した部屋は存在しません。</a>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>部屋詳細</title>
    <link rel="stylesheet" href="css/search_result.css">
</head>
<body>
    <form action="room.php?roomID=<?php echo $result['roomID']; ?>" method="POST" class="room">
        <div class="room_box">
            <p>部屋番号:<span><?php echo $result['roomID']; ?></span></p>
            <input type="submit" class="btn" value="参加する">
        </div>
    </div>
</body>
</html>