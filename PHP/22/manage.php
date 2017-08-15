<?php
//DB情報
$host     = 'localhost';
$username = 'kjmyk12';   // MySQLのユーザ名
$password = '';       // MySQLのパスワード
$dbname   = 'camp';   // MySQLのDB名
$charset  = 'utf8';   // データベースの文字コード

// MySQL用のDSN文字列
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;

//変数初期化
$drink_name = '';
$price = 0;
$new_img_filename = '';
$img_dir  = '../21/img/';  //アップロードした画像ファイルの保存先ディレクトリ
$create_datetime = '';
$update_datetime = '';
$stock = '';
$update_stock = '';
$err_msg = [];
$data = [];


//POSTで送信されていたら以下の処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //drink_names取得
    if (isset($_POST['drink_name']) === TRUE) {
        $drink_name = $_POST['drink_name'];
    }
    // price取得
    if (isset($_POST['price']) === TRUE) {
        $price = $_POST['price'];
    }
    
    // stock取得
    if (isset($_POST['stock']) === TRUE) {
        $stock = $_POST['stock'];
    }
    
    // update_stock取得
    if (isset($_POST['update_stock']) === TRUE) {
        $update_stock = $_POST['update_stock'];
    }
    
    // update_id取得
    if (isset($_POST['update_id']) === TRUE) {
        $update_id = $_POST['update_id'];
    }

    //商品登録でPOST送信があった時のエラーコードの設定
    if (isset($_POST['register']) === TRUE) {
        if ($drink_name === '') {
            $err_msg[] = '商品名を入力してください';
        }
        if ($price === '') {
           $err_msg[] = '値段を入力してください'; 
        }
        if ($stock === '') {
            $err_msg[] = '在庫を入力してください';
        } else if (!preg_match("/^[0-9]+$/", $stock)) {
            $err_msg[] ='在庫は半角数字で入力してください';
        }
    
        
        // 画像エラーチェック
        // HTTP POSTでファイルがアップロードされたかどうかチェック
        if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
            // 画像の拡張子を取得
            $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
            // 指定の拡張子かチェック
            if ($extension !== 'jpg' && $extension !== 'jpeg' && $extension !== 'png') {
                $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEGもしくはPNGのみ利用可能です。';
            }
        } else {
            $err_msg[] = 'ファイルを選択してください';
        }
    
        
        // エラーじゃない時に以下の処理を行う
        if (count($err_msg) === 0) {
            
            // 保存する新しい画像ファイル名の生成（ユニークな値）
            $new_img_filename = sha1(uniqid(mt_rand(),true)).'.'.$extension;
            // 同名ファイルが存在するかチェック
            if(is_file($img_dir . $new_img_filename) !== TRUE) {
                //アップロードされたファイルを指定ディレクトリに移動して保存
                if (move_uploaded_file($_FILES['new_img']['tmp_name'], $img_dir . $new_img_filename) !== TRUE) {
                    $err_msg[] = 'ファイルアップロードに失敗しました';
                }
            } else {
                $err_msg[] = 'ファイアップロードに失敗しました。再度お試しください';
            }
            
        }
        
    // 在庫更新でPOST送信があった時のエラーコードの設定    
    } else if (isset($_POST['update']) === TRUE) {
        if ($update_stock === '') {
            $err_msg[] = '在庫数を入力してください';
        } else if (!preg_match("/^[0-9]+$/", $update_stock)) {
            $err_msg[] = '在庫数は半角数字で入力してください';
        }
    }
}

