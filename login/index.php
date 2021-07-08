<?php
session_start();


function h($s){
        return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}
?>


<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>logintest</title>
    </head>
    <body>
        <h2>ログイン画面</h2>
        <span style="color:red">
        <?php if(!empty($_GET))echo $_GET['error']."<br>"?></span>
        <form action="login.php" method="post">
            id<br>
            <input type="text" name="ID"><br><br>
            password<br>
            <input type="password" name="password"><br>
            <input type="submit" value="sign in">
        </form>
        <br>
        <a href="new_acount.php">アカウント新規作成</a>
    </body>
</html>