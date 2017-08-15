<?php

/**
 * 特殊文字をHTMLエンティティに変換する
 * @param str $str 変換前文字
 * @return str　変換後文字
 */

function entity_str($str) {
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
    
}

/**
 * 特殊文字をHTMLエンティティに変換する(2次元配列の値)
 * @param array $assoc_array　変換前配列
 * @return array 変換後配列
 */
 
function entity_assoc_array($assoc_array) {
    foreach($assoc_array as $key => $value) {
        foreach($value as $keys => $values) {
            // 特殊文字をHTMLエンティティに変換
            $assoc_array[$key][$keys] = entity_str($values);
        }
    }
    
    return $assoc_array;
}

/**
 * DBハンドルを取得
 * @return obj $dbh DBハンドル
 */

function get_db_connect(){
    
        // データベースに接続
        $dbh = new PDO(DSN, DB_USER, DB_PASSWD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
var_dump($dbh);
        return $dbh;
}

/**POSTで送信された新商品の情報を取得
 * @param str $key POSTで送信されたname属性の値
 * @return str $str POSTで送信された情報のvalueの値
 */
 
function get_post_data($key) {
    $str = '';
    if (isset($_POST[$key]) === TRUE) {
        $str = $_POST[$key];
    }
    
    return $str;
}


/**
 * POSTされた新商品情報のエラーチェック
 * @param str $drink_name 商品名
 * @param str $price 価格
 * @param str $status 公開ステータス
 * @param str $stock 在庫数
 * @return bool $chck_flg エラーがあったらfalse,なければtrue
 */

function err_chck($drink_name, $price, $status, $stock) {
    
    // エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    //エラーフラグの初期化。エラーがあればfalseへ
    $chck_flg = true;
    
    //エラーコードの設定
    if ($drink_name === '') {
        $err_msg[] = '商品名を入力してください';
        $chck_flg = false;
    }
    if ($price === '') {
        $err_msg[] = '値段を入力してください';
        $chck_flg = false;
    } else if (preg_match('/^[0-9]+$/', $price) === 0) {
        $err_msg[] = '値段は0以上の整数で入力してください';
        $chck_flg = false;
    }
    
    if ($stock === '') {
        $err_msg[] = '個数を入力してください';
        $chck_flg = false;
    }else if (preg_match('/^[0-9]+$/', $stock) === 0) {
        $err_msg[] = '値段は0以上の整数で入力してください';
        $chck_flg = false;
    } 
    
    if (preg_match('/^[01]$/', $status) === 0) {
        $err_msg[] = '公開ステータスを選択してください';
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
 
function save_img($new_img_filename, $img_dir) {
    
    //エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    // エラーフラグの初期化。エラーがあればfalse
    $chck_flg = true;
    
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
 * @param str $drink_name 商品名
 * @param str $price 価格
 * @param str $status 公開ステータス
 * @param str $stock 在庫数
 * @param str $extension　商品画像の拡張子
 * @return bool $chck_flg エラーが一つでもあればfalse
 */

function total_err_chck($drink_name, $price, $status, $stock, $extension) {
    
    // エラーチェック用フラグ
    $chck_flg = TRUE;
    
    // 商品名、価格、在庫数、ステータスのチェック
    if (err_chck($drink_name, $price, $status, $stock) === FALSE) {
        $chck_flg = FALSE;
    }
    if (img_up_chck($extension) === FALSE) {
        $chck_flg = FALSE;
    }

    return $chck_flg;
}

/** DBのdrink_masterへ新商品情報を登録し、
 *  DBのdrink_stockへ在庫数を登録する
 * @param obj $dbh DBハンドル
 * @param str $drink_name　商品名
 * @param str $price 価格
 * @param str $status 公開ステータス
 * @param str $img 画像ファイル名
 * @param str $stock 在庫数
 */
 
 function insert_drink_data($dbh, $drink_name, $price, $status, $new_img_filename, $stock) {
     
    // 完了メッセージはグローバルの$cmp_msgに代入する
    global $cmp_msg;
    
    // 作成時間を取得
    $datetime = date('Y-m-d H:i:s');
    
    // トランザクション開始
    $dbh->beginTransaction();
    try {
    
        // drink_masterへ在庫以外の情報を登録
        // SQL文を作成
        $sql = 'insert into drink_master (
                    drink_name,
                    price,
                    img,
                    status,
                    create_datetime
                    )
                values (?, ?, ?, ?, ?)';
        
        // SQL実行準備
        $stmt = $dbh->prepare($sql);
        
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $drink_name, PDO::PARAM_STR);
        $stmt->bindValue(2, $price, PDO::PARAM_STR);
        $stmt->bindValue(3, $new_img_filename, PDO::PARAM_STR);
        $stmt->bindValue(4, $status, PDO::PARAM_STR);
        $stmt->bindValue(5, $datetime, PDO::PARAM_STR);
        
        // SQLを実行
        $stmt->execute();
        
        // 今登録した商品のdrink_idを取得
        $drink_id = $dbh->lastInsertId();
        
        // drink_stockに在庫数を登録
        // SQL文作成
        $sql = 'insert into drink_stock (
                    drink_id,
                    stock,
                    create_datetime
                )
                values(?, ?, ?)';
        // SQL文実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $drink_id, PDO::PARAM_STR);
        $stmt->bindValue(2, $stock, PDO::PARAM_STR);
        $stmt->bindValue(3, $datetime, PDO::PARAM_STR);
        // SQL実行
        $stmt->execute();
        
        // 完了メッセージの代入
        $cmp_msg[] = '新商品の登録が完了しました';
        
        //コミット処理
        $dbh->commit();
    } catch (PDOExeption $e) {
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
 
function reg_drink_data($dbh) {
    
    global $err_msg;
    
    // 変数初期化
    $drink_name = '';
    $price = '';
    $stock = '';
    $status = '';
    $img = '';
    $img_dir = '';
    
    // 新商品登録のPOST送信がされた時の処理
    if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['register']) === TRUE)) {
        
        // 1)送信された情報の取得
        $drink_name = get_post_data('drink_name');
        $price = get_post_data('price');
        $stock = get_post_data('stock');
        $status = get_post_data('status');
        
        // 2) エラーチェック
        $extension = get_img_extension();
        $new_img_filename = name_img($extension);
        if (total_err_chck($drink_name, $price, $status, $stock, $extension) === TRUE) {
        
        // 3) 画像を保存
        // 画像の保存先
            $img_dir = './img/';
            if(save_img($new_img_filename, $img_dir) === TRUE) {
                
        // 4) DBに情報登録
            insert_drink_data($dbh, $drink_name, $price, $status, $new_img_filename, $stock);
            }
        }
        
    }
}

