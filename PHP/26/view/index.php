<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>自動販売機-販売画面</title>
        <style type="text/css">
            .container {
                max-width : 900px;
                margin: 0 auto;
                text-align: center;
            }
            form {
                width: 100%;
            }
            body {
                margin: 0;
            }
            .red {
                color: #FF0000;
            }
            .center {
                text-align: center;
            }
            
            .item_wrap {
                display: flex;
                flex-wrap: wrap;  
                width: 450px;
                margin: 0 auto;
            }
            .item {
                width: 150px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>自動販売機</h1>
            <form action="./result.php" method="post">
                <p>
                    <label>金額：<input type="text" name="payment"></label>
                </p>
                <div class="item_wrap">
                    <?php foreach($data as $value) { ?>
                        <div class="item center">
                            <img src="<?php print $img_dir.$value['img']; ?>">
                            <p><?php print $value['drink_name']; ?></p>
                            <p><?php print $value['price']; ?></p>
                            <?php if ($value['stock'] === '0') { ?>
                                <p class="red">売り切れ</p>
                            <?php } else { ?>
                                <p><input type="radio" name="drink_id" value="<?php print $value['drink_id']; ?>"></p>
                            <?php } ?>    
                        </div>
                    <?php } ?>
                </div>
                <p class="center"><input type="submit" name="purchase" value="★購入する★"></p>
            </form>
        </div>
    </body>
</html>