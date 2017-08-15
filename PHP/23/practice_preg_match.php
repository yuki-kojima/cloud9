<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>正規表現課題</title>
    </head>
    <body>
        <?php
        //半角数字であるかどうかのチェックをする検索パターンを記述
        $subject = '15000';
        $pattern ='/^[0-9]+$/';
        if(preg_match($pattern, $subject)) {
            print $subject.'は、半角数字です';
        } else {
            print $subject.'は、半角数字ではありません';
        }
        print "<br>";
        
        //半角英字であるかどうかのチェックをする検索パターンを記述
        $subject = 'CodeCamp';
        $pattern = '/^[a-zA-Z]+$/';
        if (preg_match($pattern, $subject)) {
            print $subject.'は、半角英字です';
        } else {
            print $subject.'は、半角英字ではありません';
        }
        print "<br>";
        
        //郵便番号のチェック
        $subject = '160-0023';
        $pattern = '/^[0-9]{3}-[0-9]{4}$/';
        if (preg_match($pattern, $subject)) {
            print $subject.'は、郵便番号の形式です';
        } else {
            print $subject.'は、郵便番号の形式ではありません';
        }
        ?>
    </body>
</html>