<?php

// 設定ファイルを読み込み
require_once './conf/const.php';

// 関数ファイルを読み込み
require_once './model/common_model.php';
require_once './model/session_check_model.php';
require_once './model/cart_model.php';

// 変数初期化
$err_msg = [];
$cmp_msg = '';
$data = [];
$total_price = '';
$user_id = '';

// ログイン済かチェックしユーザーIDを取得
$user_id = check_user_id();

try {

    // DB接続
    $dbh = get_db_connect();
    
    // 購入点数変更処理
    change_quantity($dbh, $user_id);

    // 削除処理
    delete_item($dbh, $user_id);

    // カート内商品一覧を取得
    $data = get_cart_data($dbh, $user_id);

    // 特殊文字をHTMLエンティティに変換
    if (count($data) !== 0) {
        $data = entity_assoc_array($data);
    }
    
    // 合計金額を取得
    $total_price = culc_total_price($data);
    
    
    
} catch (PDOException $e) {
    $err_msg[] = $e->getMessage();
}


//カート画面テンプレートファイル読み込み
include_once './view/cart_view.php';