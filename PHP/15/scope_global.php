<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <?php
        $str = 'スコープテスト'; //グローバル変数
        
        function test_scope() {
            global $str; //グローバルう宣言（グローバル変数を参照）
            print $str;
        }
        
        test_scope();
        
        ?>

    </body>
</html>

