<?php

    $value = 55.5555;
    
    // 小数点切り捨て
    $floor = floor($value);
    
    // 小数点切り上げ
    $ceil = ceil($value);
    
    // 小数四捨五入
    $round = round($value);
    
    // 小数第2位で四捨五入
    $round_2 = round($value, 2);
    
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>課題</title>
    </head>
    <body>
        <p>元の値： <?php print $value; ?></p>
        <p>小数切り捨て：　<?php print $floor; ?></p>
        <p>小数切り上げ：　<?php print $ceil; ?></p>
        <p>小数四捨五入：　<?php print $round; ?></p>
        <p>小数第2位で四捨五入：　<?php print $round_2; ?></p>
    </body>
</html>