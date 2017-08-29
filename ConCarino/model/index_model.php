<?php 

// ================
// 検索結果取得処理
// ================


/** エラーチェック
 *  @param str $target_m 男性向けフラグ
 *  @param str $target_f 女性向けフラグ
 *  @param str $min_budget 予算下限
 *  @param str $max_budget = 予算上限
 *  @param str $category1 = 飲料・食料フラグ
 *  @param str $category2 = スイーツ・お菓子フラグ
 *  @param str $category3 = ボディケア・コスメフラグ
 *  @param str $category4 = キッチン雑貨フラグ
 *  @param str $category5 = ファッション雑貨フラグ
 *  @param str $category6 = ステーショナリーフラグ
 *  @param bool $check_flg 一つでもエラーがあったらfalse
 */
 

 
function check_search_err($target_m, $target_f, $min_budget, $max_budget, $category1, $category2, $category3, $category4, $category5, $category6) {

    // エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    // チェックフラグ初期化
    $check_flg = TRUE;
    
    // ターゲットフラグ
    if (($target_m !== '1') && ($target_m !== '')) { 
        $err_msg[] = '「贈る相手」のチェックが不正です';
        $check_flg = FALSE;
    }
    if (($target_f !== '1') && ($target_f !== '')) { 
        $err_msg[] = '「贈る相手」のチェックが不正です';
        $check_flg = FALSE;
    }
    // 予算
    if ($min_budget === '') { 
        $err_msg[] = '「ご予算」の下限を選択してください';
        $check_flg = FALSE;
    } elseif (preg_match('/^[0-9]+$/', $min_budget) === 0) {
        $err_msg[] = '「ご予算」の下限の値が不正です';
        $check_flg = FALSE;
    }
    if ($max_budget === '') { 
        $err_msg[] = '「ご予算」の上限を選択してください';
        $check_flg = FALSE;
    } elseif (preg_match('/^[0-9]+$/', $max_budget) === 0) {
        $err_msg[] = '「ご予算」の上限の値が不正です';
        $check_flg = FALSE;
    }
    if ($min_budget > $max_budget){
        $err_msg[] = '「ご予算」の下限が上限を超えています';
        $check_flg = FALSE;
    }
    // カテゴリー
    if (($category1 !== '') && ($category1 !== '1')) {
        $err_msg[] = '「カテゴリ」のチェックが不正です';
        $check_flg = FALSE;
    }
    if (($category2 !== '') && ($category2 !== '2')) {
        $err_msg[] = '「カテゴリ」のチェックが不正です';
        $check_flg = FALSE;
    }
    if (($category3 !== '') && ($category3 !== '3')) {
        $err_msg[] = '「カテゴリ」のチェックが不正です';
        $check_flg = FALSE;
    }
    if (($category4 !== '') && ($category4 !== '4')) {
        $err_msg[] = '「カテゴリ」のチェックが不正です';
        $check_flg = FALSE;
    }
    if (($category5 !== '') && ($category5 !== '5')) {
        $err_msg[] = '「カテゴリ」のチェックが不正です';
        $check_flg = FALSE;
    }
    if (($category6 !== '') && ($category6 !== '6')) {
        $err_msg[] = '「カテゴリ」のチェックが不正です';
        $check_flg = FALSE;
    }
    
    return $check_flg;
}


/** 検索結果取得
 *  @param obj $dbh DBハンドル
 *  @param str $target_m 男性向けフラグ
 *  @param str $target_f 女性向けフラグ
 *  @param str $min_budget 予算下限
 *  @param str $max_budget 予算上限
 *  @param str $category1 飲料・食料フラグ
 *  @param str $category2 スイーツ・お菓子フラグ
 *  @param str $category3 ボディケア・コスメフラグ
 *  @param str $category4 キッチン雑貨フラグ
 *  @param str $category5 ファッション雑貨フラグ
 *  @param str $category6 ステーショナリーフラグ
 *  @param array 検索結果一覧
 */
