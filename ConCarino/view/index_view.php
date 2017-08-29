<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ConCari</title>
        <link rel="stylesheet" type="text/css" href="./css/normalize.css">
        <link rel="stylesheet" type="text/css" href="./css/common.css">
        <link rel="stylesheet" type="text/css" href="./css/index.css">
        <script src="https://use.fontawesome.com/271459d746.js"></script>
    </head>
    <body>
        <div class="container_wrap">
            <?php $Path = './'; include ('./common/header.php'); ?>
            <div class="container">
                <!-- 商品検索フォーム -->
                <div class="form_wrap">
                    <form class="form_search center" action="./index.php" method="get">
                        <div class="form_wrap_opacity">
                            <div>
                                <span class="label">贈る相手</span>
                                <div class="option_wrap">
                                    <input type="checkbox" id="target_f" name="target_f" value="1"><label for="target_f">女性</label>
                                    <input type="checkbox" id="target_m" name="target_m" value="1"><label for="target_m">男性</label>
                                </div>
                            </div>
                            <div>
                                <span class="label">ご予算</span>
                                <div class="option_wrap">
                                    <select name="min_budget">
                                        <?php foreach ($budget as $key => $value) { 
                                            if ($key === 0) { ?>
                                                <option value="0" selected><?php print $value;
                                            } elseif ($key !== ($budget_len - 1)) { ?>
                                                <option value="<?php print $value; ?>"><?php print $value; ?>円 
                                        <?php }
                                     } ?>
                                     </select>
                                     〜
                                     <select name="max_budget">
                                        <?php foreach ($budget as $key => $value) { 
                                            if ($key === ($budget_len - 1)){ ?>
                                                <option value="999999" selected><?php print $value;
                                            }elseif ($key !== 0) { ?>
                                                <option value="<?php print $value; ?>"><?php print $value; ?>円
                                        <?php } 
                                     } ?>
                                     </select>
                                 </div>
                            </div>
                            <div>
                                <span class="label">カテゴリ</span>
                                <div class="option_wrap">
                                    <input type="checkbox" id="cat_1" name="category1" value="1"><label for="cat_1">食料・飲料</label>
                                    <input type="checkbox" id="cat_2" name="category2" value="2"><label for="cat_2">スイーツ・お菓子</label>
                                    <input type="checkbox" id="cat_3" name="category3" value="3"><label for="cat_3">ボディケア・コスメ</label>
                                    <input type="checkbox" id="cat_4" name="category4" value="4"><label for="cat_4">キッチン雑貨</label>
                                    <input type="checkbox" id="cat_5" name="category5" value="5"><label for="cat_5">ファッション雑貨</label>
                                    <input type="checkbox" id="cat_6" name="category6" value="6"><label for="cat_6">ステーショナリー</label>
                                </div>
                            </div>
                            <!--<div class="center">-->
                            <!--    <label class="fw_bold" for="keywd">キーワードで検索</label><input class="input_common" type="text" id="keywd" name="keywd" value="">-->
                            <!--</div>-->
                            <button class="btn_common btn_red btn_search" type="submit" name="search">この条件でプレゼントを探す</button>
                        </div>
                    </form>
                </div>
                <!-- /商品検索フォーム -->
                <!-- エラーメッセージ -->
                <?php foreach ($err_msg as $value) { ?>
                    <p><?php print $value; ?></p>
                <?php } ?>
                <!-- /エラーメッセージ -->
                <!-- 商品一覧 -->
                <div>
                    <h2>こんなプレゼントはいかが？</h2>
                    <form class="sort_wrap" name="form_sort" action="./index.php" method="get">
                        <?php 
                            foreach ($_GET as $key => $value) {
                                $value = htmlspecialchars($value); ?>
                                <input type="hidden" name="<?php print $key; ?>" value="<?php print $value; ?>" >
                        <?php  } ?>
                        <div>
                    　　<label for="sort_flg" class="fw_bold">表示順：</label>
                    　　<select class="input_common" name="sort_flg" onChange="document.form_sort.submit()">
                        　　<option value="0" selected>新着順</option>
                        　　<option value="1">価格安い順</option>
                        　　<option value="2">価格高い順</option>
                        　　<option value="3">評価高い順</option>
                    　　</select>
                    　　</div>
                    </form>
                    <div class="item_list_wrap">
                        <?php if (count($data) === 0) { ?>
                            <p>該当する商品がありませんでした。</p>
                        <?php } ?>
                        <?php foreach ($data as $key => $value) { ?>
                            <div class="item_wrap">
                                    <a href="./detail.php?item_id=<?php print $value['item_id']; ?>">
                                        <div class="item_img">
                                            <img src="<?php print $img_dir.$value['img']; ?>">
                                        </div>
                                        <div class="rate_wrap">
                                            <i class="rate <?php print 'rate'.$value['star_rate']; ?>"></i><span class="rate_score"><?php print $value['avg_rate'] / 10; ?></span>
                                        </div>
                                        <div class="item_info_wrap">
                                            <div class="item_name"><?php print $value['item_name']; ?></div>
                                            <div class="item_price"><?php print $value['price']; ?>円</div>
                                        </div>
                                    </a>
                                    <!--<a href="" onclick="document.detail<?php print $value['item_id']; ?>.submit();return false;">-->
                                    <!--    <div class="item_img">-->
                                    <!--        <img src="<?php print $img_dir.$value['img']; ?>">-->
                                    <!--    </div>-->
                                    <!--    <div class="rate_wrap">-->
                                    <!--        <i class="rate <? php print 'rate'.$value['rate']; ?>"></i><span class="rate_score">3.5</span>-->
                                    <!--    </div>-->
                                    <!--    <div class="item_info_wrap">-->
                                    <!--        <div class="item_name"><?php print $value['item_name']; ?></div>-->
                                    <!--        <div class="item_price"><?php print $value['price']; ?>円</div>-->
                                    <!--    </div>-->
                                    <!--</a>-->
                                    <!--<form action="./detail.php" name="detail<?php print $value['item_id']; ?>" method="post">-->
                                    <!--    <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>" >-->
                                    <!--</form>-->
                                <?php if (($value['status'] === '0') && ($value['stock'] !== '0')) { ?>
                                    <form action="./index.php" method="post">
                                        <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
                                        <button type="submit" class="btn_common btn_cart" name="add_to_cart">カートに入れる</button>
                                    </form>
                                <?php } else { ?>
                                    <p class="center">売り切れです</p>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php $Path = './'; include ('./common/footer.php'); ?>
        </div>
    </body>
</html>