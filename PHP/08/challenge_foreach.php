<?php
$class = ['ガリ勉' => '鈴木', '委員長' => '佐藤', 'セレブ' => '斎藤', 'メガネ' => '伊藤', '女神' => '杉内'];
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>foreach課題2</title>
    </head>
    <body>
        <?php
            foreach ($class as $key => $value) {
                print $value . 'さんのあだ名は' . $key . 'です。';
            }
        ?>
    </body>
    
</html>