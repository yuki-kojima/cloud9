<?php

// 設定ファイルを読み込み
require_once './conf/const.php';

// 関数ファイルを読み込み
require_once './model/common_model.php';
require_once './model/logout_model.php';

// 変数初期化

// ログアウト処理し、ログインページへ遷移
logout();

