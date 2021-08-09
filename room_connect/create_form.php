<?php

    session_start();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>部屋作成</title>
    <link rel="stylesheet" href="css/create_form.css">
</head>
<body>
    <form action="create_room.php" method="POST">
        <input type="submit" value="部屋を作成する">
    </form>
</body>
</html>