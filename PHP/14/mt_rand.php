<pre>
<?php

// mt_rand()の引数を指定しない場合、0 から mt_getrandmax() の値を生成します
var_dump(mt_rand());
print "\n";

//指定の範囲内の値を取得したい場合は最小値と最大値を指定
// １０〜５０までの値を生成
var_dump(mt_rand(10,50));
print "\n";

//memo:maxの数で割れば1を越すことはない＝0-1の間の乱数を取得できる
var_dump(mt_rand(10,50) / 50);
print "\n";
print "\n";


//memo:L21　mt_rand() / mt_getrandmax()→0-1までの小数点がでる。これに($max - $min)をすることで差分を超えない＝returnされる値が$maxを超えない！（最大でも1 * ($max - $min)のため）
function randomFloat($min = 0, $max = 1) {
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}

var_dump(randomFloat());
print "\n";
var_dump(randomFloat(2, 5));
print "\n";
print "\n";


?>

</pre>

