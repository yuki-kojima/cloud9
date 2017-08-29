<?php 

// ================
// 新商品登録処理
// ================

/**
 * POSTされた新商品情報のエラーチェック
 * @param str $item_name 商品名
 * @param str $description 商品説明
 * @param str $price 価格
 * @param str $status 公開ステータス
 * @param str $stock 在庫数
 * @param str $target_f 女性向けフラグ
 * @param str $target_m 男性向けフラグ
 * @param str $category カテゴリ
 * @return bool $chck_flg エラーがあったらfalse,なければtrue
 */

function err_chck($item_name, $description, $price, $status, $stock, $target_f, $target_m, $category) {
    
    // エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    // エラーフラグの初期化。エラーがあればfalseへ
    $chck_flg = true;
    
    // 文字数入力チェック用
    $item_name_len = mb_strlen($item_name, 'UTF-8');
    $description_len = mb_strlen($description, 'UTF-8');
    
    //エラーコードの設定
    
    // 商品名
    if ($item_name === '') {
        $err_msg[] = '商品名を入力してください';
        $chck_flg = false;
    } elseif ($item_name_len > 30) {
        $err_msg[] = '商品名は30文字以内で入力してください';
        $chck_flg = false;
    }
    // 商品説明
    if ($description === '') {
        $err_msg[] = '商品説明を入力してください';
        $chck_flg = false;
    } elseif ($description_len > 300) {
        $err_msg[] = '商品名は300文字以内で入力してください';
        $chck_flg = false;
    }
    //価格
    if ($price === '') {
        $err_msg[] = '単価を入力してください';
        $chck_flg = false;
    } elseif (preg_match('/^[0-9]+$/', $price) === 0) {
        $err_msg[] = '単価は0以上の整数で入力してください';
        $chck_flg = false;
    }
    // 在庫
    if ($stock === '') {
        $err_msg[] = '在庫数を入力してください';
        $chck_flg = false;
    }else if (preg_match('/^[0-9]+$/', $stock) === 0) {
        $err_msg[] = '在庫数は0以上の整数で入力してください';
        $chck_flg = false;
    } 
    // 公開ステータス
    if (preg_match('/^[01]$/', $status) === 0) {
        $err_msg[] = '公開ステータスを選択してください';
        $chck_flg = false;
    }
    // 女性向け・男性向けフラグ
    if (($target_f === 0) && ($target_m === 0)) {
        $err_msg[] = 'ターゲットを一つ以上選択してください';
        $chck_flg = false;
    } elseif (preg_match('/^[01]$/', $target_f) === 0) {
        $err_msg[] = '女性向けフラグの値が不正です';
        $chck_flg = false;
    } elseif (preg_match('/^[01]$/', $target_m) === 0) {
        $err_msg[] = '男性向けフラグの値が不正です';
        $chck_flg = false;
    }
    // カテゴリ
    if ($category === '') {
        $err_msg[] = 'カテゴリを選択してください';
        $chck_flg = false;
    } else if (preg_match('/^[1-6]$/', $category) === 0) {
        $err_msg[] = 'カテゴリを選択してください';
        $chck_flg = false;
    }
    
    return $chck_flg;
    
}

/** 商品画像のエラーチェック
 * @param str $extension 画像の拡張子
 * @return bool $chck_flg ファイルが選択されていなければfalse
 */

function img_up_chck($extension) {
    
    //エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    // エラーフラグの初期化。エラーがあればfalse
    $chck_flg = true;
    
    // HTTP POST でファイルがアップされたかチェック
    if (is_uploaded_file($_FILES['img']['tmp_name']) === FALSE) {
        $err_msg[] = 'ファイルを選択してください';
        $chck_flg = false;
    }elseif ($extension !== 'jpg' && $extension !== 'jpeg' && $extension !== 'png') {
        $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEGもしくはPNGのみ利用可能です。';
        $chck_flg = false;
    }
    
    return $chck_flg;
}


/** 画像の拡張子取得
 * @return str $extension 画像の拡張子
 */
 
function get_img_extension(){
    
    if(isset($_FILES['img']['name']) === TRUE) {
        // 画像の拡張子を取得
        $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
    }

    return $extension;
}


/** 画像のファイル名作成
 * @param str $extension 画像の拡張子
 * @return str $new_img_filename 画像のファイル名
 */

function name_img($extension) {
    // 保存する新しい画像ファイル名の生成
    $new_img_filename = sha1(uniqid(mt_rand(), true)).'.'.$extension;
    return $new_img_filename;
}