/**
 * クエリを実行しその結果を配列で取得する
 * @param obj $dbh DBハンドル
 * @param str $sql SQL文
 * @return array 結果配列データ
 */
 
function get_as_array($dbh, $sql) {
    
    // エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;

    // SQLを実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL実行
    $stmt->execute();
    // レコードの取得
    $rows = $stmt->fetchAll();
    
    return $rows;
}

/** 登録商品一覧を取得する
 * @param obj $dbh DBハンドル
 * @return array 商品一覧データ
 */
 
function get_drink_data($dbh) {
    
    // SQL文作成
    $sql = 'select
                drink_master.drink_id,
                drink_name,
                price,
                img,
                stock,
                status
            FROM drink_master INNER JOIN drink_stock
                ON drink_master.drink_id = drink_stock.drink_id
            ORDER BY drink_master.create_datetime DESC';
    
    // クエリ実行
    return get_as_array($dbh, $sql);
}

/** 更新された在庫数をDBのdrink_stockに反映し
 *  @param obj $dbh DBハンドル
 *  @param str $update_stock 在庫数
 *  @param str $update_id 更新された商品ID
 *  drink_masterのupdate_datetimeも更新する
 */
 
function insert_update_stock_data($dbh, $update_stock, $update_id) {
    
    // 完了メッセージはグローバルの$cmp_msgに代入
    global $cmp_msg;
    
    // update_date取得
    $update_datetime = date('Y-m-d H:i:s');
    
    //トランザクション開始
    $dbh->beginTransaction();
    try {
        // drink_stockの更新
        // SQL文の作成
        $sql = 'UPDATE
                    drink_stock
                SET
                    stock = ?,
                    update_datetime = ?
                WHERE drink_id = ?';
                
        // SQL文実行の準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $update_stock, PDO::PARAM_STR);
        $stmt->bindValue(2, $update_datetime, PDO::PARAM_STR);
        $stmt->bindValue(3, $update_id, PDO::PARAM_STR);
        //SQL文を実行
        $stmt->execute();
        
        // drink_masterのupdate_datetimeの更新
        // SQL文作成
        $sql = 'update
                    drink_master
                set
                    update_datetime = ?
                where
                    drink_id = ?';
        // SQL文実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $update_datetime, PDO::PARAM_STR);
        $stmt->bindValue(2, $update_id, PDO::PARAM_STR);

        // SQL文を実行
        $stmt->execute();
        
        // 完了メッセージを代入
        $cmp_msg[] = '在庫数を更新しました';
        
        // コミット処理
        $dbh->commit();
    } catch (PDOExeption $e) {
        // ロールバック処理
        $dbh->rollback();
        throw $e;
    }
}

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

