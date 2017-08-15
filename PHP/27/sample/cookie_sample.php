<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Cookie</title>
    </head>
    <body>
        <?php
        // Cookieが設定されていなければ(初回アクセス)、cookieを設定する
        if (!isset($_COOKIE['visit_count'])) {
            // Cookieを設定
            setcookie('visit_count', 1, time() + 3600);
            print( "訪問回数は1回<br>");
        }
        // Cookieがすでに設定されていれば(2回目以降のアクセス) 、cookieで設定した数値を加算する
        else {
            $count = $_COOKIE['visit_count'] + 1;
            setcookie('visit_count', $count, time() + 3600);
            print("訪問回数は".$count."回<br>");
        }
        ?>
    </body>
</html>