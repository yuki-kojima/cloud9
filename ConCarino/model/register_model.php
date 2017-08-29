<?php

// ================
// 新規会員登録処理
// ================

/** メールアドレス既登録チェック用
 * @param obj $dbh DBハンドル
 * @param str $email 入力されたメールアドレス
 * @return array DBに既に登録のある同じメールアドレス
 */
 
function get_duplicate_email($dbh, $email) {
    //SQL文作成
    $sql = 'SELECT email 
        FROM user_master 
        WHERE email = :email';
    // SQL実行準備
    $stmt = $dbh->prepare($sql);
    // SQLのプレースホルダに値をバインド
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    // SQL実行
    $stmt->execute();
    
    $row = $stmt->fetch();
    
    // var_dump($row);
    return $row;
}

/** エラーチェック
 * @param obj $dbh DBハンドル
 * @param str $user_name ユーザー名
 * @param str $nickname ニックネーム
 * @param str $email Eメールアドレス
 * @param str $postcode 郵便番号
 * @param str $pref 都道府県コード
 * @param str $address1 市区町村・番地
 * @return bool $err_check エラーが一つでもあればfalse
 */
 
function check_err($dbh, $user_name, $nickname, $email, $passwd, $postcode, $pref, $address1) {
    // エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    // エラーフラグの初期化。エラーがあればfalseへ
    $check_flg = true;
    
    // エラーコードの設定
    // お名前
    if ($user_name === '') {
        $err_msg[] = 'お名前を入力してください';
        $check_flg = FALSE;
    }
    // ニックネーム
    if ($nickname === '') {
        $err_msg[] = 'ニックネームを入力してください';
        $check_flg = FALSE;
    }
    // メールアドレス
    // 正規表現
    $email_regex = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD';
    // 既に同じメールアドレスが登録済みの場合以下の配列に代入
    $email_dubulication = get_duplicate_email($dbh, $email);
    // var_dump($email_dubulication);
    if ($email === '') {
        $err_msg[] = 'メールアドレスを入力してください';
        $check_flg = FALSE;
    } elseif (preg_match($email_regex, $email) === 0) {
        $err_msg[] = 'メールアドレスの形式が正しくありません';
        $check_flg = FALSE;
    } elseif ($email_dubulication !== FALSE) {
        $err_msg[] = '既に登録済みのメールアドレスです';
        $check_flg = FALSE;
    }
    // ログインパスワード
    $passwd_len = mb_strlen($passwd, 'UTF-8');
    $passwd_regex = '/^[a-zA-Z0-9]+$/';
    if ($passwd === '') {
        $err_msg[] = 'パスワードを入力してください';
        $check_flg = FALSE;
    } elseif (($passwd_len) < 6 || (preg_match($passwd_regex, $passwd) === 0)){
        $err_msg[] = 'パスワードは半角英数字6文字以上で設定してください';
        $check_flg = FALSE;
    }
    // 郵便番号
    $postcode_regex = '/^[0-9]{3}-[0-9]{4}$/';
    if ($postcode === '') {
        $err_msg[] = '郵便番号を入力してください';
        $check_flg = FALSE;
    } elseif (preg_match($postcode_regex, $postcode) === 0) {
        $err_msg[] = '郵便番号の形式が正しくありません';
        $check_flg = FALSE;
    }
    // 都道府県コード
    if (($pref === '') || ($pref === 0)) {
        $err_msg[] = '都道府県を選択してください';
        $check_flg = FALSE;
    }
    // 市区町村・番地
    if ($address1 === '') {
        $err_msg[] = '市区町村・番地を入力してください';
        $check_flg = FALSE;
    }
    
    return $check_flg;
}

/** DBのuser_masterへ新会員情報を登録
 * @param str $user_name ユーザー名
 * @param str $nickname ニックネーム
 * @param str $email Eメールアドレス
 * @param str $postcode 郵便番号
 * @param str $pref 都道府県コード
 * @param str $address1 市区町村・番地
 * @param str $address2 建物名など
 */
 
 function insert_new_member_data($dbh, $user_name, $nickname, $email, $passwd, $postcode, $pref, $address1, $address2) {
    
    // 作成時間を取得
    $created_at = date('Y-m-d H:i:s');
    
    
    // user_masterへ情報を登録
    // SQL文を作成
    $sql = 'INSERT INTO 
                user_master (
                user_name,
                nickname,
                email,
                passwd,
                postcode,
                pref,
                address1,
                address2,
                created_at
                )
            VALUES (
                :user_name,
                :nickname,
                :email,
                :passwd,
                :postcode,
                :pref,
                :address1,
                :address2,
                :created_at
                )';
    
    // SQL実行準備
    $stmt = $dbh->prepare($sql);
    
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(':user_name', $user_name, PDO::PARAM_STR);
    $stmt->bindValue(':nickname', $nickname, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':passwd', $passwd, PDO::PARAM_STR);
    $stmt->bindValue(':postcode', $postcode, PDO::PARAM_STR);
    $stmt->bindValue(':pref', $pref, PDO::PARAM_STR);
    $stmt->bindValue(':address1', $address1, PDO::PARAM_STR);
    $stmt->bindValue(':address2', $address2, PDO::PARAM_STR);
    $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);
    
    // SQLを実行
    $stmt->execute();

 }


/** 新規会員登録がされた時に以下の処理を行う
 * 1)送信された内容の取得
 * 2)エラーチェック
 * 3)データベースへ情報を格納
 * @param obj $dbh DBハンドル
 */
 
 function reg_member_data($dbh) {
    
    // 変数初期化
        $user_name = '';
        $nickname = '';
        $email = '';
        $passwd = '';
        $postcode = '';
        $pref = '';
        $address1 = '';
        $address2 = '';
    
    // POST送信がされた時の処理
    if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['register_member']) === TRUE)) {
        
        // 1)送信された情報の取得
        $user_name = get_post_data('user_name');
        $nickname = get_post_data('nickname');
        $email = get_post_data('email');
        $passwd = get_post_data('passwd');
        $postcode = get_post_data('postcode');
        $pref = get_post_data('pref');
        $address1 = get_post_data('address1');
        $address2 = get_post_data('address2');
        
        // 2) エラーチェック
        if (check_err($dbh, $user_name, $nickname, $email, $passwd, $postcode, $pref, $address1) === TRUE) {
            
        // 3) DBに情報登録
        insert_new_member_data($dbh, $user_name, $nickname, $email, $passwd, $postcode, $pref, $address1, $address2);
        
        // 4) 登録完了画面へ遷移
        header('Location: ./register_complete.php');
        exit;
        }
        
    }
}