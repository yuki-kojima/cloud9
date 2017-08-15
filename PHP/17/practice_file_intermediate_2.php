<?php
$filename = './tokyo.csv';
$data_array = [];
$data = [];
if ((is_readable($filename)) === TRUE) {
    if (($fp = fopen($filename, 'r')) !== FALSE) {
        while (($data_array = fgetcsv($fp)) !== FALSE) {
                $data[] = $data_array;
            }
        }
        fclose($fp);
    }else {
    $data[] = 'データがありません';
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>課題2</title>
    </head>
    <body>
        <p>以下にファイルから読み込んだ住所データを表示</p>
        
        <p>住所データ</p>
        <table border="1" cellspacing="0">
            <tr>
                <th>郵便番号</th>
                <th>都道府県</th>
                <th>市区町村</th>
                <th>町域</th>
            </tr>
            <?php foreach ($data as $value) { ?>
            <tr>
                <td><?php print htmlspecialchars($value[2], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value[6], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value[7], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value[8], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <?php } ?>
        </table>
    </body>
    
</html>