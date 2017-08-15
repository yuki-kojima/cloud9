<?php 
        // 履歴削除処理
        // delete_historyのPOST送信があったらCookie削除
        if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['delete_history']) === TRUE)) {
            // var_dump($_COOKIE['visit_count']);
            setcookie('visit_count', '', time() - 3600);
            // var_dump($_COOKIE['visit_count']);
            setcookie('visit_time', '', time() - 3600);
            
        // topへリダイレクト
        header ('Location: challenge_cookie.php');
        exist;
        }
?>