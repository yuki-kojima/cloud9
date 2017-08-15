<!DOCTYPE html>
<html lang="ja">
<head>
  <title></title>
  <meta charset="UTF-8">
</head>
<body>
    <?php
        $rand = mt_rand(1, 10);
        
        print $rand;
        
        if ($rand >= 6) {
            print '当たり';
        }else {
            print 'はずれ';
        }
    ?>
</body>
</html>