<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ログイン - ConCair</title>
        <link rel="stylesheet" type="text/css" href="./css/normalize.css">
        <link rel="stylesheet" type="text/css" href="./css/common.css">
        <link rel="stylesheet" type="text/css" href="./css/login.css">
        <script src="https://use.fontawesome.com/271459d746.js"></script>
    </head>
    <body>
        <div class="container_wrap">
            <?php $Path = './'; include ('./common/header.php'); ?>
            <div class="container center">
                <form class="form_login" action="./login.php" method="post">
                    <p><label for="email">メールアドレス：</label><input class="input_common" type="email" id ="email" name="email" value="<?php print $email; ?>"></p>
                    <p><label for="passwd">パスワード：</label><input class="input_common" type="password" id ="passwd" name="passwd" value=""></p>
                    <p class="btn_wrap"><button class="btn_common btn_login" name="login" type="submit">ログイン</button><a href="./register.php" class="btn_common btn_red btn_reg" >新規会員登録</a></p>
                    <?php foreach ($err_msg as $value) { ?>
                        <p><?php print $value; ?></p>
                    <?php } ?>
                </form>
            </div>
            <?php $Path = './'; include ('./common/footer.php'); ?>
        </div>
    </body>
    
</html>