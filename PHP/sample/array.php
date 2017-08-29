<?php

echo ("【CASE1】 小嶋さんの元々の処理の簡略版") . PHP_EOL;
// 変数を初期化
$item_review = init();

// 配列を回してrateを計算
foreach ($item_review as $value) {
    // rate を 100で割る
    $value['rate'] = $value['rate'] / 100;
}

// 計算結果出力
print_r($item_review);

echo ("【CASE２】 植田さんの言っていた処理") . PHP_EOL;
$item_review = init();
foreach ($item_review as $key => $value) {
    // rate を 100で割る
    // $keyを指定して上げる必要がある
    $item_review[$key]['rate'] = $value['rate'] / 100;
}
print_r($item_review);

echo ("【CASE3】 foreach を参照変数で回す") . PHP_EOL;
$item_review = init();
foreach ($item_review as &$value) {
    // rate を 100で割る
    // $key を使う必要がない
    $value['rate'] = $value['rate'] / 100;
}
print_r($item_review);



function init() {
    return array(
        array('review_id' => '3','rate' => '15'),
        array('review_id' => '4','rate' => '35'),
        array('review_id' => '5','rate' => '30'),
/*
        array('review_id' => '6','rate' => '15'),
        array('review_id' => '7','rate' => '50'),
        array('review_id' => '8','rate' => '10'),
        array('review_id' => '9','rate' => '40'),
        array('review_id' => '10','rate' => '10'),
        array('review_id' => '11','rate' => '35'),
        array('review_id' => '12','rate' => '10'),
        array('review_id' => '13','rate' => '30')
*/
    );
}
