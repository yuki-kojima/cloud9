<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>トランザクション</title>
    </head>
    <body>
        <?php
        $host     = 'localhost';
        $username = 'kjmyk12';
        $password = '';
        $dbname   = 'camp';
        $charset  = 'utf8';
        
        // MySQL用のDSN文字列
        $dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
        try {
            //データベースに接続
            $dbh = new PDO($dsn, $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            //トランザクション開始
            $dbh->beginTransaction();
            try {
                // 現在日時を取得
                $now_date = date('Y-m-d H:i:s');
                //
                // 在庫情報テーブルにデータ作成
                //
                //SQL文作成
                $sql = 'insert into test_item_master(id, item_name, price, create_datetime, update_datetime) values (1, "PHP入門", 2000, "' . $now_date . '", "' . $now_date . '");';
                //SQL文を実行する準備
                $stmt = $dbh->prepare($sql);
                //SQLを実行
                $stmt->execute();
                
                // 最後にインサートされたIDの取得
                $lastid = $dbh->lastInsertId('update_datetime');
                print $lastid;
                
                //
                //在庫情報テーブルにデータ作成
                //
                // SQL文作成
                $sql = 'insert into test_item_stock(item_id, stock, create_datetime, update_datetime) values(1, 100, "' . $now_date . '", "' . $now_date . '");';
                // SQLを実行する準備
                $stmt = $dbh->prepare($sql);
                // SQLを実行
                $stmt->execute();
                
                 // 最後にインサートされたIDの取得
                 // mySQLではAUTO_INCEREMENTのIDが取得対象になるため、こちらは0になる！！
                $lastid = $dbh->lastInsertId();
                print $lastid;


                // コミット処理
                $dbh->commit();
                echo 'データが登録できました';
            } catch (PDOException $e) {
                //ロールバック処理
                $dbh->rollback();
                // 例外をスロー
                throw $e;
            }
            
            
        } catch (PDOException $e) {
            echo 'データベース処理でエラーが発生しました。理由：' . $e->getMessage();
        }
        ?>
    </body>
</html>