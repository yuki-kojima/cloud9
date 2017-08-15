<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>商品管理ページ - Con Carino</title>
        <link rel="stylesheet" type="text/css" href="./css/normalize.css">
        <link rel="stylesheet" type="text/css" href="./css/common.css">
        <link rel="stylesheet" type="text/css" href="./css/admin.css">
        <?php $Path = './'; include ('./common/header.php'); ?>
    </head>
    <body>
        <div class="container center">
           <h1>商品管理ページ</h1> 
           <div>
               <h2>商品登録</h2>
               <form class="form_reg_item" action="./admin.php" enctype="multipart/form-data" method="post">
                    <p><label class="label" for="item_name">商品名：</label><input class="input_reg_item" type="text" id="item_name" name="item_name" value=""></p>
                    <p><label class="label" for="item_description">商品説明：</label><textarea class="input_reg_item" id="item_description" name="item_description" rows="3" cols="30"></textarea></p>
                    <p><label class="label" for="img">商品画像：</label><input type="file" id="img" name="img" value=""></p>
                    <p><label class="label" for="price">単価(税抜)：</label><input class="input_reg_item input_text" type="text" id="price" name="price" value="">円</p>
                    <p><label class="label" for="stock">在庫数：</label><input class="input_reg_item input_text" type="text" id="stock" name="stock" value="">個</p>
                    <p>
                        <span class="label" >ターゲット：</span>
                        <label for="target_f"><input type="checkbox" id="target_f" name="target_f" value="1">女性
                        <label for="target_m"><input type="checkbox" id="target_m" name="target_m" value="1">男性</p>
                    <p>
                        <span class="label" >カテゴリ：</span>
                        <select name="category">
                            <option value="">選択してください</option>
                            <option value="0">飲料・飲食</option>
                            <option value="1">スイーツ・お菓子</option>
                            <option value="2">ボディケア・コスメ</option>
                            <option value="3">キッチン雑貨</option>
                            <option value="4">ファッション雑貨</option>
                            <option value="5">ステーショナリー</option>
                        </select>
                    </p>
                    <p>
                        <span class="label" >ステータス：</span>
                        <select name="status">
                            <option value="0">公開</option>
                            <option value="1">非公開</option>
                        </select>
                    </p>
                    <button class="btn_common btn_reg_item" type="submit" name="register">商品を登録する</button>
               </form>
           </div>
           <div>
               <?php foreach($err_msg as $value) { ?>
                   <p><?php print $value; ?></p>
               <?php } ?>
           </div>
           <div>
               <h2>商品一覧</h2>
               <table>
                   <tr>
                       <th>商品画像</th>
                       <th>商品名</th>
                       <th>商品説明</th>
                       <th>単価(税抜)</th>
                       <th>カテゴリ</th>
                       <th>ターゲット</th>
                       <th>在庫数</th>
                       <th>ステータス</th>
                       <th>削除</th>
                   </tr>
                   <?php foreach ($data as $value) { ?>
                       <tr <?php if ($value['status'] === '1') { print 'class="bg_c_gray"';}  ?>>
                            <th><img src=<?php print $img_dir . $value['img']; ?></th>
                            <th><?php print $value['item_name']; ?></th>
                            <th><?php print $value['item_description']; ?></th>
                            <th><?php print $value['price']; ?>円</th>
                            <th><?php print $value['category']; ?></th>
                            <th>
                            <?php if ($value['target_m'] === '1') { ?><p>男性</p> <?php } ?>
                            <?php if ($value['target_f'] === '1') { ?><p>女性</p> <?php } ?>
                            </th>
                            <th>
                                <form action="./admin.php" method="post">
                                    <input type="hidden" name="update_id" value="<?php print $value['drink_id']; ?>">
                                    <p><input type="text" name="update_stock" placeholder="<?php print $value['stock']; ?>">個</p>
                                    <p><input type="submit" name="update" value="変更"></p>
                                </form>
                            </th>
                            <th>
                                <form action="./admin.php" method="post">
                                    <input type="hidden" name="change_id" value="<?php print $value['drink_id']; ?>">
                                    <?php if ($value['status'] === '0') { ?>
                                        <input type="hidden" name="change_status" value="1">
                                        <p>公開</p>
                                        <p><input type="submit" name="change" value="非公開にする"></p>
                                    <?php } else { ?>
                                        <input type="hidden" name="change_status" value="0">
                                        <p>非公開</p>
                                        <p><input type="submit" name="change" value="公開にする"></p>
                                    <?php } ?>
                                </form>
                            </th>
                            <th>
                                <form action="./adimin.php" method="post">
                                    <input type="submit" name="delete_item" value="×削除"> 
                                </form>
                            </th>
                   <?php } ?>
               </table>
           </div>
        </div>
    </body>
</html>