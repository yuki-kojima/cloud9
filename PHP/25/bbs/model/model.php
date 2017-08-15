<?php

/**
 * DBハンドルを取得
 * @return obj $dbh DBハンドル
 */
 
 function get_db_connect() {
     
    // ★志賀さんからのアドバイス
    // 実は、例外は関数内でキャッチされない場合はその関数を呼び出している側の世界に飛び出していきます。
    // つまり、get_db_connect関数の中でtry~catch節を書かなければ、例外は関数の世界を飛び出して、
    // controller.phpの14行目で例外が起こったのと同じ扱いになります。
    // そして、controller.phpの25行目のcatch節でキャッチされて、$err_msgにエラーメッセージが代入されるのです。

     // データベースに接続
     $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
     $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    return $dbh;
}
 
 
 /**
   * POSTで送信された名前、投稿内容、投稿日時を取得する
   * 
   * @return str $str POSTで送信された情報
   */
   
   function get_post_data($key) {
       
       $str = '';
       if (isset($_POST[$key]) === TRUE) {
           $str = $_POST[$key];
       }
       
       return $str;
   }
   
 /**
   * POSTで送信された情報のエラーチェックをする
   * 
   * @param str $user_name　投稿者名
   * @param str $comment 投稿内容
   * @return bool $chck_flg エラーがあったらfalse,なければtrue
   */
   
   function err_chck($user_name, $comment) {
    //   var_dump($user_name);
    //   var_dump($comment);
       
        // 変数初期化
        $user_name_len = 0;
        $comment_len = 0;
       
        // エラー文はグローバルの$err_msgに代入
        global $err_msg;
        
        // エラーの有無を確認するためのフラグ。エラーがあるとfalse
        $chck_flg = true;
        
        // 投稿者名・投稿内容の文字数を代入
        $user_name_len = mb_strlen($user_name, 'UTF-8');
        $comment_len = mb_strlen($comment, 'UTF-8');
    
        // エラーコードの設定
        if ($user_name === '') {
            $err_msg[] = '名前を入力してください';
            $chck_flg = false;
        }
        if ($user_name_len > 20) {
            $err_msg[] = '名前は20文字以内で設定してください';
            $chck_flg = false;
        }
        if ($comment === '') {
            $err_msg[] = 'コメントを入力してください';
            $chck_flg = false;
        }
        if ($comment_len > 100) {
            $err_msg[] = 'コメントは100文字以内で設定してください';
            $chck_flg = false;
        }
    
    // エラーが一つでもあればfalse,なければtrueが返される
    return $chck_flg;

   }
   
 /**
   *データベースへ投稿情報を格納
   * 
   * @param str $user_name　投稿者名
   * @param str $comment 投稿内容
   * @param obj $dbh DBハンドル
   */
   
   function insert_message_data($dbh, $user_name, $comment) {
       // 投稿時間を取得
       $datetime = date('Y-m-d H:i:s');
       
        // 投稿情報格納用SQL文を作成
        $sql = 'insert into post (user_name, user_comment, create_datetime) values (?, ?, ?)';
    
        // SQL実行する準備
        $stmt = $dbh->prepare($sql);
        
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
        $stmt->bindValue(2, $comment, PDO::PARAM_STR);
        $stmt->bindValue(3, $datetime, PDO::PARAM_STR);
        
        // SQLを実行
        $stmt->execute();
   }
   
   
 /**
  * POST送信された時に以下の処理を行う
  * 1)送信された内容の取得
  * 2)エラーチェック
  * 3)データベースへ投稿情報を格納
  */
  
  function reg_sent_data($dbh) {
  
    // 変数初期化
    $user_name = '';
    $comment = '';
    $datetime = '';

 
    // POSTで送信された時の処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // 1)送信された内容の取得
        $user_name = get_post_data('user_name');
        $comment = get_post_data('comment');
        
        // 2)エラーチェック
        if(err_chck($user_name, $comment) === true) {
        
        // 3)データベースへ投稿情報を格納
            insert_message_data($dbh, $user_name, $comment);
        }
    }
 }
 
 
 /**
  * クエリを実行しその結果を配列で取得する
  * 
  * @param obj $dbh DBハンドル
  * @param str $sql SQL文
  * @return array 結果配列データ
  */
  
  function get_as_array($dbh, $sql) {
      
      try {
          // SQL文を実行する準備
          $stmt = $dbh->prepare($sql);
          // SQL実行
          $stmt->execute();
          // レコードの取得
          $rows = $stmt->fetchAll();
      } catch (PDOException $e) {
          echo '接続できませんでした。理由：'.$e->getMessage();
      }
      
      return $rows;
  }
  
  
/**
 * コメント一覧を取得する
 * 
 * @param obj $dbh DBハンドル
 * @return array コメント一覧データ
 */
   
   function get_msg_data($dbh) {
       
       // SQL文作成
       $sql = 'select user_name, user_comment, create_datetime from post order by create_datetime desc';
       
       // クエリ実行
       return get_as_array($dbh, $sql);
   }
   
/**
 * 特殊文字をHTMLエンティティに変換する
 * @param str $str 変換前文字
 * @return str 変換後文字
 */
 
 function entity_str($str) {
     return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
     
 }
 
/**
 * 特殊文字をHTMLエンティティに変換する(2次元配列の値)
 * @param array $assoc_array 変換前配列
 * @return array 変換後配列
 */
 
 function entity_assoc_array($assoc_array) {
     
     foreach ($assoc_array as $key => $value) {
         foreach ($value as $keys => $values) {
             // 特殊文字をHTMLえティティに変換
             $assoc_array[$key][$keys] = entity_str($values);
         }
     }
     
     return $assoc_array;
 }
   

   
   
   
   ?>