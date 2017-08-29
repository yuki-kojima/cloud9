<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>商品詳細 - ConCari</title>
        <link rel="stylesheet" type="text/css" href="./css/normalize.css">
        <link rel="stylesheet" type="text/css" href="./css/common.css">
        <link rel="stylesheet" type="text/css" href="./css/detail.css">
        <script src="https://use.fontawesome.com/271459d746.js"></script>
    </head>
    <body>
        <div class="container_wrap">
            <?php $Path = './'; include ('./common/header.php'); ?>
            <div class="container">
                <div class="detail_wrap">
                    <div class="detail_img_wrap"><img src="<?php print $img_dir.$data['img']; ?>"></div>
                    <div class="detail_info_wrap">
                        <h1 class="detail_ttl"><?php print $data['item_name']; ?></h1>
                        <p class="detail_price"><?php print $data['price']; ?> 円</p>
                        <div class="rate_wrap">
                            <i class="rate detail_rate <?php print 'rate'.$data['star_rate']; ?>"></i><span class="score score_detail"><?php print $data['avg_rate'] / 10; ?></span>
                        </div>
                        <p class="detail_discription"><?php print $data['description']; ?></p>
                        <?php if (($data['status'] === '0') && ($data['stock'] !== '0')) { ?>
                            <form class="btn_cart_wrap" action="./detail.php" method="post">
                                <input type="hidden" name="item_id" value="<?php print $data['item_id']; ?>" >
                                <button type="submit" class="btn_common btn_cart" name="add_to_cart">カートに入れる</button>
                            </form>
                        <?php } else { ?>
                        <p>売り切れです</p>
                        <?php } ?>
                    </div>
                </div>
                <?php foreach ($err_msg as $value) { ?>
                <p class="center"><?php print $value; ?></p>
                <?php } ?>
                <?php foreach ($cmp_msg as $value) { ?>
                <p class="center"><?php print $value; ?></p>
                <?php } ?>
                <div class="review_wrap">
                    <h2>もらった人・あげた人の口コミ</h2>
                    <p class="right"><a href="#post">この商品の口コミを投稿する<i class="fa fa-commenting-o" aria-hidden="true"></i></a></p>
                    <?php if (empty($review_data)) { ?>
                        <p class="no_review center">この商品の口コミはまだありません</p>
                    <?php } ?>
                    <?php foreach ($review_data as $value) { ?>
                        <div class="review">
                            <div class="rate_wrap">
                                <i class="rate review_rate <?php print 'rate'.$value['rate']; ?>"></i>
                                <span class="score score_review"><?php print $value['rate']/10; ?></span>
                                <?php if ($value['give_given'] === '0') { ?>
                                    <span class="given">もらった</span> 
                                   <?php } else { ?>
                                    <span class="give">あげた</span>
                               <?php } ?>
                            </div>
                            <p class="review_comment"><?php print $value['comment']; ?></p>
                            <p class="right"><?php print $value['nickname'].'　さん '.$value['created_at']; ?></p>
                        </div>
                        <?php } ?>
                    <div id="post" class="review">
                        <form action="./detail.php?item_id=<?php print $data['item_id']; ?>" method="post">
                            <div class="post_select_wrap">
                                <p>
                                    <label for="post_rate">評価：</label>
                                    <select class="input_common" id="post_rate" name="rate">
                                        <option value="10">★1︎</option>
                                        <option value="15">★1.5︎</option>
                                        <option value="20">★2︎</option>
                                        <option value="25">★2.5︎</option>
                                        <option value="30">★3︎</option>
                                        <option value="35">★3.5︎</option>
                                        <option value="40">★4︎</option>
                                        <option value="45">★4.5︎</option>
                                        <option value="50">★5︎</option>
                                    </select>
                                </p>
                                <p>
                                    <label for="give_given">このプレゼントを：</label>
                                    <select class="input_common" id="give_given" name="give_given">
                                        <option value="0">もらった︎</option>
                                        <option value="1">あげた︎</option>
                                    </select>
                                </p>
                            </div>
                            <p><label for="comment">コメント：</label><textarea id="comment" name="comment" rows="5" cols="80"></textarea></p>
                            <input type="hidden" name="item_id" value="<?php print $data['item_id']; ?>" >
                            <button class="btn_common btn_post" type="submit" name="post_review">口コミを投稿する</button>
                        </form> 
                    </div>
                     <p class="mgn_t_30"><a href="./index.php"><< 商品一覧に戻る</a></p>
                </div>
            </div>
            <?php $Path = './'; include ('./common/footer.php'); ?>
        </div>
    </body>
</html>