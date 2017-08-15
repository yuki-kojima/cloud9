<?php 
    
    session_start();
    // 履歴削除処理
    $session_name = session_name();
    //セッション変数を全て削除
    $_SESSION = array();
    // Cookieに保存されているセッションIDを削除
    if (isset($_COOKIE[$session_name])) {
        setcookie($session_name, '', time() - 3600);
    }
    // セッションIDを無効化
    session_destroy();
    // topへリダイレクト
    header ('Location: challenge_session.php');
    exist;

?>