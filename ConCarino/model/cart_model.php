<?php 

// ================
// カート内商品情報取得
// ================


/** カートに入っている商品一覧を取得する
 * @param obj $dbh DBハンドル
 * @param str $user_id ユーザーID
 * @return array カート内商品一覧データ
 */

function get_cart_data($dbh, $user_id) {
    
    // SQL文作成
    $sql = 'SELECT
                cart.item_id,
                item_name,
                price,
                img,
                quantity,
                stock,
                status
            FROM 
                item_master 
            INNER JOIN 
                cart
            ON 
                item_master.item_id = cart.item_id
            INNER JOIN
                item_stock
            ON
                cart.item_id = item_stock.item_id
            WHERE user_id = :user_id
            ORDER BY cart.created_at';
    // SQL実行準備
    $stmt = $dbh->prepare($sql);
    // SQLのプレースホルダに値をバインド
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    // SQL実行
    $stmt->execute();
    
    // レコードの取得
    $rows = $stmt->fetchAll();
    
    return $rows;
}

/** カート内商品の合計金額を取得する
 * @param array $data カート内の商品一覧データ
 * @return int $total_price 合計金額
 */

function culc_total_price($data) {
    $total_price = 0;
    foreach ($data as $key => $value) {
        $total_price = $total_price + ($value['quantity'] * $value['price']);
    }
    return $total_price;
}

// ================
// 購入点数更新処理
// ================

/** 更新された購入点数のエラーチェック
 * @param int $change_quantity 購入点数
 * @return bool $chck_flg エラーが一つでもあればFALSE
 */
 
function quantity_err_chck($change_quantity) {
    
    // エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    // エラーチェック用フラグ
    $chck_flg = TRUE;
    
    if ($change_quantity === '') {
        $err_msg[] = '購入点数を入力してください';
        $chck_flg = FALSE;
    }elseif (preg_match('/^[0-9]+$/', $change_quantity) === 0) {
        $err_msg[] = '購入点数は0以上の正の整数で入力してください';
        $chck_flg = FALSE;
    }

    return $chck_flg;
}

/** 更新された購入点数をDBのcartに反映する
 *  @param obj $dbh DBハンドル
 *  @param str $change_quantity 在庫数
 *  @param str $item_id 商品ID
 *  @param str $user_id ユーザーID
 */
 
function insert_change_quantity_data($dbh, $change_quantity, $item_id, $user_id) {
    
    // 完了メッセージはグローバルの$cmp_msgに代入
    global $cmp_msg;
    
    // updated_at取得
    $updated_at = date('Y-m-d H:i:s');
    
    // cartの更新
    // SQL文の作成
    $sql = 'UPDATE
                cart
            SET
                quantity = :quantity,
                updated_at = :updated_at
            WHERE
                item_id = :item_id
            AND
                user_id = :user_id';
            
    // SQL文実行の準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(':quantity', $change_quantity, PDO::PARAM_STR);
    $stmt->bindValue(':updated_at', $updated_at, PDO::PARAM_STR);
    $stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    //SQL文を実行
    $stmt->execute();

    
    // 完了メッセージを代入
    $cmp_msg = '購入点数を更新しました';

}



/** 在庫数更新処理が行われた時に以下の処理をする
 * @param obj $dbh DBハンドル  
 * @param str $use_id ユーザーID
 */
 
 function change_quantity($dbh, $user_id) {
     
     if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['change']) === TRUE)) {
         
        // 変数初期化
        $change_quantity = '';
        $item_id = '';
         
        // 1)送信された情報取得
        $change_quantity = get_post_data('change_quantity');
        $item_id = get_post_data('item_id');
        
        
        // 2)エラーチェック
        if (quantity_err_chck($change_quantity) === TRUE) {
        
        // 3)item_stockの在庫数を更新
            insert_change_quantity_data($dbh, $change_quantity, $item_id, $user_id);
        }

         
     }
 }
 
// ================
// 削除処理
// ================
 
 /** POSTで送られてきたitem_idの情報を
  * cartから削除する
  * @param obj $dbh DBハンドル
  * @param str $delete_id 削除対象item_id
  * @param str $user_id ユーザーID
  */
  
function delete_cart_item_info($dbh, $delete_id, $user_id) {
    
    // 完了メッセージの設定
    global $cmp_msg;
    
    // cartから削除
    // SQL文を作成
    $sql = 'DELETE FROM cart WHERE item_id = :delete_id AND user_id = :user_id';
    // SQL文の実行準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(':delete_id', $delete_id, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    // SQL実行
    $stmt->execute();
    
    // 完了メッセージ
    $cmp_msg = '商品をカートから削除しました';

}

 /** 削除処理
  * @param obj $dbh DBハンドル
  */
  
  function delete_item($dbh, $user_id) {
      if((($_SERVER['REQUEST_METHOD']) === 'POST') && (isset($_POST['delete']) === TRUE)) {
          
          // 変数初期化
          $delete_id = '';
          
          // 1)送信された値の取得
          $delete_id = get_post_data('delete_id');
          
          // 2)該当商品をDBから削除
          delete_cart_item_info($dbh, $delete_id, $user_id);
      }
  }
 
// // ================
// // 購入確定処理
// // ================


//  /** 在庫切れ・取り扱い終了チェック
//   * @param obj $dbh DBハンドル
//   * @param array $data カート内商品一覧
// 　* @return bool $chck_flg エラーが一つでもあればFALSE
// 　*/

// function purchase_err_chck($data) {
    
//     // エラーメッセージはグローバルの$err_msgに代入
//     global $err_msg;
    
