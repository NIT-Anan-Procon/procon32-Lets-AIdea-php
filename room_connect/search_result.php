<?php

require_once('DB.php');

$room = $_POST;
$count = count($room);
if ($count === 0) {
    exit("検索された値が空です。");
}

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

$room_number = (int)(h($room['room_number']));

if (empty($room_number)) {
    echo "<a href=join_form.php>検索した部屋は存在しません。</a>";
    exit;
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
    <div class="room">
        <div class="room_box">
            <?php echo $_SESSION['id']; ?>
            <p>部屋番号:<span><?php echo $result['room_number']; ?></span></p>
            <p>公開状態:<span><?php if ((int)$result['publish_status'] === 1) {
                echo "公開";
                } else {
                echo "非公開";
                }
            ?></span></p>
            <a href="room.php?room_number=<?php echo $result['room_number'] ?>">参加する</a>
        </div>
    </div>
</body>
</html>