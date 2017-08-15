<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>while課題１</title>
    </head>
    <body>
        <?php
        $sum = 0;
        $i = 1;
        while ($i <= 100) {
            if ($i % 3 == 0) {
                $sum += $i;
            }
            $i++;
        }
        ?>
        <p>合計：　<?php print $sum; ?></p>
    </body>
    
</html>