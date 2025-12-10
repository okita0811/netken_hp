<?php
// DB 接続設定
$dsn = 'mysql:dbname=tb270783db;host=localhost';
$user = 'tb-270783';
$password = 'e6MtwgenZz';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

// ------------------------------------------
//  テーブル一覧の表示
// ------------------------------------------
echo "<h3>テーブル一覧</h3>";
$sql = 'SHOW TABLES';
$result = $pdo->query($sql);

foreach ($result as $row) {
    echo $row[0];
    echo '<br />';
}
echo "<hr>";

// ------------------------------------------
//  admins テーブルの表示
// ------------------------------------------
echo "<h3>admins テーブル (管理者)</h3>";
$sql = 'SELECT * FROM admins';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();

foreach ($results as $row) {
    echo 'ID: ' . $row['id'] . ', ';
    echo 'PASS: ' . $row['password_hash'] . ', '; //ハッシュ化されているか確認
    echo 'NAME: ' . $row['username'] . '<br>';
}
echo "<hr>";

// ------------------------------------------
//  activity_logs テーブルの表示
// ------------------------------------------
echo "<h3>activity_logs テーブル (活動記録)</h3>";
$sql = 'SELECT * FROM activity_logs';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();

foreach ($results as $row) {
    echo 'ID: ' . $row['id'] . '<br>';
    echo 'TITLE: ' . $row['title'] . '<br>';
    echo 'CONTENT: ' . mb_substr($row['content'], 0, 10) . '...<br>'; // 長いので最初の10文字だけ表示
    echo 'DATE: ' . $row['post_date'] . '<br><br>';
}
echo "<hr>";

// ------------------------------------------
//  chats テーブルの表示
// ------------------------------------------
echo "<h3>chats テーブル (チャット)</h3>";
$sql = 'SELECT * FROM chats';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();

foreach ($results as $row) {
    echo 'ID: ' . $row['id'] . '<br>';
    echo 'NAME: ' . $row['sender_name'] . '<br>';
    echo 'MSG: ' . $row['message'] . '<br><br>';
}
echo "<hr>";

// ------------------------------------------
//  infos テーブルの表示
// ------------------------------------------
echo "<h3>infos テーブル (お知らせ)</h3>";
$sql = 'SELECT * FROM infos';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();

foreach ($results as $row) {
    echo 'ID: ' . $row['id'] . '<br>';
    echo 'DATE: ' . $row['post_date'] . '<br>';
    echo 'CONTENT: ' . mb_substr($row['content'], 0, 10) . '...<br><br>';
}
echo "<hr>";

// ------------------------------------------
//  works テーブルの表示
// ------------------------------------------
echo "<h3>works テーブル (作品)</h3>";
$sql = 'SELECT * FROM works';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();

foreach ($results as $row) {
    echo 'ID: ' . $row['id'] . '<br>';
    echo 'TITLE: ' . $row['title'] . '<br>';
    echo 'IMG: ' . $row['image_path'] . '<br><br>';
}
echo "<hr>";

// ------------------------------------------
//  site_settings テーブルの表示
// ------------------------------------------
echo "<h3>site_settings テーブル (設定)</h3>";
$sql = 'SELECT * FROM site_settings';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();

foreach ($results as $row) {
    echo 'KEY: ' . $row['setting_key'] . '<br>';
    echo 'VALUE: ' . $row['setting_value'] . '<br><br>';
}
?>