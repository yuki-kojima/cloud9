<?php

/**
 * DBハンドルを取得
 * @return obj $dbh DBハンドル
 * @return array $err_msg　エラーメッセージ
 */
 
 function get_db_connect() {

    try {
         // データベースに接続
         $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
         $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
         
    } catch (PDOException $e) {
         $err_msg[] = '接続できませんでした。理由：'.$e->getMessage();
//   var_dump($err_msg); // 追記by ueda　データベース接続ができたら削除しましょう。
    }
     
    return $dbh;
    return $err_msg;
}
 
 
 /**
   * POSTで送信された名前、投稿内容、投稿日時を取得する
   * 
   * @return str $user_name, $comment, $datetime POSTで送信された投稿者名、投稿内容、投稿日時
   * @return int $user_name_len, $comment_len POSTで送信された投稿者名の文字数、投稿内容の文字数
   */
   
   function get_sent_data() {
   
        // user_name取得
        if(isset($_POST['user_name']) === TRUE) {
            $user_name = htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8');
            $user_name_len = mb_strlen($user_name, 'UTF-8');
            // var_dump($user_name);
            // var_dump($user_name_len);
        }
        // comment取得
        if(isset($_POST['comment']) === TRUE) {
            $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');
            $comment_len = mb_strlen($comment, 'UTF-8');
        }
        // datetimeの取得
        $datetime = date('Y-m-d H:i:s');
        // var_dump($datetime);
        
        return $user_name;
        return $user_name_len;
        return $comment;
        return $comment_len;
        return $datetime;
   }

   
   
 /**
   * POSTで送信された情報のエラーチェックをする
   * 
   * @param str $user_name　投稿者名
   * @param int $user_name_len　投稿者名の文字数
   * @param str $comment 投稿内容
   * @param int $comment_len 投稿内容の文字数
   * @param array $err_msg エラーメッセージ
   * @return array $err_msg エラーメッセージ
   */
   
   function err_chck($user_name, $user_name_len, $comment, $comment_len, $err_msg) {
    //   var_dump($user_name);
    //   var_dump($comment);
    //   var_dump($err_msg);
       
       
        
        // エラーコードの設定
        if ($user_name === '') {
            $err_msg[] = '名前を入力してください';
        }
        if ($user_name_len > 20) {
            $err_msg[] = '名前は20文字以内で設定してください';
        }
        if ($comment === '') {
            $err_msg[] = 'コメントを入力してください';
        }
        if ($comment_len > 100) {
            $err_msg[] = 'コメントは100文字以内で設定してください';
        }
    
    return $err_msg;

   }
   
 /**
   *データベースへ投稿情報を格納
   * 
   * @param str $user_name　投稿者名
   * @param str $comment 投稿内容
   * @param array $err_msg エラーメッセージ
   * @param obj $dbh DBハンドル
   */
   
   function insert_message_data($dbh, $err_msg, $user_name, $comment, $datetime) {
        //エラーじゃない時に以下の処理を行う
        if (count($err_msg) === 0) {
        
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
   }
   
   
 /**
  * POST送信された時に以下の処理を行う
  * 1)送信された内容の取得
  * 2)エラーチェック
  * 3)データベースへ投稿情報を格納
  */
  
  function reg_sent_data($dbh, $err_msg) {
  
    // 変数初期化
    $user_name = '';
    $comment = '';
    $datetime = '';
    $data = [];
    $user_name_len = 0;
    $comment_len = 0;
  
  
    // POSTで送信された時の処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // 1)送信された内容の取得
        get_sent_data();
        
        var_dump($user_name);
        
        // 2)エラーチェック
        err_chck($user_name, $user_name_len, $comment, $comment_len,$err_msg);
        
        // 3)データベースへ投稿情報を格納
        insert_message_data($dbh, $err_msg, $user_name, $comment, $datetime);
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