<?php
//そのままprint出力すると文字化けするのでいれてます。
header("Content-type: text/html; charset=utf-8");

//デバッグ用
//print_r($_POST);

if(isset($_POST['product_id'])){
    $id = $_POST['product_id'];
    print $id;
}

if(isset($_POST['product_name'])){
    $name = $_POST['product_name'];
    print $name;
}

?>