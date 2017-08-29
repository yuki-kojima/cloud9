<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>購入完了 - ConCari</title>
        <link rel="stylesheet" type="text/css" href="./css/normalize.css">
        <link rel="stylesheet" type="text/css" href="./css/common.css">
        <link rel="stylesheet" type="text/css" href="./css/shopping_complete.css">
        <script src="https://use.fontawesome.com/271459d746.js"></script>
    </head>
    <body>
        <div class="container_wrap">
            <?php $Path = './'; include ('./common/header.php'); ?>
            <div class="container">
                <div class="purchased_wrap">
                    <h1 class="mgn_b_30">¡Muchas gracias!</h1>
                    <p>プレゼントのご注文を承りました。</p>
                    <p>あなたの愛があの人に届きますように・・・</p>
                    <table class="purchased">
                        <tr>
                            <th>商品名</th>
                            <th>単価</th>
                            <th>数量</th>
                            <th>価格</th>
                        </tr>
                        <?php foreach ($data as $key => $value) { ?>
                        <tr>
                            <td class="item_name"><div class="item_name_wrap"><img class="item_thumbnail" src="<?php print $img_dir.$value['img']; ?>" ><p><?php print $value['item_name']; ?></p></div></td>
                            <td class="price"><?php print $value['price']; ?>円</td>
                            <td class="quantity"><?php print $value['quantity']; ?>個</td>
                            <td class="price"><?php print $value['quantity'] * $value['price']; ?>円</td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="total_price" colspan="3">合計(税込)</td>
                            <td class="right"><?php print $total_price; ?>円</td>
                        </tr>
                    </table>
                    <button class="btn_continue" type="button" onclick="location.href='./index.php'">プレゼント選びを続ける</button>
                </div>
                <?php foreach ($err_msg as $value) { ?>
                    <p><?php print $value; ?></p>
                <?php } ?>
            </div>
            <?php $Path = './'; include ('./common/footer.php'); ?>
        </div>
    </body>
    
</html>