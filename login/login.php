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