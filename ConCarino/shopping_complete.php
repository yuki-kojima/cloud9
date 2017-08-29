<?php

// 設定ファイルを読み込み
require_once './conf/const.php';

// 関数ファイルを読み込み
require_once './model/common_model.php';
require_once './model/session_check_model.php';
require_once './model/shopping_complete_model.php';

// 変数初期化
$err_msg = [];
$cmp_msg = '';
$data = [];
$total_price = '';
$user_id ='';

// 商品画像の保存先ディレクトリ
global $img_dir;

// ログイン済かチェックしユーザーIDを取得
$user_id = check_user_id();

try {
    
    // DB接続
    $dbh = get_db_connect();

     // 購入確定商品一覧を取得
    $data = get_cart_data($dbh, $user_id);
    
     // 合計金額を取得
    $total_price = culc_total_price($data);
    
    // 特殊文字をHTMLエンティティに変換
    $data = entity_assoc_array($data);
    
} catch (PDOException $e) {
    $err_msg[] = $e->getMessage();
}

    // 購入確定処理
    if(purchase($dbh, $user_id, $data) === TRUE) {
        //購入完了画面テンプレートファイル読み込み
        include_once './view/shopping_complete_view.php';
    } else {
        //カート画面テンプレートファイル読み込み
        include_once './view/cart_view.php';
    }

