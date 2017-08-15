<?php

// 初期化
$height = '';
$weight = '';
$bmi = '';
$err_msg = []; // エラーメッセージようの配列

// リクエストメソッドを取得
$request_method = $_SERVER['REQUEST_METHOD'];

//「BMI計算」ボタンをクリックした(POSTメソッドで送られた)場合に処理する
if ($request_method === 'POST') {
    
    //POSTデータを取得
    $height = get_post_data('height');
    $weight = get_post_data('weight');
    
    //身長の値が数値化どうかチェックする
    if (is_numeric($height) === FALSE) {
        $err_msg[] = '身長は数値を入力してください';
    }
    
    //体重の値が数値化どうかチェックする
    if (is_numeric($weight) === FALSE) {
        $err_msg[] = '体重は数値を入力してください';
    }
    
    //エラーがない場合はBMIを計算
    if (count($err_msg) === 0) {
        $bmi = calc_bmi($height, $weight);
    }
}

//POSTデータ取得関数定義
function get_post_data($key) {
    $str = '';
    if(isset($_POST[$key]) === TRUE) {
        $str = $_POST[$key];
    }
    return $str;
}

// BMI計算関数定義
function calc_bmi($height, $weight) {
    $height = $height * 0.01;
    $bmi = $weight / ($height * $height);
    return round($bmi, 1);
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>BMI計算</title>
    </head>
    <body>
        <h1>BMI計算</h1>
        <form method="post">
            <label>身長(cm)：<input type="text" name="height" value="<?php print $height; ?>"></label>
            <label>体重(kg)：<input type="text" name="weight" value="<?php print $weight; ?>"></label>
            <input type="submit" value="BMIを計算する">
        </form>
    
    <?php if (count($err_msg) > 0) { 
        foreach ($err_msg as $value) {
    ?>
    <p><?php print $value; ?></p>
    <?php }
    } ?>
    <?php if ($request_method === 'POST' && count($err_msg) === 0) { ?>
    <p>あなたのBMIは<?php print $bmi; ?>です</p>
    <?php } ?>
    
    <p>*変数のデバッグ
        <?php var_dump($height, $weight); ?>
    </p>
    </body>
    
</html>