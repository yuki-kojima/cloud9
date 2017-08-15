<?php
    $enemy = ['グー', 'チョキ', 'パー'];
    $key = array_rand($enemy);
    $enemy_choice = $enemy[$key];
    
    $r_p_s = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['r_p_s']) === TRUE) {
            $r_p_s = htmlspecialchars($_POST['r_p_s'], ENT_QUOTES, 'UTF-8');
        }else {
            $r_p_s = '未選択';
        }
    }
    
    $result = '';
    if ($r_p_s === '未選択') {
        $result = '';
    }else if ($r_p_s === $enemy_choice){
        $result = 'draw';
    }else if ($r_p_s === 'グー' && $enemy_choice === 'チョキ' || $r_p_s === 'パー' && $enemy_choice === 'グー' || $r_p_s === 'チョキ' && $enemy_choice === 'パー') {
        $result = 'win!';        
    }else {
        $result = 'lose...';
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>課題３</title>
    </head>
    <body>
        <h1>じゃんけん勝負</h1>
        <p>自分：<?php if ($r_p_s !== '') { print $r_p_s; } ?></p>
        <p>相手：<?php if ($r_p_s !== '') { print $enemy_choice; } ?></p>
        <p>結果：<?php if ($r_p_s !== '') { print $result; } ?></p>
        <form method="POST">
            <p>
                <input type="radio" name="r_p_s" value="グー">グー
                <input type="radio" name="r_p_s" value="チョキ">チョキ
                <input type="radio" name="r_p_s" value="パー">パー
            </p>
            <p>
                <input type="submit" value="勝負！">
            </p>
        </form>
    </body>
    
</html>