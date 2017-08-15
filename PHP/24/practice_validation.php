<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>バリデーション課題</title>
    </head>
    <body>
        <?php
        // ユーザIDバリデーション
        $userid = $_POST['userid'];
        $userid_regex = '/^[0-9a-z]{6,8}$/i';
        
        // バリデーション実行
        if (preg_match($userid_regex, $userid)) {
            print($userid." :ユーザIDは正しい形式で入力されています<br>");
        } else{
            print($userid." :ユーザIDは正しくない形式で入力されています<br>");

        }
        
        // 年齢バリデーション
        $age = $_POST['age'];
        $age_regex = '/^[0-9]+$/';
        
        // バリデーション実行
        if (preg_match($age_regex, $age)) {
            print($age." :正しい年齢の形式です<br>");
        } else {
            print($age." :正しくない年齢の形式です<br>");
        }
        
        // メールアドレスバリデーション
        $email = $_POST['email'];
        $email_regex = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\+_\.]+[a-z]{2,6})$/iD';
        
        // バリデーション実行
        if (preg_match($email_regex, $email)) {
            print($email." :正しいメールアドレスの形式です<br>");
        } else {
            print($email." :正しくないメールアドレスの形式です<br>");
        }
        
        // 電話番号バリデーション
        $tel = $_POST['tel'];
        $tel_regex = '/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{4}$/';
        
        // バリデーション実行
        if (preg_match($tel_regex, $tel)) {
            print($tel." :正しい電話番号の形式です<br>");
        } else {
           print($tel." :正しくない電話番号の形式です<br>");

        }
        
        ?>
    </body>
    
</html>