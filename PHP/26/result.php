<?php

// 設定ファイルを読み込み
require_once './conf/const.php';

// 関数ファイルを読み込み
require_once './model/function.php';

global $err_msg;
$payment = '';
$drink_id = '';
$drink_name = '';
$img = '';
$change = '';
$img_dir = '';

// 画像保存先ディレクトリ
// $img_dir = './img/';


try {
    
    // DB接続
    $dbh = get_db_connect();
    
    // 投入金額の取得
    $payment = get_post_data('payment');
    
    // 購入したドリンクの情報取得
    $drink_id = get_post_data('drink_id');
    $data = get_selected_drink_data($dbh, $drink_id);
    $img = $img_dir.$data['img'];
    $change = culc_change($payment, $data['price']);
    
    // 商品購入処理
    purchase($dbh);

} catch (PDOExeption $e){
    $err_msg[] = $e->getMessage();
}

// 購入完了画面テンプレートファイルを読み込み
include_once './view/result.php';