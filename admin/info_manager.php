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
    $sql = "DELETE FROM infos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $delete_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $message = "削除しました。";
    }
}

// 新規追加処理
if (isset($_POST['add_info'])) {
    $content = $_POST['content'];
    $post_date = $_POST['post_date'];

    if (!empty($content) && !empty($post_date)) {
        $sql = "INSERT INTO infos (content, post_date) VALUES (:content, :post_date)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':post_date', $post_date, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $message = "お知らせを追加しました。";
        }
    } else {
        $message = "内容と日付は必須です。";
    }
}

// お知らせ一覧取得
$sql = "SELECT * FROM infos ORDER BY post_date DESC";
$stmt = $pdo->query($sql);
$infos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>お知らせ管理</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <h1>お知らせ管理</h1>
    <p><a href="index.php">&laquo; ダッシュボードに戻る</a></p>

    <?php if ($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <h2>新規追加</h2>
    <form method="post" action="">
        <label>日付:</label>
        <input type="date" name="post_date" required value="<?php echo date('Y-m-d'); ?>">
        <br>
        <label>内容:</label><br>
        <textarea name="content" rows="4" cols="50" required></textarea>
        <br>
        <button type="submit" name="add_info">追加する</button>
    </form>

    <hr>

    <h2>既存のお知らせ一覧</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>日付</th>
            <th>内容</th>
            <th>操作</th>
        </tr>
        <?php foreach ($infos as $info): ?>
            <tr>
                <td><?php echo $info['id']; ?></td>
                <td><?php echo $info['post_date']; ?></td>
                <td><?php echo nl2br(htmlspecialchars($info['content'], ENT_QUOTES)); ?></td>
                <td>
                    <form method="post" action="" onsubmit="return confirm('本当に削除しますか？');">
                        <input type="hidden" name="delete_id" value="<?php echo $info['id']; ?>">
                        <button type="submit">削除</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>