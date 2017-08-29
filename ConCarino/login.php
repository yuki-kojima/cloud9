<?php

// 設定ファイルを読み込み
require_once './conf/const.php';

// 関数ファイルを読み込み
require_once './model/common_model.php';
require_once './model/login_model.php';
require_once './model/login_process_model.php';

// 変数初期化
$err_msg = [];
$email = '';

// CookieとSessionの確認（ログアウト処理の確認）
// $session_name = session_name();
// var_dump($_COOKIE[$session_name]);
// var_dump($_SESSION['nickname']);
// var_dump($_SESSION['user_name']);
// var_dump(session_get_cookie_params());

// セッションスタート
session_start();

// ログイン済みだったら商品一覧ページへ遷移
check_login();

// Cookieからメールアドレスを取得
$email = get_email();

try {

// DB接続
$dbh = get_db_connect();

// ログイン処理
login($dbh);

} catch (PDOException $e) {
    $err_msg[] = $e->getMessage();
}


//ログイン画面テンプレートファイル読み込み
include_once './view/login_view.php';