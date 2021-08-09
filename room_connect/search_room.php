<?php

    $userID = $_POST['ID'];

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>部屋を検索</title>
    <link rel="stylesheet" href="css/search_room.css">
</head>
<body>
    <form action="./search_result.php" method="POST">
        <label for="room_number">部屋番号</label>
        <input type="text" id="room_number" name="roomID">
        <input type="hidden" name="ID" value=<?php echo $userID; ?> >
        <input type="submit" value="検索する">
    </form>
</body>
</html>