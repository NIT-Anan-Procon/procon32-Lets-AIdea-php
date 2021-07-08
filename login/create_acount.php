<?php
require('connect.php');

if(empty($_POST['ID']) || empty($_POST['password'])){
    $error = 'IDまたはpasswordに入力がありません。';
    header("location: new_acount.php?error=$error"); // new_acount.phpに飛ばす
    exit;
}

$dbh = connectDB();
$sql = "SELECT * FROM login WHERE ID=:ID";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':ID', $_POST['ID']); // :IDに値を代入
$stmt->execute();
$acount = $stmt->fetch(PDO::FETCH_ASSOC); // レコードを取得  

if (!empty($acount)) { // IDが存在するか
    $error = 'このアカウントは既に存在します。別のIDで登録してください。';
    header("location: new_acount.php?error=$error"); // new_acount.phpに飛ばす
    exit;
}

$sql = "INSERT INTO login VALUES (:ID, :password)";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':ID', $_POST['ID']);
$stmt->bindValue(':password', $_POST['password']);
$stmt->execute();

?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>logintest</title>
    </head>
    <body>
        登録完了<br>
        <a href="index.php">ログイン画面に戻る</a>
    </body>
</html>