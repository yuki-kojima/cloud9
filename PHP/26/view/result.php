<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>自動販売機-購入完了画面</title>
        <style type="text/css">
            .container {
                max-width: 900px;
                margin: 0 auto;
                text-align: center;
            }
            .wrap {
                width: 100%;
            }
            body {
                margin: 0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>自動販売機結果</h1>
            <div class="wrap">
                <?php 
                    if (count($err_msg) > 0) {
                        foreach($err_msg as $value) { ?>
                            <p><?php print $value; ?></p> 
                <?php   } 
                    } else { 
                ?>
                <img src="<?php print $img; ?>">
                <p>がしゃん！【<?php print $data[drink_name]; ?>】が買えました！</p>
                <p>お釣りは【<?php print $change; ?>円】です。</p>
                <?php } ?>
            </div>
            <p class="wrap"><a href="./index.php">戻る</a></p>
        </div>
    </body>
</html>