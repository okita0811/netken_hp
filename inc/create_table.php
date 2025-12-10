<?php
// DB 接続設定
$dsn = 'mysql:dbname=tb270783db;host=localhost';
$user = 'tb-270783';
$password = 'e6MtwgenZz';

//エラーがあれば表示
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

// ---------------------------
// 管理者テーブルの作成
// ---------------------------
$sql = "CREATE TABLE IF NOT EXISTS admins"
    . " ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "username VARCHAR(50)," // ログインID
    . "password_hash VARCHAR(255)," // パスワード（ハッシュ化）
    . "created_at DATETIME DEFAULT CURRENT_TIMESTAMP" // 作成日時
    . ");";

$stmt = $pdo->query($sql);

// ---------------------------
// 活動記録テーブル
// ---------------------------
$sql = "CREATE TABLE IF NOT EXISTS activity_logs"
    . " ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "title VARCHAR(50)," // 記事のタイトル
    . "content text," // 記事の本文
    . "image_path VARCHAR(255)," // 画像のファイル名/パス
    . "post_date DATETIME," // 活動を行った日付
    . "created_at DATETIME DEFAULT CURRENT_TIMESTAMP" // 投稿日時
    . ");";

$stmt = $pdo->query($sql);

// --------------------------
// チャットテーブル
// --------------------------
$sql = "CREATE TABLE IF NOT EXISTS chats"
    . " ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "sender_name VARCHAR(50)," // 送信者名（匿名可）
    . "message text," // メッセージ内容
    . "is_admin INT DEFAULT 0," // 判別用0:学生,1:管理者
    . "created_at DATETIME DEFAULT CURRENT_TIMESTAMP" // 送信日時
    . ");";

$stmt = $pdo->query($sql);

// --------------------------
// お知らせテーブル
// --------------------------
$sql = "CREATE TABLE IF NOT EXISTS infos"
    . " ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "content text," // お知らせ内容
    . "post_date DATETIME," // 掲載日
    . "created_at DATETIME DEFAULT CURRENT_TIMESTAMP" // 作成日時
    . ");";

$stmt = $pdo->query($sql);

// --------------------------
// 作品テーブル
// --------------------------
$sql = "CREATE TABLE IF NOT EXISTS works"
    . " ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "title VARCHAR(100)," // 作品名
    . "content text," // 作品説明
    . "image_path VARCHAR(255)," // 作品画像パス
    . "created_at DATETIME DEFAULT CURRENT_TIMESTAMP" // 作成日時
    . ");";

$stmt = $pdo->query($sql);

// --------------------------
// サイト設定テーブル (管理者メールアドレスなど)
// --------------------------
$sql = "CREATE TABLE IF NOT EXISTS site_settings"
    . " ("
    . "setting_key VARCHAR(50) PRIMARY KEY," // 設定キー (例: admin_email)
    . "setting_value text" // 設定値
    . ");";

$stmt = $pdo->query($sql);

//作成確認
echo "データベースのテーブル作成が完了しました。";
?>