/**　画像の保存処理
 * @param str $img_dir 画像の保存先ディレクトリ
 * @param str $new_img_filename 画像のファイル名
 * @return bool $chck_flg アップロードに失敗したらfalse
 */
 
function save_img($new_img_filename) {
    
    //エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    // エラーフラグの初期化。エラーがあればfalse
    $chck_flg = true;
    
    // 画像の保存先ディレクトリ
    global $img_dir;
    
    // 同名のファイルが存在するかチェック
    if(is_file($img_dir.$new_img_filename) !== TRUE) {
        // アップロードされたファイルを指定ディレクトリに移動して保存
        if (move_uploaded_file($_FILES['img']['tmp_name'], $img_dir.$new_img_filename) !== TRUE) {
            $err_msg[] = 'ファイルアップロードに失敗しました';
            $chck_flg = false;
        }
    } else {
        $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
    }
    
    return $chck_flg;
}

/** エラーチェックまとめ
 * @param str $item_name 商品名
 * @param str $description 商品説明
 * @param str $price 価格
 * @param str $status 公開ステータス
 * @param str $stock 在庫数
 * @param str $target_f 女性向けフラグ
 * @param str $target_m 男性向けフラグ
 * @param str $category カテゴリ
 * @param str $extension　商品画像の拡張子
 * @return bool $chck_flg エラーが一つでもあればfalse
 */

function total_err_chck($item_name, $description, $price, $status, $stock, $target_f, $target_m, $category, $extension) {
    
    // エラーチェック用フラグ
    $chck_flg = TRUE;
    
    // 商品名、商品説明、価格、在庫数、ターゲットフラグ、カテゴリ、ステータスのチェック
    if (err_chck($item_name, $description, $price, $status, $stock, $target_f, $target_m, $category) === FALSE) {
        $chck_flg = FALSE;
    }
    if (img_up_chck($extension) === FALSE) {
        $chck_flg = FALSE;
    }

    return $chck_flg;
}

/** DBのitem_masterへ新商品情報を登録し、
 *  DBのitem_stockへ在庫数を登録する
 * @param obj $dbh DBハンドル
 * @param str $item_name　商品名
 * @param str $description 商品説明
 * @param str $price 価格
 * @param str $status 公開ステータス
 * @param str $img 画像ファイル名
 * @param str $target_f 女性向けフラグ
 * @param str $target_m 男性向けフラグ
 * @param str $category カテゴリ
 * @param str $stock 在庫数
 */
 
 function insert_item_data($dbh, $item_name, $description, $price, $new_img_filename, $status, $stock, $target_f, $target_m, $category) {
     
    // 完了メッセージはグローバルの$cmp_msgに代入する
    global $cmp_msg;
    
    // 作成時間を取得
    $created_at = date('Y-m-d H:i:s');
    
    // トランザクション開始
    $dbh->beginTransaction();
    try {
    
        // item_masterへ在庫以外の情報を登録
        // SQL文を作成
        $sql = 'insert into item_master (
                    item_name,
                    description,
                    price,
                    img,
                    status,
                    target_f,
                    target_m,
                    category_id,
                    created_at
                    )
                values (:item_name, :description, :price, :img, :status, :target_f, :target_m, :category, :created_at)';
        
        // SQL実行準備
        $stmt = $dbh->prepare($sql);
        
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(':item_name', $item_name, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':price', $price, PDO::PARAM_STR);
        $stmt->bindValue(':img', $new_img_filename, PDO::PARAM_STR);
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':target_f', $target_f, PDO::PARAM_STR);
        $stmt->bindValue(':target_m', $target_m, PDO::PARAM_STR);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);
        
        // SQLを実行
        $stmt->execute();
        
        // 今登録した商品のitem_idを取得
        $item_id = $dbh->lastInsertId();
        
        // item_stockに在庫数を登録
        // SQL文作成
        $sql = 'insert into item_stock (
                    item_id,
                    stock,
                    created_at
                )
                values(:item_id, :stock, :create_at)';
        // SQL文実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
        $stmt->bindValue(':stock', $stock, PDO::PARAM_STR);
        $stmt->bindValue(':create_at', $created_at, PDO::PARAM_STR);
        // SQL実行
        $stmt->execute();
        
        // 完了メッセージの代入
        $cmp_msg = '新商品の登録が完了しました';
        
        //コミット処理
        $dbh->commit();
    } catch (PDOException $e) {
        // ロールバック処理
        $dbh->rollback();
        throw $e;
    }
 }
 
 

