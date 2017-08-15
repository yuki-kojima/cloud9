<?php


// DB接続
$host     = 'localhost';
$username = 'kjmyk12';
$password = '';
$dbname = 'camp';
$charset = 'utf8';

// MySQL用のDSN文字
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;

// 変数初期化
$user_name = '';
$comment = '';
$datetime = '';
$data = [];
$err_msg = [];
$user_name_len = 0;
$comment_len = 0;

try {
    //データベースに接続
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // echo 'データベースに接続しました';

    // POSTで送信された時の処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // user_name取得
        if(isset($_POST['user_name']) === TRUE) {
            $user_name = htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8');
            $user_name_len = mb_strlen($user_name, 'UTF-8');
        }
        // comment取得
        if(isset($_POST['comment']) === TRUE) {
            $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');
            $comment_len = mb_strlen($comment, 'UTF-8');
        }
        // datetimeの取得
        $datetime = date('Y-m-d H:i:s');
        
        // エラーコードの設定
        if ($user_name === '') {
            $err_msg[] = '名前を入力してください';
        }
        if ($user_name_len > 20) {
            $err_msg[] = '名前は20文字以内で設定してください';
        }
        if ($comment === '') {
            $err_msg[] = 'コメントを入力してください';
        }
        if ($comment_len > 100) {
            $err_msg[] = 'コメントは100文字以内で設定してください';
        }
        
        //エラーじゃない時に以下の処理を行う
        if (count($err_msg) === 0) {
        
            // 投稿情報格納用SQL文を作成
            $sql = 'insert into post (user_name, user_comment, create_datetime) values (?, ?, ?)';
        
            // SQL実行する準備
            $stmt = $dbh->prepare($sql);
            
            // SQL文のプレースホルダに値をバインド
            $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
            $stmt->bindValue(2, $comment, PDO::PARAM_STR);
            $stmt->bindValue(3, $datetime, PDO::PARAM_STR);
            
            // SQLを実行
            $stmt->execute();
        }
    }
    
    // 投稿情報取得用SQL文を作成
    $get_sql = 'select user_name, user_comment, create_datetime from post order by create_datetime desc';
    
    // SQL実行する準備
    $stmt_get_log = $dbh->prepare($get_sql);
    
    // SQLを実行
    $stmt_get_log->execute();        
    
    //レコードを取得
    $data = $stmt_get_log->fetchAll();
    // var_dump($data);

}catch (PDOExeption $e) {
    $err_msg[] = '接続できませんでした。理由：'.$e->getMessage();
}


?>



<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ひとこと掲示板</title>
    </head>
    <body>
        <h1>ひとこと掲示板</h1>
        <ul>
        <?php 
        if (count($err_msg) > 0) {
          foreach ($err_msg as $value) { 
        ?>
        <li><?php print $value; ?></li>
        <?php 
            } 
        }
        ?>
        </ul>
        <form method="post">
            <label>名前:　<input type="text" name="user_name"></label>
            <label>ひとこと: <input type="text" name="comment" size="60"></label>
            <input type="submit" value="送信">
        </form>
        <ul>
        <?php foreach ($data as $value) { ?>
        <li><?php print $value[0].': '.$value[1].' -'.$value[2]; ?></li>
        <?php } ?>
        </ul>
    </body>
</html>