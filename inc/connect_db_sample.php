<?php
// GitHub公開用のサンプルファイルです
// 実際利用する際はファイル名を db_connect.php に変更し、設定を埋めてください
$dsn = 'mysql:dbname=YOUR_DB_NAME;host=localhost';
$user = 'YOUR_USER_NAME';
$password = 'YOUR_PASSWORD';

try {
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    exit('データベース接続失敗: ' . $e->getMessage());
}