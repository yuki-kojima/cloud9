<?php


$sql = <<<EOD
'''''""""""""
EOD;

echo $sql;
die;

$name = 'moriyama';
echo "My name is $name {$name} ${name}. I am printing some ";

die;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charaset="utf-8">
    </head>
    <body>
        <h1>シングルクウォーテーション</h1>
        <p>
            echo '{$string}';の出力結果
            <br> <?php echo '{$string}'; ?>
        </p>
        <h1>ダブルクウォーテーション</h1>
        <p>
            echo "$string";の出力結果
            <br> <?php echo "$test$string"; ?>
        </p>
        <h1>文字列の種類</h1>
        <p>
        http://php.net/manual/ja/language.types.string.php
        </p>
    </body>
</html>