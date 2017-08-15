<?php 
//         // 履歴削除処理
//         // delete_historyのPOST送信があったらCookie削除
//         if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['delete_history']) === TRUE)) {
//             var_dump($_COOKIE['visit_count']);
//             setcookie('visit_count', '', time() - 3600);
//             var_dump($_COOKIE['visit_count']);
//             setcookie('visit_time', '', time() - 3600);
//         }
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>課題)Cookie</title>
    </head>
    <body>
        <?php 
    
        
        $now = date('Y年m月d日 H時i分s秒');
        // Cookieが設定されていなければ、　cookieを設定する
        if (!isset($_COOKIE['visit_count'])) {
            // Cookieを設定
            setcookie('visit_count', 1);
             print "初めてのアクセスです<br>";

        // Cookieがすでに設定されていれば、更新
        } else {
            $count = $_COOKIE['visit_count'] + 1;
            setcookie('visit_count', $count);
            print "合計" . $count . "回目のアクセスです<br>";
        }
        
        print $now . "(現在日時)<br>";
        
        // アクセス日時がcookieに設定されていなければ、cookieを設定
        if (!isset($_COOKIE['visit_time'])) {
            // アクセス日時をCookieに設定
            setcookie('visit_time', $now);
            
        } else {
            // 前回アクセス日時を取得
            $last_visit = $_COOKIE['visit_time'];
            print $last_visit . "(前回のアクセス日時)<br>";
            
            // アクセス日時を更新
            setcookie('visit_time', $now);
        }?>
        
        <form action="./challenge_cookie_delete.php" method="post">
            <input type="submit" name="delete_history" value="履歴削除">
        </form>
    </body>
    
</html>