/** 新商品登録がされた時に以下の処理を行う
 * 1)送信された内容の取得
 * 2)エラーチェック
 * 3)データベースへ投稿情報を格納
 * @param obj $dbh DBハンドル
 */
 
function reg_item_data($dbh) {

    
    // 変数初期化
    $item_name = '';
    $description = '';
    $price = '';
    $stock = '';
    $status = '';
    $img = '';
    $target_f = '';
    $target_m = '';
    $category = '';
    
    // 新商品登録のPOST送信がされた時の処理
    if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['register']) === TRUE)) {
        
        // 1)送信された情報の取得
        $item_name = get_post_data('item_name');
        $description = get_post_data('description');
        $price = get_post_data('price');
        $stock = get_post_data('stock');
        $status = get_post_data('status');
        $img = get_post_data('img');
        $category = get_post_data('category');
        
        // ここってもっといい方法ありますか(><)
        // ターゲットフラグはチェックされてない場合0にする
        if (isset($_POST['target_f']) === TRUE) {
            $target_f = get_post_data('target_f');
        } else {
            $target_f = 0;
        }
        if (isset($_POST['target_m']) === TRUE) {
            $target_m = get_post_data('target_m');
        } else {
            $target_m = 0;
        }
        
        
        // 2) エラーチェック
        $extension = get_img_extension();
        $new_img_filename = name_img($extension);
        if (total_err_chck($item_name, $description, $price, $status, $stock, $target_f, $target_m, $category, $extension) === TRUE) {
        
        // 3) 画像を保存
        // 画像の保存先
            if(save_img($new_img_filename) === TRUE) {
                
        // 4) DBに情報登録
            insert_item_data($dbh, $item_name, $description, $price, $new_img_filename, $status, $stock, $target_f, $target_m, $category);
            }
        }
        
    }
}


// ================
// 商品情報取得
// ================

/** 登録商品一覧を取得する
 * @param obj $dbh DBハンドル
 * @return array 商品一覧データ
 */
 
function get_item_data($dbh) {
    
    // SQL文作成
    $sql = 'select
                item_master.item_id,
                description,
                item_name,
                price,
                img,
                stock,
                status,
                target_f,
                target_m,
                category
            FROM
                item_master 
            INNER JOIN 
                item_stock
            ON
                item_master.item_id = item_stock.item_id
            INNER JOIN
                category_master
            ON
                item_master.category_id = category_master.category_id
            ORDER BY item_master.created_at DESC';
    
    // クエリ実行
    return get_as_array($dbh, $sql);
}


// ================
// 在庫更新処理
// ================

/** 更新された在庫数のエラーチェック
 * @param int $update_stock 在庫数
 * @return bool $chck_flg エラーが一つでもあればFALSE
 */
 
function stock_err_chck($update_stock) {
    
    // エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    // エラーチェック用フラグ
    $chck_flg = TRUE;
    
    if ($update_stock === '') {
        $err_msg[] = '在庫数を入力してください';
        $chck_flg = FALSE;
    }elseif (preg_match('/^[0-9]+$/', $update_stock) === 0) {
        $err_msg[] = '在庫数は0以上の正の整数で入力してください';
        $chck_flg = FALSE;
    }

    return $chck_flg;
}

/** 更新された在庫数をDBのitem_stockに反映し
 * item_masterのupdated_atも更新する
 *  @param obj $dbh DBハンドル
 *  @param str $update_stock 在庫数
 *  @param str $update_id 更新された商品ID
 */
 
function insert_update_stock_data($dbh, $update_stock, $update_id) {
    
    // 完了メッセージはグローバルの$cmp_msgに代入
    global $cmp_msg;
    
    // updated_at取得
    $updated_at = date('Y-m-d H:i:s');
    
    //トランザクション開始
    $dbh->beginTransaction();
    try {
        // item_stockの更新
        // SQL文の作成
        $sql = 'UPDATE
                    item_stock
                SET
                    stock = ?,
                    updated_at = ?
                WHERE item_id = ?';
                
        // SQL文実行の準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $update_stock, PDO::PARAM_STR);
        $stmt->bindValue(2, $updated_at, PDO::PARAM_STR);
        $stmt->bindValue(3, $update_id, PDO::PARAM_STR);
        //SQL文を実行
        $stmt->execute();
        
        // item_masterのupdated_atの更新
        // SQL文作成
        $sql = 'update
                    item_master
                set
                    updated_at = ?
                where
                    item_id = ?';
        // SQL文実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $updated_at, PDO::PARAM_STR);
        $stmt->bindValue(2, $update_id, PDO::PARAM_STR);

        // SQL文を実行
        $stmt->execute();
        
        // 完了メッセージを代入
        $cmp_msg = '在庫数を更新しました';
        
        // コミット処理
        $dbh->commit();
    } catch (PDOExeption $e) {
        // ロールバック処理
        $dbh->rollback();
        throw $e;
    }
}



