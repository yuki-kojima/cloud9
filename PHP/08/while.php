<?php
    $i = 1900;
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
            生まれ西暦を選択してください
            <select name="born_year">
                <?php
                    while ($i <= $date) {
                ?>
                <option value="<?php print $i; ?>"><?php print $i; ?>年</option>
                
                <?php
                    $i++;
                    }
                ?>
                
            </select>
        </form>
    </body>
    
</html>