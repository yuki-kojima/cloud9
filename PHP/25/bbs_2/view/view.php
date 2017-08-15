<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ひとこと掲示板</title>
    </head>
    <body>
        <h1>ひとこと掲示板</h1>
        <ul>
        <?php foreach ($err_msg as $value) { ?>
        <li><?php print $value; ?></li>
        <?php } ?>
        </ul>
        <form method="post">
            <label>名前:　<input type="text" name="user_name"></label>
            <label>ひとこと: <input type="text" name="comment" size="60"></label>
            <input type="submit" value="送信">
        </form>
        <ul>
        <?php foreach ($data as $value) { ?>
        <li><?php print $value[0].': '.$value[1].' -'.$value[2]; ?></li>
        <?php } ?>
        </ul>
    </body>
</html>