//     // エラーチェック用フラグ
//     $chck_flg = TRUE;

//     foreach ($data as $key => $value) {

//         // 商品が非公開になっていないかチェック
//         if ($value['status'] === 1) {
//             $err_msg[] = $value['item_name'].'の取り扱いが終了しました';
//             $chck_flg = FALSE;
//         }
//         // 在庫切れになっていないかチェック
//         if ($value['stock'] === 0) {
//             $err_msg[] = $value['item_name'].'が在庫切れになりました';
//             $chck_flg = FALSE;
//         } else if ($value['stock'] < $value['quantity']){
//             $err_msg[] = $value['item_name'].'の在庫が不足しています。残りあと'.$value['stock'].'個です。';
//             $chck_flg = FALSE;
//         }
//     }
//     return $chck_flg;
// }

 
//  /** 購入された商品の在庫をitem_stockから購入点数からマイナスし
//  *  item_masterの更新日時を更新
//  * @param obj $dbh DBハンドル
//  * @param array $data 購入された商品一覧(カートテーブルの情報)
//  * @param date $created_at 購入日時
//  */

// function update_sold_stock($dbh, $data, $created_at){

//     // item_stockを更新
//     foreach ($data as $key => $value) {
        
//         // SQL文作成
//         $sql = 'UPDATE item_stock
//                 SET
//                     stock = stock - :quantity,
//                     updated_at = :updated_at
//                 WHERE item_id = :item_id';
//         // SQL文の実行準備
//         $stmt = $dbh->prepare($sql);
//         // SQL文のプレースホルダに値をバインド
//         $stmt->bindValue(':quantity', $value['quantity'], PDO::PARAM_STR);
//         $stmt->bindValue(':updated_at', $created_at, PDO::PARAM_STR);
//         $stmt->bindValue(':item_id', $value['item_id'], PDO::PARAM_STR);
//         // SQL実行
//         $stmt->execute();
        
//         // item_masterのupdated_atを更新
//         // SQL文を作成
//         $sql = 'UPDATE item_master
//                 SET updated_at = :updated_at
//                 WHERE item_id = :item_id';
//         // SQL文の実行準備
//         $stmt = $dbh->prepare($sql);
//         // SQL文のプレースホルダに値をバインド
//         $stmt->bindValue(':updated_at', $created_at, PDO::PARAM_STR);
//         $stmt->bindValue(':item_id', $value['item_id'], PDO::PARAM_STR);
//         // SQL実行
//         $stmt->execute();
//     }
 
// }

// /** カートテーブルの情報を購入履歴テーブルに追加
//   * @param obj $dbh DBハンドル
//   * @param array $data カートの商品情報配列
//   * @param str $user_id ユーザーID
//   * @param datetime $created_at 購入日時 
//   */
 
//   function insert_history($dbh, $data, $user_id, $created_at) {
    
//     foreach ($data as $key => $value) {
//         // SQL文作成
//         $sql = 'INSERT INTO
//                     purchase_history
//                     (user_id, item_id, quantity, created_at)
//                 VALUES
//                     (:user_id, :item_id, :quantity, :created_at)';
//         // SQL実行準備
//         $stmt = $dbh->prepare($sql);
//         // SQLのプレースホルダに値をバインド
//         $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
//         $stmt->bindValue(':item_id', $value['item_id'], PDO::PARAM_STR);
//         $stmt->bindValue(':quantity', $value['quantity'], PDO::PARAM_STR);
//         $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);
//         // SQL実行
//         $stmt->execute();
//     }
//   }

 
 

//  /** カートテーブルのレコードを削除
//   * @param obj $dbh DBハンドル
//   * @param str $user_id ユーザーID
//   */
 
//  function delete_cart($dbh, $user_id) {
//      // SQL文作成
//      $sql = 'DELETE FROM cart WHERE user_id = :user_id';
//      // SQL実行準備
//      $stmt = $dbh->prepare($sql);
//      // SQLプレースホルダに値をバインド
//      $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
//      // SQL実行
//      $stmt->execute();
//  }
 
 
//  /** 購入確定された時の処理
//   * @param obj $dbh DBハンドル
//   * @param str $user_id ユーザー名
//   * @param array $data カート内商品情報配列
//  */
 
//  function purchase($dbh, $user_id, $data) {
     
//     // エラーはグローバルの$err_msgに代入
//      global $err_msg;
    
//     // 変数初期化
//     $created_at = '';
    
//     if ((($_SERVER['REQUEST_METHOD']) === 'POST') && (isset($_POST['purchase']) === TRUE)) {
        
//         // 購入日時を取得
//         $created_at = date('Y-m-d H:i:s');
        
//         // 1)エラーチェック
//         if(purchase_err_chck($data) === TRUE) {
            
//             try {
                
//                 //トランザクション開始
//                 $dbh->beginTransaction();
                
//                 // 2)在庫数をマイナスする
//                     update_sold_stock($dbh, $data, $created_at);
                    
//                 // 3)購入履歴テーブルにレコードを追加する
//                     insert_history($dbh, $data, $user_id, $created_at);
                
//                 // 4)カートテーブルのレコードを削除する
//                     delete_cart($dbh, $user_id);
                    
//                 // コミット処理
//                     $dbh->commit();
                    
//                 // 5)購入完了ページへ遷移
//                 header('Location: ./shopping_complete.php');
//                 exit;
                
//             } catch (PDOException $e) {
//                 // ロールバック処理
//                 $dbh->rollback();
//                 $err_msg[] = 'エラーが発生しました。理由：'.$e->getMessage;
//             }
//         }
//     }
// }