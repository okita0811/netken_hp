<?php
session_start();
require '../inc/connect_db.php';

// ログインチェック
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

// 保存処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_email = $_POST['admin_email'];

    // 設定テーブルに保存 (KEY: admin_email)
    // 既存ならUPDATE、なければINSERT (ON DUPLICATE KEY UPDATE)
    $sql = "INSERT INTO site_settings (setting_key, setting_value) VALUES ('admin_email', :email)
            ON DUPLICATE KEY UPDATE setting_value = :email_update";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $admin_email, PDO::PARAM_STR);
    $stmt->bindValue(':email_update', $admin_email, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $message = "設定を保存しました。";
    } else {
        $message = "保存に失敗しました。";
    }
}

// 現在の設定を取得
$sql = "SELECT setting_value FROM site_settings WHERE setting_key = 'admin_email'";
$stmt = $pdo->query($sql);
$current_email = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>サイト設定</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <h1>サイト設定</h1>
    <p><a href="index.php">&laquo; ダッシュボードに戻る</a></p>

    <?php if ($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>お問い合わせ受信メールアドレス:</label><br>
        <input type="email" name="admin_email"
            value="<?php echo htmlspecialchars($current_email ? $current_email : '', ENT_QUOTES); ?>" required
            style="width: 300px;">
        <br><br>
        <button type="submit">保存する</button>
    </form>
</body>

</html>