<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>スコープ</title>
    </head>
    <body>
        <?php
        $str = 'スコープテスト'; //関数外で定義＝グローバル変数
        
        function test_scope() {
            print $str; //関数内の変数を参照
        }
        
        test_scope();
        
        //逆パターン
        

        function test_scope2() {
          print $str2 = 'スコープテスト'; // ローカル変数の定義
        }
        
        test_scope2();
        print $str2;
        
        ?>
    </body>
    
</html>