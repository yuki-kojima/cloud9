<?php
$sample = '';
$born_year = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['example']) === TRUE) {
        $sample = htmlspecialchars($_POST['example'], ENT_QUOTES, 'UTF-8');
    }
    
    if (isset($_POST['born_year']) === TRUE) {
        $born_year = htmlspecialchars($_POST['born_year'], ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>サンプルプログラミング</title>
    </head>
    <body>
        <?php if ($sample !== '' ) { ?>
        <p><?php print $sample; ?></p>
        <?php } ?>
        <?php if (isset($born_year) !== '' ) { ?>
        <p><?php print $born_year; ?></p>
        <?php    
        }
        ?>
        <!--フォーム-->
        <form method="post">
            <p><input type="checkbox" name="example" value="sample">サンプル</p>
            <!--<p><select name="born_year">-->
            <!--       <option value="2013">2013年</option>-->
            <!--    </select></p>-->
            <p><select name="born_year">
                   <option>2013年</option>
                </select></p>
            <input type="submit" value="送信">
        </form>
        
    </body>
</html>