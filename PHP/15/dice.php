<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>サイコロ</title>
    </head>
    <body>
        <pre>
        <?php
        
        // print 'サイコロを1個振る:' . throw_dice(1) . "\n";
        // print 'サイコロを1個振る:' . throw_dice(1) . "\n";
        // print 'サイコロを2個振る:' . throw_dice(2) . "\n";
        // print 'サイコロを2個振る:' . throw_dice(2) . "\n";
        // print 'サイコロを3個振る:' . throw_dice(3) . "\n";
        // print 'サイコロを3個振る:' . throw_dice(3) . "\n";

        print 'サイコロを1個振る:' . throw_dice() . "\n";

        function throw_dice($num = 1) {
            $sum = 0;
            for ($i = 0; $i < $num; $i++) {
                $sum += mt_rand(1,6);
            }
            return $sum;
        }
        ?>
        </pre>
    </body>
    
</html>