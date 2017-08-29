<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>新規会員登録 - ConCari</title>
        <link rel="stylesheet" type="text/css" href="./css/normalize.css">
        <link rel="stylesheet" type="text/css" href="./css/common.css">
        <link rel="stylesheet" type="text/css" href="./css/register.css">
        <script src="https://use.fontawesome.com/271459d746.js"></script>
    </head>
    <body>
        <div class="container_wrap">
            <?php $Path = './'; include ('./common/header.php'); ?>
            <div class="container center">
                <h1 class="mgn_t_30">新規会員登録</h1>
                <?php foreach ($err_msg as $value) { ?>
                <p><?php print $value; ?></p>
                <?php } ?>
                <form class="form_reg_user" action="./register.php" method="post">
                    <div class="input_set">
                        <label for="use_name">お名前<span class="required">必須</span></label>
                        <input class="input_common input_reg" type="text" id="user_name" name="user_name"/>
                    </div>
                    <div class="input_set">
                        <label for="nickname">ニックネーム<span class="required">必須</span></label>
                        <input class="input_common input_reg" type="text" id="nickname" name="nickname"/>
                    </div>
                    <div class="input_set">
                        <label for="email">メールドレス<span class="required">必須</span></label>
                        <input class="input_common input_reg" type="email" id="email" name="email" placeholder="example@xxx.xxx"/>
                    </div>
                    <div class="input_set">
                        <label for="passwd">ログインパスワード<span class="required">必須</span></label>
                        <input class="input_common input_reg" type="password" id="passwd" name="passwd" placeholder="6文字以上の半角英数字"/>
                    </div>
                    <div class="input_set">
                        <label for="post_code">郵便番号<span class="required">必須</span></label>
                        <input class="input_common input_reg" type="text" id="post_code" name="postcode" placeholder="000-0000 (半角数字)">
                    </div>
                    <div class="input_set">
                        <span class="label">都道府県</span><span class="required">必須</span>
                        <select class="input_common" name="pref">
                            <?php foreach ($pref as $key => $value) { ?>
                            <option value="<?php print $key; ?>"><?php print $value; ?></option>  
                            <?php } ?>
                        </select>
                    </div>
                    <div class="input_set">
                        <label for="address1">市区町村・番地<span class="required">必須</span></label>
                        <input class="input_common input_reg" type="text" id="address1" name="address1"/>
                    </div>
                    <div class="input_set">
                        <label for="address2">建物名など<span class="optional">任意</span></label>
                        <input class="input_common input_reg" type="text" id="address2" name="address2"/>
                    </div>
                    <p><button class="btn_common btn_red btn_reg_user" type="submit" name="register_member">新規登録する</button></p>
                </form>
            </div>
            <?php $Path = './'; include ('./common/footer.php'); ?>
        </div>
    </body>
</html>