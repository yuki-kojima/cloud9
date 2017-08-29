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
                    <div class="detail_img_wrap"><img src="./img/milktea.png"></div>
                    <div class="detail_info_wrap">
                        <h1 class="detail_ttl">圀圀圀圀圀圀圀圀圀</h1>
                        <p class="detail_price">1,000円</p>
                        <div class="rate_wrap">
                            <i class="rate detail_rate rate50"></i><span class="score score_detail">5</span>
                        </div>
                        <p class="detail_discription">圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀</p>
                        <form class="btn_cart_wrap" action="./cart.php" method="post"><button type="submit" class="btn_common btn_cart" name="add_to_cart">カートに入れる</button></form>
                    </div>
                </div>
                <div class="review_wrap">
                    <h2>もらった人・あげた人の口コミ</h2>
                    <p class="right"><a href="#post">この商品の口コミを投稿する</a></p>
                    <div class="review">
                        <div class="rate_wrap">
                            <i class="rate review_rate rate50"></i><span class="score score_review">5</span><span class="give_given">もらった</span>
                        </div>
                        <p class="review_comment">圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀圀</p>
                        <p class="right">ニックネーム　さん 2017/12/31</p>
                    </div>
                    <div id="post" class="review">
                        <form action="./detail.php" method="post">
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
                                    <select class="input_common" id="post_rate" name="give_given">
                                        <option value="0">もらった︎</option>
                                        <option value="1">あげた︎</option>
                                    </select>
                                </p>
                            </div>
                            <p><label for="comment">コメント：</label><textarea id="comment" name="comment" rows="5" cols="80"></textarea></p>
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