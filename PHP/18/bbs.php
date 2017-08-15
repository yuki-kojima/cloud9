<?php
// ログ記録ファイル
$filename = './review.txt';

// 変数初期化
$user_name = '';
$comment = '';
$log = '';
$data = [];
$data_rvs = [];
$err_msg = [];


// 志賀さんからのアドバイス
// $user_name_lenと$comment_lenは
// 「POSTされていて、かつuser_nameあるいはcomment_lenが送られていない時」
// には存在しなくなります。(これは通常は起こりませんが、
// ユーザーが自由な値をポストすることは可能ですので、それを前提に処理を書きます。）
// ですので、0で初期化しておいた方が良いでしょう。

$user_name_len = 0;
$comment_len = 0;

// POSTで送信された時の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // user_name取得
    if(isset($_POST['user_name']) === TRUE) {
        $user_name = $_POST['user_name'];
        $user_name_len = mb_strlen($user_name, 'UTF-8');
    }
    // comment取得
    if(isset($_POST['comment']) === TRUE) {
        $comment = $_POST['comment'];
        $comment_len = mb_strlen($comment, 'UTF-8');
    }
    
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
        
        // 掲示板に表示する内容を$logへ代入
        $log = $user_name . ': ' . $comment . ' -' . date('Y-m-d H:i:s') . "\n";
        
        // ログを記録ファイルへ書き込み
        if (($fp = fopen($filename, 'a')) !== FALSE) {
            if (fwrite($fp, $log) === FALSE) {
                $err_msg[] = 'ファイル書き込み失敗：' . $filename;
            }
            fclose($fp);
        }
        
    }
}

// ログ読み込み処理
if (is_readable($filename) === TRUE) {
    if (($fp = fopen($filename, 'r')) !== FALSE) {
        while (($tmp = fgets($fp)) !== FALSE) {
            $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
        }
        fclose($fp);
        $data_rvs = array_reverse($data);
    }
}else{
    $data[] = 'ファイルがありません';
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
        <?php foreach ($data_rvs as $value) { ?>
        <li><?php print $value; ?></li>
        <?php } ?>
        </ul>
    </body>
</html>