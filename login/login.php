<?php
session_start();
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
    $_SESSION['id'] = $acount['ID'];

} else {
    $error = 'アカウント名またはパスワードが間違っています。';
    header("location: index.php?error=$error"); // index.phpに飛ばす
    exit;
}
?>
<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>logintest</title>
    </head>
    <body>
        <h2>こんにちは、<?php echo "{$_SESSION['id']}"; ?>様</h2>
        <a href="../room_connect/html/create_form.html">部屋作成</a><br>
        <a href="../room_connect/html/search_room.html">部屋検索</a><br>
    </body>
</html>