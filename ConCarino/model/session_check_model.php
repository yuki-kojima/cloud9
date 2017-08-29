<?php

/** ユーザーがログイン済かチェックする&IDを取得
 * ログインしていない・セッションがきれていたらログインページへリダイレクト
 * @return str ユーザーID
 */

function check_user_id() {

  // セッション開始
  session_start();
  
  // セッション変数からuser_id取得
  if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    return $user_id;
  } else {
    // 非ログインの場合、ログインページへリダイレクト
    header('Location: login.php');
    exit;
  }
}