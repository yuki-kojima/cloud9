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
 * 特殊文字をHTMLエンティティに変換する(1次元配列の値)
 * @param array $array　変換前配列
 * @return array 変換後配列
 */
 
function entity_array($array) {
    foreach($array as $key => $value) {
         // 特殊文字をHTMLエンティティに変換
         $array[$key] = entity_str($value);

    }
    
    return $array;
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


/**GETで送信された新商品の情報を取得
 * @param str $key GETで送信されたname属性の値
 * @return str $str GETで送信された情報のvalueの値
 */
 
function get_get_data($key) {
    $str = '';
    if (isset($_GET[$key]) === TRUE) {
        $str = $_GET[$key];
    }
    
    return $str;
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

