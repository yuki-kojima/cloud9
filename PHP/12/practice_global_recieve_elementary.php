<?php
    $user_name = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['user_name']) === TRUE) {
            $user_name = htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8');
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>課題２</title>
    </head>
    <body>
        <p>
            <?php
                if ($user_name !== ''){
                    print 'ようこそ' . $user_name . 'さん';
                }else {
                    print '名前を入力してください';
                }
            ?>
        </p>
    </body>
    
</html>