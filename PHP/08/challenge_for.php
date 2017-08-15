<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>for課題１</title>
    </head>
    <body>
        <?php
        $sum = 0;
        for ($i = 1; $i <= 100; $i++){
            if ($i % 3 == 0) {
                $sum += $i;
            }
        }
        ?>
        <p>合計：　<?php print $sum; ?></p>
    </body>
    
</html>