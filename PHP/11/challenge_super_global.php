<?php
    $user_name = '';
    $gender = '';
    $mail_status = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['user_name']) === TRUE) {
            $user_name = htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($_POST['gender']) === TRUE) {
            $gender = htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8');
            }

        if (isset($_POST['mail_status']) === TRUE) {
            $mail_status = htmlspecialchars($_POST['mail_status'], ENT_QUOTES, 'UTF-8');
        }else {
            $mail_status = 'NO';
        }

    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>課題1</title>
    </head>
    <body>
        <h1>課題</h1>
        <!-- 入力フォーム -->
        <form method="post">
            <p><label>お名前：　<input type="text" name="user_name" value=""></label></p>
            <p><label>性別：　
                    <input type="radio" name="gender" value="男">男　
                    <input type="radio" name="gender" value="女">女
                </label>
            </p>
            <p><label><input type="checkbox" name="mail_status" value="OK">お知らせメールを受け取る</label></p>
            <p><input type="submit" value="送信"/></p>
        </form>
        <!-- 値表示 -->
        <?php if ($user_name !== '') { ?>
        <p><?php print 'お名前：　' . $user_name; ?></p>
        <?php } ?>
        <?php if ($gender == '男' || $gender == '女') { ?>
        <p><?php print '性別：　' . $gender; ?></p>
        <?php } ?>
        <?php if ($mail_status !== '') { ?>
        <p><?php print 'お知らせメール：　' . $mail_status; ?></p>
        <?php } ?>
    </body>
    
</html>