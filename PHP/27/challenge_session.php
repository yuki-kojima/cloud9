<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>課題)Session</title>
    </head>
    <body>
        <?php
        $now = date('Y年m月d日 H時i分s秒');
        session_start();
        //訪問回数セッションがなければ設定
        if (!isset($_SESSION['count'])) {
            $_SESSION['count'] = 1;
            print "初めてのアクセスです<br>";
        } else {
            $_SESSION['count']++;
            print '合計' . $_SESSION['count'] . "回目のアクセスです<br>";
        }
        
        print $now . "(現在日時)<br>";
        
        // 訪問時刻のセッションがなければ設定
        if (!isset($_SESSION['visit_time'])) {
            // アクセス日時を更新
            $_SESSION['visit_time'] = $now;
        } else {
            $last_visit = $_SESSION['visit_time'];
            print $last_visit . "(前回アクセス日時)";
        
            // アクセス日時を更新   
            $_SESSION['visit_time'] = $now;
        } 
        ?>
        
        <form action="./challenge_session_delete.php" method="post">
            <input type="submit" name="delete_history" value="履歴削除"> 
        </form>
        
    </body>
    
</html>