<?php


// =========================
// 商品情報取得処理
// =========================


/** item_idエラーチェック用
 * @param obj $dbh DBハンドル
 * @param srt $item_id　商品ID
 * @return array 商品IDが登録されていたら代入
 */
 
function get_item_id($dbh, $item_id) {
    //SQL文作成
    $sql = 'SELECT item_id
        FROM item_master 
        WHERE item_id = :item_id';
    // SQL実行準備
    $stmt = $dbh->prepare($sql);
    // SQLのプレースホルダに値をバインド
    $stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
    // SQL実行
    $stmt->execute();
    
    $row = $stmt->fetch();
    
    return $row;
}

/** item_idエラーチェック
 * @param obj $dbh DBハンドル
 * @param srt $item_id　商品ID
 * @return bool $check_flg 一つでもエラーならfalse
 */
 
 function check_item_id($dbh, $item_id) {
     
     // チェック用フラグ初期化
     $check_flg = TRUE;
     
     //　DBに登録されている商品IDか
     $check_item_id = get_item_id($dbh, $item_id);
     
     if($item_id === '') {
        header('Location: detail_err.php');
           exit;
     } elseif ($check_item_id === FALSE) {
         header('Location: detail_err.php');
           exit;
     }
 }

/**商品情報の取得
 * @param obj $dbh DBハンドル
 * @param str $item_id 商品ID
 * @return array　商品情報
 */
 
 function get_db_detail($dbh, $item_id) {
     
    // SQL文作成
    $sql = 'SELECT           
                item_master.item_id,
                item_name,
                description,
                price,
                img,
                stock,
                status,
                target_f,
                target_m,
                TRUNCATE(avg(rate),1) as avg_rate
            FROM
                item_master 
            INNER JOIN
                item_stock
            ON
                item_master.item_id = item_stock.item_id
            INNER JOIN
                item_review
            ON 
                item_master.item_id = item_review.item_id
            WHERE item_master.item_id = :item_id';
            
    // SQL実行準備
    $stmt = $dbh->prepare($sql);
    // SQLのプレースホルダーに値をバインド
    $stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
    // SQL実行
    $stmt->execute();
    // レコードを取得
    $data = $stmt->fetch();
    return $data;
 }
 
 /** 星の数表示用のクラス名を設定
  * @param array $data 商品情報
  * @return array $data　商品情報
  */
  function get_star_class($data) {
     if ((0 < $data['avg_rate']) && ($data['avg_rate'] < 10) ) {$data['star_rate'] = 05;}
         elseif ((10 <= $data['avg_rate']) && ($data['avg_rate'] < 15 )) {$data['star_rate'] = 10;}
         elseif ((15 <= $data['avg_rate']) && ($data['avg_rate'] < 20 )) {$data['star_rate'] = 15;}
         elseif ((20 <= $data['avg_rate']) && ($data['avg_rate'] < 25 )) {$data['star_rate'] = 20;}
         elseif ((25 <= $data['avg_rate']) && ($data['avg_rate'] < 30 )) {$data['star_rate'] = 25;}
         elseif ((30 <= $data['avg_rate']) && ($data['avg_rate'] < 35 )) {$data['star_rate'] = 30;}
         elseif ((35 <= $data['avg_rate']) && ($data['avg_rate'] < 40 )) {$data['star_rate'] = 35;}
         elseif ((40 <= $data['avg_rate']) && ($data['avg_rate'] < 45 )) {$data['star_rate'] = 40;}
         elseif ((45 <= $data['avg_rate']) && ($data['avg_rate'] < 50 )) {$data['star_rate'] = 45;}
         elseif ($data['avg_rate'] === 50 ) {$data['star_rate'] = 50;}
         
         return $data;
  }
  
  /** 商品情報取得処理まとめ
   * @param obj $dbh DBハンドル
   * @return array $data　商品情報
   */
   
   function get_detail($dbh) {
      if(isset($_GET['item_id'])) {
    // POSTで送信した場合
    //   if(($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['item_id']) === TRUE)) {
           
           // 変数初期化
           $item_id = '';
           $data = [];
           
           // GETの値を取得
          $item_id = get_get_data('item_id');
          // POSTの値を取得
          // $item_id = get_post_data('item_id');
          
          // item_idエラーチェック
          check_item_id($dbh, $item_id);

           // DBから商品情報を取得
           $data = get_db_detail($dbh, $item_id);
           
          // 星の数表示用クラスを商品情報に追加
          $data = get_star_class($data);
           
           return $data;
       } else {
           header('Location: detail_err.php');
           exit;
       }
   }
   
// =========================
// 口コミ投稿処理
// =========================