/** 在庫数更新処理が行われた時に以下の処理をする
 * @param obj $dbh DBハンドル  
 */
 
 function update_stock($dbh) {
     
     if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST['update']) === TRUE)) {
         
        // 変数初期化
         
        // 1)送信された情報取得
        $update_stock = get_post_data('update_stock');
        $update_id = get_post_data('update_id');
        
        // 2)エラーチェック
        if (stock_err_chck($update_stock) === TRUE) {
        
        // 3)drink_stockの在庫数を更新
            insert_update_stock_data($dbh, $update_stock, $update_id);
        }

         
     }
 }
 
 /** 公開ステータスの情報をDBのdrink_masterに反映
  * @param obj $dbh DBハンドル
  * @param str $update_status 更新後の公開ステータス
  * @param str $drink_id 更新されたドリンクのID
  */
  
function insert_update_status($dbh, $update_status, $update_id) {
    // 完了メッセージはグローバルの$cmp_msgに代入
    global $cmp_msg;
    
    // update_date取得
    $update_datetime = date('Y-m-d H:i:s');

        // drink_masterの更新
        // SQL文作成
        $sql = 'update
                    drink_master
                set
                    status = ?,
                    update_datetime = ?
                where
                    drink_id = ?';
        // SQL文実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $update_status, PDO::PARAM_STR);
        $stmt->bindValue(2, $update_datetime, PDO::PARAM_STR);
        $stmt->bindValue(3, $update_id, PDO::PARAM_STR);

        // SQL文を実行
        $stmt->execute();
        
        // 完了メッセージを代入
        $cmp_msg[] = '公開ステータスを変更しました';

}
 
 /** 公開ステータスの変更処理
  * @param obj $dbh DBハンドル
  */
  
function change_status($dbh) {
    if ((($_SERVER['REQUEST_METHOD']) === 'POST') && (isset($_POST['change_status']) === TRUE)) {
        
        // 変数初期化
        $update_status = '';
        $update_id = '';
        
        // 1)送信された値の取得
        $update_status = get_post_data('update_status');
        $update_id = get_post_data('update_id');
        
        // 2)DBに情報登録
        insert_update_status($dbh, $update_status, $update_id);
        
    }
}
  
/** 販売可能商品一覧を取得する
 * @param obj $dbh DBハンドル
 * @return array 商品一覧データ
 */
 
function get_item_data($dbh) {
    
    // SQL文作成
    $sql = 'select
                drink_master.drink_id,
                drink_name,
                price,
                img,
                stock,
                status
            FROM drink_master INNER JOIN drink_stock
                ON drink_master.drink_id = drink_stock.drink_id
            WHERE status = 0
            ORDER BY drink_master.create_datetime DESC';
    
    // クエリ実行
    return get_as_array($dbh, $sql);
}


/** エラーチェック
 * @param str $drink_id 選択された商品ID
 * @param str $payment 投入金額
 * @param int $price 商品価格
 * @param str $status 商品の公開ステータス
 * @return bool $chck_flg エラーが一つでもあればFALSE
 */
 
