<?php
// 管理者アカウントの登録

// DB接続設定
$dsn = 'mysql:dbname=tb270783db;host=localhost';
$user = 'tb-270783';
$password = 'e6MtwgenZz';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

// 現在の日時
$now = date('Y-m-d H:i:s');

// 1. 管理者 (ID: admin / Pass: password123)
$pass_hash = password_hash('password123', PASSWORD_DEFAULT);
$sql = "INSERT INTO admins (username, password_hash, created_at) VALUES ('admin', '$pass_hash', '$now')";
$pdo->query($sql);

echo "管理者アカウントを作成しました。<br>";
echo "ID: <strong>admin</strong><br>";
echo "PASS: <strong>password123</strong><br>";
?>