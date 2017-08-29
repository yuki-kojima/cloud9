<?php

/** 入力されたEmailをCookieに保存
 *  @param str $email メールアドレス
 */
 
 function setcookie_email($email) {
  // メールアドレスをCookieへ保存
  setcookie('email', $email, time() + 60 * 60 * 24 * 365);
  
 }
 

/** メールアドレスを元にパスワード・ユーザーIDの取得
 * @param obj $dbh DBハンドル
 * @param str $email
 * @return array パスワード・ユーザーID
 */ 

function get_db_data($dbh, $email) {
  
  // SQL文作成
  $sql = 'SELECT
            passwd,
            user_id,
            nickname
          FROM user_master
          WHERE email = :email';
  
  // SQL実行準備
  $stmt = $dbh->prepare($sql);
  // SQL文のプレースホルダーに値をバインド
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  // SQL文実行
  $stmt->execute();
  
  // 値を取得
  $row = $stmt->fetch();
  
  return $row;
}

/** エラーチェック
 * @param str $email POSTで送信されたパスワード
 * @param str $passwd POSTで送信されたパスワード
 * @param str $db_passwd メールアドレスを元にDBから取得したパスワード
 * @return bool $check_flg 一致しなかったらfalse
 */

function check_login_email($email, $passwd, $db_passwd) {
  
  // エラーメッセージはグローバルの$err_msgに代入
  global $err_msg;
  
  // チェックフラグ初期化
  $check_flg = TRUE;
  
  // メールアドレス入力チェック
  if ($email === '') {
    $err_msg [] = 'メールアドレスを入力してください';
    $check_flg = FALSE;
  // メールアドレスが登録されているかチェック
  } elseif (!isset($db_passwd)) {
    $err_msg [] = '登録されていないメールアドレスです。';
    $check_flg = FALSE;
  }
  
  // パスワード入力チェック
  if ($passwd === '') {
    $err_msg [] = 'パスワードを入力してください。';
    $check_flg = FALSE;
  // パスワードが一致するかチェック
  } elseif ($db_passwd !== $passwd) {
    $err_msg [] = 'パスワードが一致しません。';
    $check_flg = 'FALSE';
  }
  return $check_flg;
}


/** ユーザID・ニックネームの取得ができたら商品一覧ページへ、できなかったらfalse
 * @param str $db_user_id メールアドレスを元にDBから取得したユーザーID
 * @param str $db_nickname メールアドレスを元にDBから取得したニックネーム
 * @return bool $check_flg  ユーザーID、ニックネームのどちらかが取得できなければfalse
 */
 
function get_user_data($db_user_id, $db_nickname){
  
  // エラーメッセージはグローバルの$err_msgに代入
  global $err_msg;
  
  // チェックフラグ初期化
  $check_flg = TRUE;
  
  // 登録データを取得できたか確認
  if ((isset($db_user_id)) && (isset($db_nickname))) {
    // セッション変数にuser_id,ニックネームを保存
    $_SESSION['user_id'] = $db_user_id;
    $_SESSION['nickname'] = $db_nickname;
    // ログイン済みユーザを商品一覧へへリダイレクト
    header('Location: index.php');
    exit;
  } else {
    $err_msg [] = 'ユーザー情報が取得できませんでした。';
    $check_flg = FALSE;
  }
  return $check_flg;
}
  

/** ログイン処理
 * @param obj $dbh DBハンドル
 */

function login($dbh) {
  if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['login']) === TRUE)) {
      
      // 変数初期化
      $email ='';
      $passwd ='';
      $data = [];
      $db_passwd = '';
      $db_user_id = '';
      $db_nickname = '';
    
      // POST値取得
      $email = get_post_data('email'); // POST送信されたメールアドレス
      $passwd = get_post_data('passwd'); // POST送信されたパスワード
      
      // メールアドレスをcookieに保存
      setcookie_email($email);
      
      // メールアドレスに基づきDB上の情報取得
      $data = get_db_data($dbh, $email); // DBのユーザーデータ
      $db_passwd = $data['passwd']; // DBのパスワード
      $db_user_id = $data['user_id']; // DBのユーザーID
      $db_nickname = $data['nickname']; // DBのニックネーム
      
      // エラーチェック
      if (check_login_email($email, $passwd, $db_passwd) === TRUE) {
        
      // セッション変数にユーザーID、ニックネームを保存し、商品一覧ページへ
        get_user_data($db_user_id, $db_nickname);
      } 
  }
}