<?php
    $date = date('Y');
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>ループの使用例</title>
    </head>
    <body>
        <form action="#">
            生まれた西暦を選択してください
            <select name="born_year">
                <?php
                //1900年〜現在の西暦までをループで処理
                for ($i = 1900; $i <= $date; $i++) {
                ?>
                <option value="<?php print $i; ?>"><?php print $i; ?>年</option>
                <?php
                }
                ?>
                
            </select>
        </form>
    </body>
    
</html>