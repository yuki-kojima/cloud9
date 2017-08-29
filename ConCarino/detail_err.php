<?php

// 設定ファイルを読み込み
require_once './conf/const.php';

// 関数ファイルを読み込み
require_once './model/common_model.php';
require_once './model/session_check_model.php';
require_once './model/index_model.php';
require_once './model/detail_model.php';


//商品詳細画面エラーテンプレートファイル読み込み
include_once './view/detail_err_view.php';