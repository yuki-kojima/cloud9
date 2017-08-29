<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="./css/normalize.css">
        <link rel="stylesheet" type="text/css" href="./css/common.css">
        <link rel="stylesheet" type="text/css" href="./css/register_complete.css">
        <script src="https://use.fontawesome.com/271459d746.js"></script>
    </head>
    <body>
        <div class="container_wrap">
            <?php $Path = './'; include ('./common/header.php'); ?>
            <div class="container center">
                <h1 class="reg_complete_ttl">新規会員登録が完了しました！</h1>
                <p class="reg_complete">早速ログインしてとっておきのプレゼントを探そう</p>
                <button class="btn_common btn_red btn_login" type="button" onclick="location.href='./login.php'">ログインページへ</button></button>
            </div>
            <?php $Path = './'; include ('./common/footer.php'); ?>
        </div>
    </body>
    
</html>