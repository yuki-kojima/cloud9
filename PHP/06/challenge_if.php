<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>if課題1</title>
    </head>
    <body>
        <?php
            $rand = mt_rand(1, 6);
        ?>
        <p>出た数：<?php print $rand; ?></p>
        <?php if ($rand %2 === 0) { ?>
        <p>偶数です。</p>
        <?php }else { ?>
        <p>奇数です。</p>
        <?php } ?>
    </body>
    
</html>