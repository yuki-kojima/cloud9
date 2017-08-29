<?php


/** ログイン済みかチェック
 */

function check_login() {
    
    // セッション変数からログイン済みか確認
    if (isset($_SESSION['user_id'])) {
      // ログイン済みの場合、商品一覧ページへリダイレクト
      header('Location: index.php');
      exit;
    }
}


/** Cookieからメールアドレス取得
 * @return str メールアドレス
 */
 
function get_email() {
    
    // Cookie情報からメールアドレスを取得
    if (isset($_COOKIE['email'])) {
      $email = $_COOKIE['email'];
    } else {
      $email = '';
    }
    
    return $email;
}
    