/** 在庫数更新処理が行われた時に以下の処理をする
 * @param obj $dbh DBハンドル  
 */
 
 function update_stock($dbh) {
     
     if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['update']) === TRUE)) {
         
        // 変数初期化
        $update_stock = '';
        $update_id = '';
         
        // 1)送信された情報取得
        $update_stock = get_post_data('update_stock');
        $update_id = get_post_data('update_id');
        
        // 2)エラーチェック
        if (stock_err_chck($update_stock) === TRUE) {
        
        // 3)item_stockの在庫数を更新
            insert_update_stock_data($dbh, $update_stock, $update_id);
        }

         
     }
 }
 
// ================
// 公開ステータス変更処理
// ================
 
 /** 公開ステータスの情報をDBのitem_masterに反映
  * @param obj $dbh DBハンドル
  * @param str $change_status 更新後の公開ステータス
  * @param str $change_id 更新されたドリンクのID
  */
  
function insert_change_status($dbh, $change_status, $change_id) {
    // 完了メッセージはグローバルの$cmp_msgに代入
    global $cmp_msg;
    
    // updated_at取得
    $updated_at = date('Y-m-d H:i:s');

        // item_masterの更新
        // SQL文作成
        $sql = 'update
                    item_master
                set
                    status = ?,
                    updated_at = ?
                where
                    item_id = ?';
        // SQL文実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $change_status, PDO::PARAM_STR);
        $stmt->bindValue(2, $updated_at, PDO::PARAM_STR);
        $stmt->bindValue(3, $change_id, PDO::PARAM_STR);

        // SQL文を実行
        $stmt->execute();
        
        // 完了メッセージを代入
        $cmp_msg = '公開ステータスを変更しました';

}
 
 /** 公開ステータスの変更処理
  * @param obj $dbh DBハンドル
  */
  
function change_status($dbh) {
    if ((($_SERVER['REQUEST_METHOD']) === 'POST') && (isset($_POST['change']) === TRUE)) {
        
        // 変数初期化
        $change_status = '';
        $change_id = '';
        
        // 1)送信された値の取得
        $change_status = get_post_data('change_status');
        $change_id = get_post_data('change_id');
        
        // 2)DBに情報登録
        insert_change_status($dbh, $change_status, $change_id);
        
    }
}

// ================
// 削除処理
// ================
 
 /** POSTで送られてきたitem_idの情報を
  * item_master、item_stockから削除する
  * @param obj $dbh DBハンドル
  * @param str $delete_id 削除対象item_id
  */
  
function delete_item_info($dbh, $delete_id) {
    
    // 完了メッセージの設定
    global $cmp_msg;
      
    // トランザクション開始
    $dbh->beginTransaction();
    try {
    
        // item_masertから削除
        // SQL文を作成
        $sql = 'delete from item_master where item_id = :delete_id';
        // SQL文の実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(':delete_id', $delete_id, PDO::PARAM_STR);
        // SQL実行
        $stmt->execute();
        
        // item_stockから削除
        // SQL文作成
        $sql = 'delete from item_stock where item_id = :delete_id';
        // SQL文実行の準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(':delete_id', $delete_id, PDO::PARAM_STR);
        // SQL実行
        $stmt->execute();
        
        // 完了メッセージ
        $cmp_msg = '商品を削除しました';
        
        // コミット処理
        $dbh->commit();
    } catch (PDOExeption $e) {
        $dbh->rollback();
        throw $e;
    }
}

 /** 削除処理
  * @param obj $dbh DBハンドル
  */
  
  function delete_item($dbh) {
      if((($_SERVER['REQUEST_METHOD']) === 'POST') && (isset($_POST['delete']) === TRUE)) {
          
          // 変数初期化
          $delete_id = '';
          
          // 1)送信された値の取得
          $delete_id = get_post_data('delete_id');

          // 2)該当商品をDBから削除
          delete_item_info($dbh, $delete_id);
      }
  }
  
  