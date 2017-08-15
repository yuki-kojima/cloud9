<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>query</title>
    </head>
    <body>
        <?php
        $host     = 'localhost';
        $username = 'kjmyk12';
        $password = '';
        $dbname   = 'camp';
        $charset  = 'utf8';
        
        //MySQL用のDSN文字列
        $dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
        
        try {
            //　データベースに接続
            $dbh = new PDO($dsn, $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            // SQL文作成
            $sql = 'select * from test_post';
            //SQL実行
            $res = $dbh->query($sql);
            // レコードの取得
            $rows = $res->fetchAll();
            var_dump($rows);
        }catch (PDOException $e) {
            echo '接続できませんでした。理由：'.$e->getMessage();
        }
        
  ?>      
    </body>
    
</html>