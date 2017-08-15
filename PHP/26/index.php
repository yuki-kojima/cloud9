<?php
// 設定ファイルを読み込み
require_once './conf/const.php';
// 関数ファイルを読み込み
require_once './model/function.php';

$err_msg = [];
$data = [];
$img_dir = './img/';

try {
    
    // DB接続
    $dbh = get_db_connect();
    
    
    // 商品一覧を取得
    $data = get_item_data($dbh);
    
    // 特殊文字をHTMLエンティティに変換
    $data = entity_assoc_array($data);
    
//} catch (PDOExeption $e) {    // コメントアウト by ueda
} catch (PDOException $e) {     // 訂正し追加 by ueda
    var_dump($e->getMessage()); // 確認用（後ほど削除してくださいね） by ueda
    $err_msg[] = $e->getMessage();;
}

// 購入画面のテンプレートファイルを読み込み
include_once './view/index.php';
