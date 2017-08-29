<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ショッピングカート - ConCari</title>
        <link rel="stylesheet" type="text/css" href="./css/normalize.css">
        <link rel="stylesheet" type="text/css" href="./css/common.css">
        <link rel="stylesheet" type="text/css" href="./css/cart.css">
        <script src="https://use.fontawesome.com/271459d746.js"></script>
    </head>
    <body>
        <div class="container_wrap">
            <?php $Path = './'; include ('./common/header.php'); ?>
            <div class="container">
                <div class="cart_wrap">
                    <h1 class="mgn_b_30">ショッピングカート</h1>
                    <!-- エラーメッセージ -->
                    <?php foreach ($err_msg as $value) { ?>
                    <p><?php print $value; ?></p>
                    <?php } ?>
                    <!-- /エラーメッセージ -->
                    <!-- 完了メッセージ -->
                    <?php if ($cmp_msg !== '') { ?>
                    <p><?php print $cmp_msg; ?></p>
                    <?php } ?>
                    <!-- /完了メッセージ -->
                    <?php if (count($data) === 0) { ?>
                    <p class="no_item">カートに商品が入っていません</p>
                    <?php } else { ?>
                        <table class="cart">
                            <tr>
                                <th>商品名</th>
                                <th>単価</th>
                                <th>数量</th>
                                <th>価格</th>
                                <th>削除</th>
                            </tr>
                            <?php foreach ($data as $key => $value) { ?>
                            <tr>
                                <td class="item_name"><div class="item_name_wrap"><img class="item_thumbnail" src="<?php print $img_dir.$value['img']; ?>" ><p><?php print $value['item_name']; ?></p></div></td>
                                <td class="price"><?php print $value['price']; ?>円</td>
                                <td class="quantity">
                                    <form action="./cart.php" method="post">
                                        <input type="text" name="change_quantity" placeholder="<?php print $value['quantity']; ?>" value="">個
                                        <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
                                        <button class="btn_cart_common" type="submit" name="change">変更</button>
                                    </form>
                                </td>
                                <td class="price"><?php print $value['price'] * $value['quantity']; ?>円</td>
                                <td class="delete">
                                    <form action="./cart.php" method="post">
                                        <input type="hidden" name="delete_id" value="<?php print $value['item_id']; ?>">
                                        <button class="btn_cart_common" type="submit" name="delete">×削除</button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td class="total_price" colspan="3">合計(税込)</td>
                                <td class="right"><?php print $total_price; ?>円</td>
                                <td class="total_price"></td>
                            </tr>
                        </table>
                        <form action="./shopping_complete.php" method="post">
                            <button class="btn_common btn_red btn_purchase" type="submit" name="purchase">購入を確定する</button>
                        </form>
                    <?php } ?>
                    <button class="btn_continue" type="button" onclick="location.href='./index.php'">プレゼント選びを続ける</button>
                </div>
            </div>
        <?php $Path = './'; include ('./common/footer.php'); ?>
</div>
    </body>
    
</html>