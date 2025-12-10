<?php
session_start();
$_SESSION = array(); // セッション変数を空にする
session_destroy();   // セッションを破壊
header("Location: login.php"); // ログイン画面に戻る
?>