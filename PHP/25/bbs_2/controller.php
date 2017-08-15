<?php
// 設定ファイルを読み込み
require_once './conf/setting.php';
// 関数ファイルを読み込み
require_once './model/model.php';

$err_msg = [];


try {
    
    // DB接続
    $dbh = get_db_connect();
    
    // 投稿された内容をエラーチェックし、DBヘ登録
    reg_sent_data($dbh, $err_msg);
    
    // 投稿の一覧を取得
    $data = get_msg_data($dbh);
    
    // 特殊文字をHTMLエンティティに変換
    $data = entity_assoc_array($data);
    
}catch (PDOException $e) {
    $err_msg[] = $e->getMessage();
}

// コメント一覧テンプレートファイルを読み込み
include_once './view/view.php';

?>