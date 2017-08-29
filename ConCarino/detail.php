<?php

// 設定ファイルを読み込み
require_once './conf/const.php';

// 関数ファイルを読み込み
require_once './model/common_model.php';
require_once './model/session_check_model.php';
require_once './model/index_model.php';
require_once './model/detail_model.php';

// 変数初期化
$user_id = '';
$err_msg = [];
$cmp_msg = [];
$data= [];
$review_data = [];
// ログイン済かチェックしユーザーIDを取得
$user_id = check_user_id();

try {
    
    // DB接続
    $dbh = get_db_connect();
    
    // カート追加処理
    add_cart($dbh, $user_id);
    
    // 口コミ投稿処理
    reg_review_data($dbh);

    // 商品情報取得
    $data = get_detail($dbh);
    
    
    // 商品口コミ一覧取得
    $review_data = get_review($dbh, $review_data);


    // 特殊文字をHTMLエンティティに変換
    $data = entity_array($data);
    $review_data = entity_assoc_array($review_data);
    
} catch (PDOException $e) {
    $err_msg[] = $e->getMessage();
}


//商品詳細画面テンプレートファイル読み込み
include_once './view/detail_view.php';