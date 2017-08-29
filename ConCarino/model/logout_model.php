<?php
/**  ログアウト処理
 */
 
function logout() {
  //POST送信かつlogoutのボタンが押されたらログアウト処理する
  if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['logout']) === TRUE)) {
  
    // セッション開始
    session_start();
  
    // セッション名取得 ※デフォルトはPHPSESSID
    $session_name = session_name();
    // セッション変数を全て削除
    $_SESSION = array();
  
    // ユーザのCookieに保存されているセッションIDを削除
    if (isset($_COOKIE[$session_name])) {
      //もともとのソース
      // setcookie($session_name, '', time() - 42000); 

      // moriyama 回答する前に動作確認させてもらいます。
      $session_paramas = session_get_cookie_params();
			setcookie($session_name, '', time() - 42000, $session_paramas['path'], 
					$session_paramas['domain'], $session_paramas['secure'], 
					$session_paramas['httponly']); 
    }
    // セッションIDを無効化
    session_destroy();
    // ログアウトの処理が完了したらログインページへリダイレクト
    header('Location: login.php');
    exit;
  }
}