// ★DB処理
try {
    //DB接続
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // DB登録処理
    // エラーがない時に以下の処理を行う
    if (count($err_msg) === 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // 商品登録でPOSTがあった時の処理
        if (isset($_POST['register']) === TRUE) {
                
            // create_date取得
            $create_datetime = date('Y-m-d H:i:s');
            // トランザクション開始
            $dbh->beginTransaction();
            try {
                // test_drink_masterにdrink_id、価格、画像名、登録日時を登録
                // SQL文作成
                $sql = 'INSERT INTO test_drink_master (drink_name, price, img, create_datetime) VALUES (?, ?, ?, ?)';
                // SQL文を実行する準備
                $stmt = $dbh->prepare($sql);
                // SQL文のプレースホルダに値をバインド
                $stmt->bindValue(1, $drink_name, PDO::PARAM_STR);
                $stmt->bindValue(2, $price, PDO::PARAM_STR);
                $stmt->bindValue(3, $new_img_filename, PDO::PARAM_STR);
                $stmt->bindValue(4, $create_datetime, PDO::PARAM_STR);
                // SQLを実行
                $stmt->execute();
                
                // 今登録した商品のIDを取得
                $last_id = $dbh->lastInsertId();
                
                // test_drink_stockにdrink_id、在庫、登録日時を登録
                // SQL文作成
                $sql = 'INSERT INTO test_drink_stock (drink_id, stock, create_datetime) VALUES (?, ?, ?)';
                // SQL文を実行する準備
                $stmt = $dbh->prepare($sql);
                // SQL文のプレースホルダに値をバインド
                $stmt->bindValue(1, $last_id, PDO::PARAM_STR);
                $stmt->bindValue(2, $stock, PDO::PARAM_STR);
                $stmt->bindValue(3, $create_datetime, PDO::PARAM_STR);
                // SQL文を実行
                $stmt->execute();
                
                // コミット処理
                $dbh->commit();
            } catch (PDOExeption $e) {
                // ロールバック処理
                $dbh->rollback();
                throw $e;
            }
        }
        // 在庫更新でPOSTがあった時の処理
        if (isset($_POST['update']) === TRUE) {
            // update_date取得
            $update_datetime = date('Y-m-d H:i:s');
            try {
                // test_drink_stockの在庫数を更新
                // SQL文の作成
                $sql = 'UPDATE test_drink_stock SET stock = ?, update_datetime = ? WHERE drink_id = ?';
                // SQL文実行の準備
                $stmt = $dbh->prepare($sql);
                // SQL文のプレースホルダに値をバインド
                $stmt->bindValue(1, $update_stock, PDO::PARAM_STR);
                $stmt->bindValue(2, $update_datetime, PDO::PARAM_STR);
                $stmt->bindValue(3, $update_id, PDO::PARAM_STR);
                //SQL文を実行
                $stmt->execute();
                
            }catch (PDOExeption $e){
                throw $e;
            }
        }
    }

    
    // 登録済み内容の表示
    // test_drink_masterとtest_drink_stockを結合して情報を取得
    // SQL文作成
    // $sql_get = 'SELECT test_drink_master.drink_id, drink_name, price, img, stock FROM test_drink_master INNER JOIN test_drink_stock ON test_drink_master.drink_id = test_drink_stock.drink_id ORDER BY test_drink_master.create_datetime DESC';
    // ★ご参考
    $sql_get = 'SELECT 
                    test_drink_master.drink_id, 
                    drink_name, 
                    price, 
                    img, 
                    stock 
                FROM test_drink_master INNER JOIN test_drink_stock 
                    ON test_drink_master.drink_id = test_drink_stock.drink_id 
                ORDER BY test_drink_master.create_datetime DESC';
    // SQL文を実行する準備
    $stmt_get = $dbh->prepare($sql_get);
    // SQL文実行
    $stmt_get->execute();
    // レコードを取得
    $data = $stmt_get->fetchAll();
    
}catch (PODExeption $e) {
    $err_msg[] = 'DBエラーになりました。理由：'. $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>自動販売機</title>
    </head>
    <body>
        <h1>自動販売機</h1>
        <div style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
            <p>新規商品追加</p>
            <form method="post" enctype='multipart/form-data'>
                <p><label>名前：<input type="text" name="drink_name"></label></p>
                <p><label>値段：<input type="text" name="price"></label></p>
                <p><label>在庫：<input type="text" name="stock"></label></p>
                <p><label><input type="file" name="new_img"></label></p>
                <p><input type="submit" name="register" value="商品登録"</p>
            </form>
            <?php
                foreach ($err_msg as $value) {
            ?>
            <p><?php print $value; ?></p>   
            <?php 
                    } 
            ?>
        </div>
        <div>
            <h1>商品情報変更</h1>
            <p>商品一覧</p>
            <table border="1" cellspacing="0" cellpadding="5" border-color="#000000">
                <tr>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                </tr>
                <!--商品情報ループで取得-->
                <?php foreach ($data as $value) { ?>
                <tr>
                    <td><img src="<?php print $img_dir.$value[3]; ?>"</td>
                    <td><?php print htmlspecialchars($value[1], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php print htmlspecialchars($value[2], ENT_QUOTES, 'UTF-8') . '円'; ?></td>
                    <td>
                        <form method="post">
                            <label><input type="text" name="update_stock" placeholder="<?php print htmlspecialchars($value[4], ENT_QUOTES, 'UTF-8'); ?>">個</label>
                            <input type="hidden" name="update_id" value="<?php print $value[0]; ?>">
                            <input type="submit" name="update" value="変更"/>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </body>
</html>