<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>繰り返し処理課題３</title>
    </head>
    <body>
        <?php
            $i = 1;
            while ($i <= 100) {
                if ($i % 3 == 0 && $i % 5 == 0) {
                    print 'FizzBuzz' . "<br>";
                }else if ($i % 3 == 0) {
                    print 'Fizz' . "<br>";
    
                }else if ($i % 5 == 0) {
                    print 'Buzz' . "<br>";
                }else {
                    print $i . "<br>";
                }
                $i++;
            }
        ?>
    </body>
    
</html>