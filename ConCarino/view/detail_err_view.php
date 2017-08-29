<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>商品詳細 - ConCari</title>
        <link rel="stylesheet" type="text/css" href="./css/normalize.css">
        <link rel="stylesheet" type="text/css" href="./css/common.css">
        <link rel="stylesheet" type="text/css" href="./css/detail_err.css">
        <script src="https://use.fontawesome.com/271459d746.js"></script>
    </head>
    <body>
        <div class="container_wrap">
            <?php $Path = './'; include ('./common/header.php'); ?>
            <div class="container">
                <p class="detail_err">指定された商品は存在しません</p>
                <button class="btn_index" type="button" onclick="location.href='./index.php'">TOPに戻ってプレゼントを探す</button>
            </div>
        <?php $Path = './'; include ('./common/footer.php'); ?>
</div>
    </body>
    
</html>