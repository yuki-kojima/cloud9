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
$img_dir  = './img/';  //アップロードした画像ファイルの保存先ディレクトリ
$create_datetime = '';
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

    //エラーコードの設定
    if ($drink_name === '') {
        $err_msg[] = '商品名を入力してください';
    }
    if ($price === '') {
       $err_msg[] = '値段を入力してください'; 
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
}

// ★DB処理
try {
    //DB接続
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // date取得　最初に取得するケースもあります
    $create_datetime = date('Y-m-d H:i:s');

    // さらにエラーじゃない時に以下の処理を行う
    if (count($err_msg) === 0 && $_SERVER['REQUEST_METHOD'] === 'POST') { 

        // date取得　こちらでも方がよろしいですね
        $create_datetime = date('Y-m-d H:i:s');

        // 商品名、価格、アップロードした新画像ファイル名、登録時間をDBに保存
        try {
            // SQL文作成
            $sql_img_up = 'INSERT INTO test_drink_master (drink_name, price, img, create_datetime) values (?, ?, ?, ?)';
            // SQL文を実行する準備
            $stmt_img_up = $dbh->prepare($sql_img_up);
            // SQL文のプレースホルダに値をバインド
            $stmt_img_up->bindValue(1, $drink_name, PDO::PARAM_STR);
            $stmt_img_up->bindValue(2, $price, PDO::PARAM_STR);
            $stmt_img_up->bindValue(3, $new_img_filename, PDO::PARAM_STR);
            $stmt_img_up->bindValue(4, $create_datetime, PDO::PARAM_STR);

            // SQLを実行
            $stmt_img_up->execute();
        } catch (PODExeption $e) {
            throw $e;
        }
    }

    
    // 登録済み内容の表示
    // DBから情報を取得するSQL文作成
    $sql_get = 'SELECT drink_name, price, img FROM test_drink_master ORDER BY create_datetime DESC';
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
                <p><label><input type="file" name="new_img"></label></p>
                <p><input type="submit" value="アップロード"</p>
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
                </tr>
                <!--商品情報ループで取得-->
                <?php foreach ($data as $value) { ?>
                <tr>
                    <td><img src="<?php print $img_dir.$value[2]; ?>"</td>
                    <td><?php print htmlspecialchars($value[0], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php print htmlspecialchars($value[1], ENT_QUOTES, 'UTF-8') . '円'; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </body>
</html>