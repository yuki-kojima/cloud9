<?php

// 設定ファイルを読み込み
require_once './conf/const.php';
// 関数ファイルを読み込み
require_once './model/function.php';

$err_msg = [];
$cmp_msg = [];
$data = [];
$img_dir = './img/';

try {
    
    // DB接続
    $dbh = get_db_connect();

    // 新規登録処理
    reg_drink_data($dbh);
    
    // 在庫更新処理
    update_stock($dbh);

    // 公開ステータス変更処理
    change_status($dbh);
    
    // 商品一覧を取得
    $data = get_drink_data($dbh);
    
    // 特殊文字をHTMLエンティティに変換
    $data = entity_assoc_array($data);


} catch (PDOExeption $e) {
    $err_msg[] = $e->getMessage();
}


// 商品管理画面テンプレートファイルを読み込み
include_once './view/tool.php';