<?php
// DB 接続設定
$dsn = 'mysql:dbname=tb270783db;host=localhost';
$user = 'tb-270783';
$password = 'e6MtwgenZz';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//これまでの全てのデータを削除
$sql = 'DROP TABLE admins';
$stmt = $pdo->query($sql);

//これまでの全てのデータを削除
$sql = 'DROP TABLE activity_logs';
$stmt = $pdo->query($sql);

//これまでの全てのデータを削除
$sql = 'DROP TABLE chats';
$stmt = $pdo->query($sql);

//メッセージ
echo "テーブルを削除しました。";
?>