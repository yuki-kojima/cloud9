<?php
//下記データは、SELECT文実行してfatchAllした配列データとします。
$products = array(
                1 => array(
                    'id' => 1,
                    'name' => 'あひるのぬいぐるみ'
                ),
                2 => array(
                    'id' => 2,
                    'name' => 'あひるの置物'
                ),
                3 => array(
                    'id' => 3,
                    'name' => 'あひるの傘'
                )
            );
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
</head>
<body>

<h2>商品一覧から詳細ページへPOST</h2>

<!--
    PHPでループして出力するならこんな感じでしょうか？
    遷移後のページでは＄＿POST['id']などでデータを受け取れるの、そのidを元に商品の詳細情報を
    selectしてループで展開すればよさそうですね。
 -->
<?php foreach ($products as $value){ ?>
    <div class="matrix_box">
        <h3><?php print $value['name']; ?></h3>
        <a href="" onclick="document.form<?php print $value['id']; ?>.submit(); return false;"><?php print $value['name']; ?>詳細を見る</a>
        <form action="product_detail.php" method="post" name="form<?php print $value['id']; ?>">
            <input type="hidden" name="product_id" value="<?php print $value['id']; ?>">
            <input type="hidden" name="product_name" value="<?php print $value['name']; ?>">
        </form>
    </div>
<?php } ?>
</body>
</html>
