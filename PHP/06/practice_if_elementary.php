<!DOCTYPE html>
<html lang="ja">
<head>
        <meta charset="utf-8">
        <title>if課題2</title>
</head>
<body>
<pre>
<?php
    $rand1 = mt_rand(0, 2);
    $rand2 = mt_rand(0, 2);
    
    print 'rand1: ' . $rand1 . "\n";
    print 'rand2: ' . $rand2 . "\n";
    
    if ($rand1 > $rand2) {
        print 'rand1が大きいです。';
    }else if ($rand1 < $rand2) {
        print 'rand2が大きいです。';
    }else {
        print '同じ値です。';
    }
?>
</pre>
</body>
    
</html>