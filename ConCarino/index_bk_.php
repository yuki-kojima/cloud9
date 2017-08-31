<?php

// 設定ファイルを読み込み
require_once './conf/const.php';

// 関数ファイルを読み込み
require_once './model/common_model.php';
require_once './model/session_check_model.php';
require_once './model/index_model.php';

// var_dumpの結果を最後まで表示させる
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_depth', -1);


// 変数初期化
$budget = [];
$budget_len = '';
$err_msg = [];
$data = [];
$user_id = '';

// ログイン済かチェックしユーザーIDを取得
$user_id = check_user_id();

// cookieとsessionの確認
// $session_name = session_name();
// var_dump($_COOKIE[$session_name]);
// var_dump($_SESSION['nickname']);
// var_dump($_SESSION['user_id']);
// var_dump(session_get_cookie_params());


// 予算オプション用配列
$budget = [
    '下限なし',
    1000,
    2000,
    3000,
    4000,
    5000,
    10000,
    15000,
    20000,
    '上限なし'
    ];

$budget_len = count($budget);

try {
    
    // DB接続
    $dbh = get_db_connect();
    
    // カート追加処理
    add_cart($dbh, $user_id);

    // 商品一覧取得
    // 商品検索がされたら以下の処理をする
    if ((($_SERVER['REQUEST_METHOD'] === 'GET') && (isset($_GET['search']) === TRUE)) || (($_SERVER['REQUEST_METHOD'] === 'GET') && (isset($_GET['sort_flg']) === TRUE))) {
     
        // 変数初期化
        $target_m = '';
        $target_f = '';
        $min_budget = '';
        $max_budget = '';
        $category1 = '';
        $category2 = '';
        $category3 = '';
        $category4 = '';
        $category5 = '';
        $category6 = '';
        $sort_flg = '';
        
        // 送信された条件の値を取得
        $target_m = get_get_data('target_m');
        $target_f = get_get_data('target_f');
        $min_budget = get_get_data('min_budget');
        $max_budget = get_get_data('max_budget');
        $category1 = get_get_data('category1');
        $category2 = get_get_data('category2');
        $category3 = get_get_data('category3');
        $category4 = get_get_data('category4');
        $category5 = get_get_data('category5');
        $category6 = get_get_data('category6');
        $sort_flg = get_get_data('sort_flg');

        // エラーチェック
        if ((check_search_err($target_m, $target_f, $min_budget, $max_budget, $category1, $category2, $category3, $category4, $category5, $category6) === TRUE) && (check_sort_err($sort_flg) === TRUE)) {

        // 検索結果を取得
            $data = get_search_data($dbh, $target_m, $target_f, $min_budget, $max_budget, $category1, $category2, $category3, $category4, $category5, $category6, $sort_flg);
        }
    } else {
    // 検索がない場合は全アイテム表示
    
        // 変数初期化
        $sort_flg = '';
        
        // 送信された並べ替えフラグを取得
        $sort_flg = get_get_data('sort_flg');
        
        // エラーチェック
        if (check_sort_err($sort_flg) === TRUE) {
        $data = get_onsale_item_data($dbh, $sort_flg);
        }
    }
    
    
    // 星の数表示用のクラスを追加
    $data = get_index_star_class($data);
    
    // 特殊文字をHTMLエンティティに変換
    $data = entity_assoc_array($data);
    
    
} catch (PDOException $e) {
    //$err_msgに代入されていらっしゃいませんでしたね！
    $err_msg[] = $e->getMessage();
}



//商品一覧画面テンプレートファイル読み込み
include_once './view/index_view.php';