/** POSTで送信された情報のエラーチェックをする
   * @param str $rate 星の数
   * @param str $give_given もらった・あげたフラグ
   * @param str $comment 口コミ内容
   * @return bool $chck_flg エラーがあったらfalse,なければtrue
   */
   
   function err_chck($rate, $give_given, $comment) {

        // 変数初期化
        $comment_len = 0;
       
        // エラー文はグローバルの$err_msgに代入
        global $err_msg;
        
        // エラーの有無を確認するためのフラグ。エラーがあるとfalse
        $chck_flg = TRUE;
        
        // 投稿者名・投稿内容の文字数を代入
        $comment_len = mb_strlen($comment, 'UTF-8');
    
        // エラーコードの設定
        // 星の数
        if ($rate === '') {
            $err_msg[] = '星の数を選択してください';
            $chck_flg = false;
        } elseif(preg_match('/^[1-5][05]$/', $rate) === 0){
            $err_msg[] = '星の数の値が不正です';
            $chck_flg = false;
        }
        // もらった・あげたフラグ
        if ($give_given === '') {
            $err_msg[] = 'あげた・もらったを選択してください';
            $chck_flg = false;
        } elseif(preg_match('/^[01]$/', $give_given) === 0){
            $err_msg[] = 'あげた・もらったチェックの値が不正です';
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
   
/**データベースへ投稿情報を格納
 * @param str $item_id 商品ID
 * @param str $user_id　投稿者のユーザーID
 * @param str $rate 星の数
 * @param str $give_given もらった・あげたフラグ
 * @param str $comment 口コミ内容
 * @param obj $dbh DBハンドル
 */

function insert_review_data($dbh, $item_id, $user_id, $rate, $give_given, $comment) {
   
   // 投稿時間を取得
   $created_at = date('Y-m-d H:i:s');
   
    // 投稿情報格納用SQL文を作成
    $sql = 'INSERT INTO item_review (
                user_id,
                item_id,
                comment,
                rate,
                give_given,
                created_at ) 
            VALUES (
                :user_id,
                :item_id,
                :comment,
                :rate,
                :give_given,
                :created_at )';

    // SQL実行する準備
    $stmt = $dbh->prepare($sql);
    
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
    $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindValue(':rate', $rate, PDO::PARAM_STR);
    $stmt->bindValue(':give_given', $give_given, PDO::PARAM_STR);
    $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);
    
    // SQLを実行
    $stmt->execute();
}

 /** 口コミがPOST送信された時に以下の処理を行う
  * 1)送信された内容の取得
  * 2)エラーチェック
  * 3)データベースへ投稿情報を格納
  */
  
  function reg_review_data($dbh) {
    
    //エラーメッセージと完了メッセージをグローバルの$err_msgに代入
   global $err_msg;
   global $cmp_msg;
  
    // 変数初期化
    $item_id = '';
    $user_id = '';
    $rate = '';
    $give_given = '';
    $comment = '';
    $created_at = '';
 
    // POSTで送信された時の処理
    if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['post_review']) === TRUE)) {
        
        // 1)送信された内容の取得
        $item_id = get_post_data('item_id');
        $user_id = $_SESSION['user_id'];
        $rate = get_post_data('rate');
        $give_given = get_post_data('give_given');
        $comment = get_post_data('comment');
        
        // 2)エラーチェック
        if(err_chck($rate, $give_given, $comment) === true) {
        
        // 3)データベースへ投稿情報を格納
            if(insert_review_data($dbh, $item_id, $user_id, $rate, $give_given, $comment) === FALSE) {
                $err_msg[] = '投稿処理がエラーとなりました';
            } else {
                $cmp_msg[] = '口コミの投稿が完了しました';
            }
        }
    }
 }
 
 
// =========================
// 口コミ投稿処理
// =========================

/**
 * 口コミ一覧を取得する
 * @param obj $dbh DBハンドル
 * @param str $item_id 商品ID
 * @return array 口コミ一覧データ
 */
   
   function get_review_data($dbh, $item_id) {
       
       // SQL文作成
       $sql = 'SELECT
                nickname, 
                comment,
                give_given,
                rate,
                item_review.created_at
              FROM
                item_review
              INNER JOIN
                user_master
              ON
                item_review.user_id = user_master.user_id
              WHERE
                item_id = :item_id
              ORDER BY
                item_review.created_at DESC';
                
        // SQL実行準備
        $stmt = $dbh->prepare($sql);
        // SQLのプレースホルダに値をバインド
        $stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
        // SQL実行
        $stmt->execute();
        
        $rows = $stmt->fetchAll();
        return $rows;
   }
   
  
  /** 口コミ情報取得処理まとめ
   * @param obj $dbh DBハンドル
   * @oaram arry $review_data 口コミ一覧
   */
   
 
 function get_review($dbh, $review_data){
     
    // 変数初期化
    $item_id = '';
    $review_data = [];
    
    // $item_idを取得
    $item_id = get_get_data('item_id');
    
    // 口コミ一覧を取得
    $review_data = get_review_data($dbh, $item_id);

    
    return $review_data;
 }