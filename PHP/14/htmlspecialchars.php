<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>htmlspecialchars</title>
    </head>
    <body>
        <?php
        // 好きなhtmlを入力
        $str = '<h2>みかんが大好きです</h2>';
        // htmlspecialcharsを使わない場合
        print $str;
        //使う場合
        print htmlspecialchars($str,ENT_QUOTES);
        ?>
    </body>
</html>