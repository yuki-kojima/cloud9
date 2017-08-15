<!DOCTYPE html>
<html lang="ja">
<head>
  <title></title>
  <meta charset="UTF-8">
</head>
<body>
    <?php
        $rand = mt_rand(0, 100);
        
        print $rand;
        
        if ($rand >= 60) {
            print '合格！！';
        }
    ?>
</body>
</html>