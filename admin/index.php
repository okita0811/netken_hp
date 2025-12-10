<?php
session_start();
require '../inc/connect_db.php';

// ログインチェック
// セッションにadmin_idがない場合はログインページへリダイレクト
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理画面トップ</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>管理画面ダッシュボード</h1>
    <!-- ログイン中のユーザー名を表示 -->
    <p>ようこそ、<?php echo htmlspecialchars($_SESSION['admin_username'], ENT_QUOTES); ?> さん</p>
    
    <ul class="menu-list">
        <li><a href="post_create.php">活動記録の投稿</a></li>
        <li><a href="info_manager.php">お知らせの管理</a></li>
        <li><a href="works_manager.php">作品の管理</a></li>
        <li><a href="chat_manager.php">チャットログ管理</a></li>
        <li><a href="settings.php">サイト設定(メール等)</a></li>
        <li><a href="logout.php">ログアウト</a></li>
    </ul>

    <hr>
    <p><a href="../hp/index.php" target="_blank">公開ページを確認する</a></p>
</body>
</html>