function get_search_data($dbh, $target_m, $target_f, $min_budget, $max_budget, $category1, $category2, $category3, $category4, $category5, $category6, $sort_flg) {

    
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
                category_id,
                TRUNCATE(avg(rate),1) as avg_rate
            FROM 
                item_master 
            INNER JOIN
                item_stock
            ON
                item_master.item_id = item_stock.item_id
            LEFT JOIN
                item_review
            ON 
                item_master.item_id = item_review.item_id
            WHERE status = 0';
    
    // 追加の検索条件
    // ターゲットフラグ
    if ($target_m !== '') {
        $sql .= ' AND target_m = 1';
    }
    if ($target_f !== '') {
        $sql .= ' AND target_f = 1';
    }
    // 予算
    if (($min_budget !== '') && ($max_budget !== '')) {
        $sql .= ' AND price BETWEEN :min_budget AND :max_budget';
    }
    
    // 選択されたカテゴリIDを配列に代入
    $category = [];
    
    if ($category1 !== '') {
        $category[] = 1;
    }
    
    if ($category2 !== '') {
        $category[] = 2;
    }
    
    if ($category3 !== '') {
        $category[] = 3;
    }
    
    if ($category4 !== '') {
        $category[] = 4;
    }
    
    if ($category5 !== '') {
        $category[] = 5;
    }
    
    if ($category6 !== '') {
        $category[] = 6;
    }
    

    if (!empty($category)) {
        $sql .= ' AND category_id IN (' . implode(', ', $category) . ')';
    }
    
    $sql .= ' GROUP BY item_master.item_id';
    
             if (($sort_flg === '0') || ($sort_flg === '')) {
                 $sql .= ' ORDER BY item_master.created_at DESC';
             } elseif ($sort_flg === '1') {
                 $sql .= ' ORDER BY item_master.price';
             } elseif ($sort_flg === '2') {
                 $sql .= ' ORDER BY item_master.price DESC';
             } elseif ($sort_flg === '3') {
                 $sql .= ' ORDER BY avg_rate DESC';
             }
    // var_dump($sql);
    // SQL実行準備
    $stmt = $dbh->prepare($sql);
    
    // 条件に応じて値をプレースホルダにバインド
    // 予算指定がある場合
    if (($min_budget !== '') && ($max_budget !== '')) {
        $stmt->bindValue(':min_budget', $min_budget, PDO::PARAM_STR);
        $stmt->bindValue(':max_budget', $max_budget, PDO::PARAM_STR);
    }
    
    // SQL実行
   $stmt->execute();
   
   $rows = $stmt->fetchAll();
   
//   var_dump($rows);
   return $rows;
}

 
// ================
// 表示順並べ替え処理
// ================
 

/** エラーチェック
 *  @param str $sort_flg 並び替えフラグ
 *  @param bool $check_flg 一つでもエラーがあったらfalse
 */
 
function check_sort_err($sort_flg) {
    
    // エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    // チェックフラグの初期化
    $check_flg = TRUE;
    
    if (($sort_flg !=='') && (preg_match('/^[0123]$/', $sort_flg) === 0)) {
        $err_msg[] = '選択された表示順の値が不正です';
        $check_flg = FALSE;
    }
    
    return $check_flg;
}

/** 並べ替え処理
 *  @param str $sort_flg 並び替えフラグ
 */

 
 


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
                category_id,
                TRUNCATE(avg(rate),1) as avg_rate
            FROM 
                item_master 
            INNER JOIN
                item_stock
            ON
                item_master.item_id = item_stock.item_id
            LEFT JOIN
                item_review
            ON 
                item_master.item_id = item_review.item_id
            WHERE status = 0
            GROUP BY item_review.item_id
            ORDER BY item_master.created_at DESC';
    
    // SQL実行準備
    $stmt = $dbh->prepare($sql);
    
    // SQL実行
   $stmt->execute();
   
   $data = $stmt->fetchAll();
   
   return $data;
}


 /** 星の数表示用のクラス名を設定
  * @param array $data 商品情報
  * @return array $data　商品情報
  */
 
 function get_index_star_class($data) {


  foreach ($data as &$value) {
     if ((0 < $value['avg_rate']) && ($value['avg_rate'] < 10) ) {$value['star_rate'] = 05;}
         elseif ((10 <= $value['avg_rate']) && ($value['avg_rate'] < 15 )) {$value['star_rate'] = 10;}
         elseif ((15 <= $value['avg_rate']) && ($value['avg_rate'] < 20 )) {$value['star_rate'] = 15;}
         elseif ((20 <= $value['avg_rate']) && ($value['avg_rate'] < 25 )) {$value['star_rate'] = 20;}
         elseif ((25 <= $value['avg_rate']) && ($value['avg_rate'] < 30 )) {$value['star_rate'] = 25;}
         elseif ((30 <= $value['avg_rate']) && ($value['avg_rate'] < 35 )) {$value['star_rate'] = 30;}
         elseif ((35 <= $value['avg_rate']) && ($value['avg_rate'] < 40 )) {$value['star_rate'] = 35;}
         elseif ((40 <= $value['avg_rate']) && ($value['avg_rate'] < 45 )) {$value['star_rate'] = 40;}
         elseif ((45 <= $value['avg_rate']) && ($value['avg_rate'] < 50 )) {$value['star_rate'] = 45;}
         elseif ($value['avg_rate'] === 50 ) {$value['star_rate'] = 50;}
      }
     return $data;
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
 