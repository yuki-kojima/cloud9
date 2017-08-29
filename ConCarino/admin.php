<?php

// 設定ファイルを読み込み
require_once './conf/const.php';

// 関数ファイルを読み込み
require_once './model/common_model.php';
require_once './model/admin_model.php';

// 変数初期化
$err_msg = [];
$cmp_msg = '';
$data = [];

try {

    // DB接続
    $dbh = get_db_connect();

    // 新規登録処理
    reg_item_data($dbh);
    
    // 在庫更新処理
    update_stock($dbh);

    // 公開ステータス変更処理
    change_status($dbh);
    
    // 商品削除処理
    delete_item($dbh);
    
    // 商品一覧を取得
    $data = get_item_data($dbh);
    
    // 特殊文字をHTMLエンティティに変換
    $data = entity_assoc_array($data);
    
    
} catch (PDOException $e) {
    $err_msg[] = $e->getMessage();
}
//新規会員登録画面テンプレートファイル読み込み
include_once './view/admin_view.php';