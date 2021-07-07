<?php

require_once('DB.php');

$room = $_POST;

if (empty($room["publish_status"])) {
    exit("<a href=html/create_form.html>公開状態が未入力です</a>");
}
if ($room['publish_status'] === "true") {
    $publish_status = 1;
} else if ($room['publish_status'] === "false"){
    $publish_status = 0;
}

$db = new DB();

$code = $db->CreateRoomNumber();
$db->create_room($code, $publish_status);
$result = $db->room_info($code);

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
    <div class="create">部屋を作成しました。</div>
    <div class="room">
        <div class="room_box">
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