<?php

//session_start();
require('connect.php');

$dbh = connectDB();
$sql = "SELECT * FROM login WHERE ID=:ID";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':ID', $_POST['ID']); // :IDに値を代入
$stmt->execute();
$acount = $stmt->fetch(PDO::FETCH_ASSOC); // レコードを取得  

if (empty($acount)) { // IDが存在しなかったために、$acountの中身が空のとき
    $error = 'アカウント名が間違っています。';
    header("location: index.php?error=$error"); // index.phpに飛ばす
    exit;
}

if($_POST['password'] === $acount['password']){
    echo "こんにちは{$acount['ID']}様";
} else {
    $error = 'アカウント名またはパスワードが間違っています。';
    header("location: index.php?error=$error"); // index.phpに飛ばす
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
    <form method="POST" action="../room_connect/create_form.php">
        <input type="hidden" name="ID" value=<?php echo $acount['ID']; ?> >
        <input type="submit" value="部屋を作成する">
    </form>
    <form method="POST" action="../room_connect/search_room.php">
        <input type="hidden" name="ID" value=<?php echo $acount['ID']; ?> >
        <input type="submit" value="部屋を検索する">
    </form>
</body>
</html>