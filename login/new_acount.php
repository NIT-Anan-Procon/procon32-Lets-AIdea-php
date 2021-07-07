<?php

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
        <h2>アカウント新規作成</h2>
        <span style="color:red">
        <?php if(!empty($_GET))echo $_GET['error']."<br>"?></span>
        <form action="create_acount.php" method="post">
            id<br>
            <input type="text" name="ID"><br><br>
            password<br>
            <input type="password" name="password"><br>
            <input type="submit" value="sign up">
        </form>
        <br>
        <a href="index.php">ログイン画面に戻る</a>
    </body>
</html>