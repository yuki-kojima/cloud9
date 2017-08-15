<?php
$filename = './access_log.txt';
$comment = '';
$log = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'];
    // $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');
    $log = date('m月d日 H:i:s') . "\t" . $comment . "\n";
    if (($fp = fopen($filename, 'a')) !== FALSE) {
        if (fwrite($fp, $log) === FALSE) {
            print 'ファイル書き込み失敗：　' .$filename;
        }
        fclose($fp);
    }
}

$data = [];

if (is_readable($filename) === TRUE) {
    if(($fp = fopen($filename, 'r')) !== FALSE) {
        while (($tmp = fgets($fp)) !== FALSE) {
            $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
        }
        fclose($fp);
    }
}else {
    $data[] = 'ファイルがありません';
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>課題1</title>
    </head>
    <body>
        <h1>課題</h1>
        <form method="post">
            <input type="text" name="comment">
            <input type="submit" value="送信する">
        </form>
        <p>発言一覧</p>
        <p>
        <?php foreach ($data as $value) { ?>
        <p><?php print $value; ?></p>
        <?php } ?>
        </p>
    </body>
</html>