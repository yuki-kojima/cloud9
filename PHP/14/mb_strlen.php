<?php

$str = '朝ごはんにたまご納豆を食べました。';

$length = mb_strlen($str);
$length_utf8 = mb_strlen($str, 'utf-8');

$str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
$length = htmlspecialchars($length, ENT_QUOTES, 'UTF-8');
$length_utf8 = htmlspecialchars($length_utf8, ENT_QUOTES, 'UTF-8');

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mb_strlen</title>
    </head>
    <body>
        <p>この文字列の長さは「<?php print $length; ?>」文字(バイト？）です。</p>
        <p>この文字列の長さは「<?php print $length_utf8; ?>」文字です。</p>
        <p><?php print $str; ?></p>
        <p>内部エンコード：<?php echo mb_internal_encoding(); ?></p>
    </body>
</html>