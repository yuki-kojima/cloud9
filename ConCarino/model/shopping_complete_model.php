<?php

/** 購入商品の合計金額を取得する
 * @param array $data 購入確定した商品一覧データ
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


// ================
// 購入確定処理
// ================


 /** 在庫切れ・取り扱い終了チェック
  * @param obj $dbh DBハンドル
  * @param array $data カート内商品一覧
　* @return bool $chck_flg エラーが一つでもあればFALSE
　*/

function purchase_err_chck($data) {
    
    // エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    // エラーチェック用フラグ
    $chck_flg = TRUE;

    foreach ($data as $key => $value) {

        // 商品が非公開になっていないかチェック
        if ($value['status'] === '1') {
            $err_msg[] = $value['item_name'].'の取り扱いが終了しました';
            $chck_flg = FALSE;
        }
        // 在庫切れになっていないかチェック
        if ($value['stock'] === '0') {
            $err_msg[] = $value['item_name'].'が在庫切れになりました';
            $chck_flg = FALSE;
        } elseif ($value['stock'] < $value['quantity']){
            $err_msg[] = $value['item_name'].'の在庫が不足しています。残りあと'.$value['stock'].'個です。';
            $chck_flg = FALSE;
        }
    }
    return $chck_flg;
}

 
 /** 購入された商品の在庫をitem_stockから購入点数からマイナスし
 *  item_masterの更新日時を更新
 * @param obj $dbh DBハンドル
 * @param array $data 購入された商品一覧(カートテーブルの情報)
 * @param date $created_at 購入日時
 */

function update_sold_stock($dbh, $data, $created_at){

    // item_stockを更新
    foreach ($data as $key => $value) {
        
        // SQL文作成
        $sql = 'UPDATE item_stock
                SET
                    stock = stock - :quantity,
                    updated_at = :updated_at
                WHERE item_id = :item_id';
        // SQL文の実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(':quantity', $value['quantity'], PDO::PARAM_STR);
        $stmt->bindValue(':updated_at', $created_at, PDO::PARAM_STR);
        $stmt->bindValue(':item_id', $value['item_id'], PDO::PARAM_STR);
        // SQL実行
        $stmt->execute();
        
        // item_masterのupdated_atを更新
        // SQL文を作成
        $sql = 'UPDATE item_master
                SET updated_at = :updated_at
                WHERE item_id = :item_id';
        // SQL文の実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(':updated_at', $created_at, PDO::PARAM_STR);
        $stmt->bindValue(':item_id', $value['item_id'], PDO::PARAM_STR);
        // SQL実行
        $stmt->execute();
    }
 
}

/** カートテーブルの情報を購入履歴テーブルに追加
  * @param obj $dbh DBハンドル
  * @param array $data カートの商品情報配列
  * @param str $user_id ユーザーID
  * @param datetime $created_at 購入日時 
  */
 
  function insert_history($dbh, $data, $user_id, $created_at) {
    
    foreach ($data as $key => $value) {
        // SQL文作成
        $sql = 'INSERT INTO
                    purchase_history
                    (user_id, item_id, quantity, created_at)
                VALUES
                    (:user_id, :item_id, :quantity, :created_at)';
        // SQL実行準備
        $stmt = $dbh->prepare($sql);
        // SQLのプレースホルダに値をバインド
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        $stmt->bindValue(':item_id', $value['item_id'], PDO::PARAM_STR);
        $stmt->bindValue(':quantity', $value['quantity'], PDO::PARAM_STR);
        $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);
        // SQL実行
        $stmt->execute();
    }
  }

 
 

 /** カートテーブルのレコードを削除
  * @param obj $dbh DBハンドル
  * @param str $user_id ユーザーID
  */
 
 function delete_cart($dbh, $user_id) {
     // SQL文作成
     $sql = 'DELETE FROM cart WHERE user_id = :user_id';
     // SQL実行準備
     $stmt = $dbh->prepare($sql);
     // SQLプレースホルダに値をバインド
     $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
     // SQL実行
     $stmt->execute();
 }
 
 
 /** 購入確定された時の処理
  * @param obj $dbh DBハンドル
  * @param str $user_id ユーザー名
  * @param array $data カート内商品情報配列
  * @return bool $check_flg エラーが発生したらfalse
 */
 
 function purchase($dbh, $user_id, $data) {
     
    // エラーはグローバルの$err_msgに代入
     global $err_msg;
     
    // チェックフラグ初期化
    $check_flg = TRUE;
    
    // 変数初期化
    $created_at = '';
    
    if ((($_SERVER['REQUEST_METHOD']) === 'POST') && (isset($_POST['purchase']) === TRUE)) {
        
        // 購入日時を取得
        $created_at = date('Y-m-d H:i:s');
        
        // 1)エラーチェック
        if(purchase_err_chck($data) === TRUE) {
            
            try {
                
                //トランザクション開始
                $dbh->beginTransaction();
                
                // 2)在庫数をマイナスする
                    update_sold_stock($dbh, $data, $created_at);
                    
                // 3)購入履歴テーブルにレコードを追加する
                    insert_history($dbh, $data, $user_id, $created_at);
                
                // 4)カートテーブルのレコードを削除する
                    delete_cart($dbh, $user_id);
                    
                // コミット処理
                    $dbh->commit();
                    
            } catch (PDOException $e) {
                // ロールバック処理
                $dbh->rollback();
                $err_msg[] = 'エラーが発生しました。理由：'.$e->getMessage;
                $check_flg = FALSE;
     
            }
        } else {
             $check_flg = FALSE;
        }
    }else{
        $check_flg = FALSE;
    }
    return $check_flg;
}