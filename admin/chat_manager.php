<?php
session_start();
require '../inc/connect_db.php';

// ログインチェック
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

// 削除処理
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $sql = "DELETE FROM chats WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $delete_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $message = "メッセージを削除しました。";
    }
}

// チャットログ一覧取得
$sql = "SELECT * FROM chats ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$chats = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>チャットログ管理</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <h1>チャットログ管理</h1>
    <p><a href="index.php">&laquo; ダッシュボードに戻る</a></p>

    <?php if ($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>日時</th>
            <th>送信者</th>
            <th>種別</th>
            <th>メッセージ</th>
            <th>操作</th>
        </tr>
        <?php foreach ($chats as $chat): ?>
            <tr>
                <td><?php echo $chat['id']; ?></td>
                <td><?php echo $chat['created_at']; ?></td>
                <td><?php echo htmlspecialchars($chat['sender_name'], ENT_QUOTES); ?></td>
                <td><?php echo $chat['is_admin'] ? '管理者' : '学生'; ?></td>
                <td><?php echo nl2br(htmlspecialchars($chat['message'], ENT_QUOTES)); ?></td>
                <td>
                    <form method="post" action="" onsubmit="return confirm('本当に削除しますか？');">
                        <input type="hidden" name="delete_id" value="<?php echo $chat['id']; ?>">
                        <button type="submit">削除</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>