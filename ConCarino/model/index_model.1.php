<?php 

// ================
// 検索結果取得処理
// ================

// ================
// 販売可能商品情報取得
// ================

/** 販売可能商品一覧を取得する
 * @param obj $dbh DBハンドル
 * @return array 商品一覧データ
 */
 
function get_onsale_item_data($dbh) {
    
    // SQL文作成
    $sql = 'select
                item_master.item_id,
                item_name,
                price,
                img,
                status,
                stock,
                target_f,
                target_m,
                category_id
            FROM item_master INNER JOIN item_stock
                ON item_master.item_id = item_stock.item_id
            WHERE status = 0
            ORDER BY item_master.created_at DESC';
    
    // クエリ実行
    return get_as_array($dbh, $sql);
}

// ================
// カート追加処理
// ================


/** カートに追加された商品の価格・商品名・在庫・画像・公開ステータスをDBから取得
 * @param obj $dbh DBハンドル
 * @param str $item_id 選択された商品ID
 * @return array $row 選択された商品の情報が入った配列
 */
 
function get_added_item_data($dbh, $item_id){
    
    //
    // SQL文作成
    $sql = 'SELECT
                item_name,
                price,
                status,
                img,
                stock
            FROM item_master INNER JOIN item_stock
                ON item_master.item_id = item_stock.item_id
            WHERE
                item_master.item_id = :item_id';
    
    // SQL文実行準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
    // SQL文実行
    $stmt->execute();
    // レコードの取得
    $row = $stmt->fetch();
    
    return $row;
}

/** 在庫切れ・取り扱い終了チェック
 * @param str $status 商品の公開ステータス
 * @param str $stock 商品の在庫
 * @return bool $chck_flg エラーが一つでもあればFALSE
 */
 
function cart_err_chck($price, $status, $stock) {
    
    // エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    // エラーチェック用フラグ
    $chck_flg = TRUE;
    
    // 商品が非公開になっていないかチェック
    if ($status === 1) {
        $err_msg[] = '商品の取り扱いが終了しました';
        $chck_flg = FALSE;
    }
    // 在庫切れになっていないかチェック
    if ($stock === 0) {
        $err_msg[] = '在庫切れになりました';
        $chck_flg = FALSE;
    }
    return $chck_flg;
    
    
} 


/** カートテーブルから今回カートに入れた商品の数を取得
 * @param obj $dbh DBハンドル
 * @param str $item_id カートに追加された商品ID
 * @return int $item_num カートに既に入っている数
 */

function get_item_num_in_cart($dbh, $user_id, $item_id) {

    // チェックフラグの初期化
    $check_flg = TRUE;
    
    // 商品カート内に既にある商品か調べる
    // SQL文作成
    $sql = 'SELECT quantity FROM cart
            WHERE item_id = :item_id AND user_id = :user_id';
    // SQL実行準備
    $stmt = $dbh->prepare($sql);
    // SQLのプレースホルダーに値をバインド
    $stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    // SQL実行
    $stmt->execute();
    // レコードを取得
    $row = $stmt->fetch();
    
    $item_num = $row['quantity'];
    
    return $item_num;
    
}

/** カートテーブルの商品の点数をプラス１する
 * @param obj $dbh DBハンドル
 * @param str $item_id カートに追加された商品ID
 */

function update_item_num($dbh, $user_id, $item_id) {

    // 登録日時取得
    $updated_at = date('Y-m-d H:i:s');
    
    // SQL文作成
    $sql = 'UPDATE cart
            SET 
                quantity = quantity + 1,
                updated_at = :updated_at
            WHERE
                item_id = :item_id
            AND
                user_id = :user_id';
                
    // SQL文実行準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(':updated_at', $updated_at, PDO::PARAM_STR);
    $stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    // SQL文の実行
    $stmt->execute();
    
}


/** カートテーブルにカートに追加された商品情報を追加
 * @param obj $dbh DBハンドル
 * @param str $item_id カートに追加された商品ID
 */

function insert_cart($dbh, $item_id, $user_id) {

    // 登録日時取得
    $created_at = date('Y-m-d H:i:s');
    
    // SQL文作成
    $sql = 'INSERT INTO cart (
                item_id,
                user_id,
                created_at
                )
            VALUES
                (:item_id, :user_id, :created_at)';
    // SQL文実行準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);
    // SQL文の実行
    $stmt->execute();
}

/** カートに商品を追加した時の処理
 * @param obj $dbh DBハンドル
 */
 
 function add_cart($dbh, $user_id) {
    
    // 変数初期化
    $item_id = '';
    $data = [];
    $status = '';
    $stock = '';
    $item_num = '';
    
    if ((($_SERVER['REQUEST_METHOD']) === 'POST') && (isset($_POST['add_to_cart']) === TRUE)) {
        
        // 1)追加された商品の情報を取得
        $item_id = get_post_data('item_id');
        $data = get_added_item_data($dbh, $item_id);
        $status = $data['status'];
        $stock = $data['stock'];
        $item_num = get_item_num_in_cart($dbh, $user_id, $item_id);
        
        // 1)エラーチェック
        if (cart_err_chck($item_id, $status, $stock) === TRUE) {
            
            // 2)カートテーブルにない商品はレコードを追加する
            if ($item_num === null) {
            insert_cart($dbh, $item_id, $user_id);
            
            // 3)カートテーブル既にある商品は点数を更新
            } else {
            update_item_num($dbh, $user_id, $item_id);
            }
            
            // 4)カートページに遷移
            header('Location: ./cart.php');
            exit;
        }
    }
     
 }
 
// ================
// 商品の評価情報取得
// ================

/** item_reviewテーブルから商品の星の平均数を取得
 * @param obj $dbj DBハンドル
 * @param str $item_id 商品のID
 */
 
