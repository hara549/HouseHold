<?php
  // セッション開始
  session_start();

  // セッション変数を全て削除
  $_SESSION = array();

  // セッションクッキーを削除
  if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
  }
  // セッションの登録データを削除
  session_destroy();

  // ログインページへ遷移
  require 'index.php';
  exit();
?>
