<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>DB操作</title>
    </head>
    <body>
        <?php
        $host     = 'localhost'; 
        $username = 'kjmyk12'; // MySQLのユーザ名（ユーザ名を入力してください)
        $password = ''; // MySQLのパスワード（空でOKです）
        $dbname   = 'camp'; // MySQLのDB名
        $charset = 'utf8'; // データベースの文字コード
        
        //  MySQL用のDSN文字
        $dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
        
        try {
            // データベースに接続
            $dbh = new PDO($dsn, $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            echo 'データベースに接続しました';
            
            // 検索条件
            $user_name = '中井';
            $user_comment = 'こんばんは！';
            // SQL文を作成
            $sql ='select * from test_post where user_name = ? AND user_comment = ?';
            // SQL文を実行する準備
            $stmt = $dbh->prepare($sql);
            // SQL文のプレースホルダに値をバインド
            $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
            $stmt->bindValue(2, $user_comment, PDO::PARAM_STR);
            
            // SQLを実行
            $stmt->execute();
            // レコードの取得
            $rows = $stmt->fetchAll();
            
            var_dump($rows);
            
        }catch (PDOException $e) {
            echo '接続できませんでした。理由：'.$e->getMessage();
        }
        ?>
    </body>
    
</html>