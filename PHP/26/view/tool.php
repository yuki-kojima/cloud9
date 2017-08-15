<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>自動販売機-商品管理画面</title>
        <style type="text/css">
            .container {
                max-width: 900px;
                margin: 0 auto;
            }
            body {
                margin: 0;
            }
            table, td, th {
                border : solid 1px #000;
            }
            .reg_wrap {
                border-top : solid 1px #000;
                border-bottom: solid 1px #000;
            }
            .hidden_item {
                background-color: #BDBDBD;
            }
            caption {
                text-align: left;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div>
            <?php foreach ($err_msg as $value) { ?>
            <p><?php print $value; ?></p>
            <?php } ?>
            <?php foreach ($cmp_msg as $value) { ?>
            <p><?php print $value; ?></p>
            <?php } ?>
            </div>
            <h1>自動販売機管理ツール</h1>
            <div class="reg_wrap">
                <h2>新規商品追加</h2>
                <form action="./tool.php" method="post" enctype="multipart/form-data">
                    <p><label>商品名：<input type="text" name="drink_name"></label></p>
                    <p><label>値段：<input type="text" name="price"></label></p>
                    <p><label>個数：<input type="text" name="stock"></label></p>
                    <p><input type="file" name="img"></p>
                    <p>
                        <select name="status">
                            <option value="0">公開</option>
                            <option value="1">非公開</option>
                        </select>
                    </p>
                    <p><input type="submit" name="register" value="登録する"></p>
                </form>
            </div>
            <div class="item_list_wrap">
                <h2>商品情報変更</h2>
                <table>
                    <caption>商品一覧</caption>
                    <tr>
                        <th>商品画像</th>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>在庫数</th>
                        <th>ステータス</th>
                    </tr>
                    <?php foreach ($data as $value) { ?>
                    <tr <?php if ($value['status'] === '1') { print 'class="hidden_item"'; } ?>>
                        <td><img src="<?php print $img_dir.$value['img']; ?>"></td>
                        <td><?php print $value['drink_name']; ?></td>
                        <td><?php print $value['price']; ?>円</td>
                        <td>
                            <form action="./tool.php" method="post">
                                <input type="text" name="update_stock" placeholder="<?php print $value['stock']; ?>">個
                                <input type="hidden" name="update_id" value="<?php print $value['drink_id']; ?>">
                                <input type="submit" name="update" value="変更">
                            </form>
                        </td>
                        <td>
                            <form action="./tool.php" method="post">
                                <?php if ($value['status'] === '0') { ?>
                                    <input type="hidden" name="update_status" value="1">
                                    <input type="hidden" name="update_id" value="<?php print $value['drink_id']; ?>">
                                    <input type="submit" name="change_status" value="公開→非公開にする">
                                <?php } else { ?>
                                    <input type="hidden" name="update_status" value="0">
                                    <input type="hidden" name="update_id" value="<?php print $value['drink_id']; ?>">
                                    <input type="submit" name="change_status" value="非公開→公開にする">
                                <?php } ?>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>    
    </body>
</html>