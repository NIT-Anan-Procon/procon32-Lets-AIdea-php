<?php
require_once('../../login_info.php');

function connectDB(){ //データベースに接続
    $DB_user = db_user;            //opアカウントのidとパスワード
    $DB_password = db_password;
    $DB_name = db_name;

    $DSN = "mysql:host=localhost;dbname=$DB_name;charset=utf8";

    try {
        $dbh = new PDO($DSN, $DB_user, $DB_password);
        return $dbh;
    } catch (\Exception $e) {
        echo 'データベースに接続できません <br>'.$e->getMessage();
    }
}
?>