function purchase_err_chck($drink_id, $payment, $price, $status, $stock) {
    
    // エラーメッセージはグローバルの$err_msgに代入
    global $err_msg;
    
    // エラーチェック用フラグ
    $chck_flg = TRUE;
    
    // 商品が選択されているかチェック
    if ($drink_id === '') {
        $err_msg[] = '商品を選択してください';
        $chck_flg = FALSE;
    }
    // 投入金額が正の整数かチェック
    if ($payment === '') {
        $err_msg[] = '金額を入力してください';
        $chck_flg = FALSE;
    } elseif (preg_match('/^[0-9]+$/', $payment) === 0){
        $err_msg[] = '金額は0以上の正の整数で入力してください';
        $chck_flg = FALSE;
    // 投入金額が足りているかチェック
    } elseif ($payment < $price) {
        $err_msg[] = '金額が不足しています';
        $chck_flg = FALSE;
    }
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


/** 購入されたドリンクの価格・商品名・在庫・画像情報・公開ステータスをDBから取得
 * @param obj $dbh DBハンドル
 * @param str $drink_id 選択された商品ID
 * @return array $row 選択された商品の価格と値段
 */
 
function get_selected_drink_data($dbh, $drink_id){
    
    //
    // SQL文作成
    $sql = 'SELECT
                drink_name,
                price,
                status,
                img,
                stock
            FROM drink_master INNER JOIN drink_stock
                ON drink_master.drink_id = drink_stock.drink_id
            WHERE
                drink_master.drink_id = ?';
    
    // SQL文実行準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $drink_id, PDO::PARAM_STR);
    // SQL文実行
    $stmt->execute();
    // レコードの取得
    $row = $stmt->fetch();
    
    return $row;
}


/** お釣り計算
 * @param str $payment 投入金額
 * @param int $price 商品価格
 * @return str $change お釣り
 */

function culc_change($payment, $price){
    $change = '';
    $change = $payment - $price;
    
    return $change;
}

/** 購入履歴テーブルにレコード追加
 * @param obj $dbh DBハンドル
 * @param str $drink_id 購入された商品ID
 * @param date $created_datetime 購入日時
 */

function insert_history($dbh, $drink_id, $created_datetime) {
    
    // SQL文作成
    $sql = 'INSERT INTO drink_history (
                drink_id,
                create_datetime
                )
            VALUES
                (?, ?)';
    // SQL文実行準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $drink_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $created_datetime, PDO::PARAM_STR);
    // SQL文の実行
    $stmt->execute();
}

/** 購入されたドリンクの在庫をdrink_stockからマイナス1し
 *  drink_masterの更新日時を更新
 * @param obj $dbh DBハンドル
 * @param str $drink_id 購入された商品ID
 * @param date $created_datetime 購入日時
 */

function update_sold_stock($dbh, $drink_id, $created_datetime){

    //　トランザクション開始
    $dbh->beginTransaction();
    try {

        // drink_stockを更新
        // SQL文作成
        $sql = 'UPDATE drink_stock
                SET
                    stock = stock - 1,
                    update_datetime = ?
                WHERE drink_id = ?';
        // SQL文の実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $created_datetime, PDO::PARAM_STR);
        $stmt->bindValue(2, $drink_id, PDO::PARAM_STR);
        // SQL実行
        $stmt->execute();
        
        // drink_masterのupdate_datetimeを更新
        // SQL文を作成
        $sql = 'UPDATE drink_master
                SET update_datetime = ?
                WHERE drink_id = ?';
        // SQL文の実行準備
        $stmt = $dbh->prepare($sql);
        // SQL文のプレースホルダに値をバインド
        $stmt->bindValue(1, $created_datetime, PDO::PARAM_STR);
        $stmt->bindValue(2, $drink_id, PDO::PARAM_STR);
        // SQL実行
        $stmt->execute();
        
        // コミット処理
        $dbh->commit();
    } catch (PDOExeption $e) {
        $dbh->rollback();
        throw $e;
    }
}
 

/** ドリンク購入された時の処理
 */

function purchase($dbh) {
    
    // 変数初期化
    $payment = '';
    $drink_id = '';
    $data = [];
    $price = '';
    $status = '';
    $stock = '';
    $created_datetime = '';
    
    if ((($_SERVER['REQUEST_METHOD']) === 'POST') && (isset($_POST['purchase']) === TRUE)) {
        
        // 購入日時を取得
        $created_datetime = date('Y-m-d H:i:s');
    
        // 1)選択された商品の情報を取得
        $payment = get_post_data('payment');
        $drink_id = get_post_data('drink_id');
        $data = get_selected_drink_data($dbh, $drink_id);
        $price = $data['price'];
        $status = $data['status'];
        $stock = $data['stock'];
        
        // 1)エラーチェック
        if(purchase_err_chck($drink_id, $payment, $price, $status, $stock) === TRUE) {
            
        // 2)在庫数をマイナス1する
            update_sold_stock($dbh, $drink_id, $created_datetime);
            
        // 3)購入履歴テーブルにレコードを追加する
            insert_history($dbh, $drink_id, $created_datetime);
        }
        

    }
    

}


