<?php
require '../inc/connect_db.php';

// 全ての活動記録を新しい順に取得
$sql = "SELECT * FROM activity_logs ORDER BY post_date DESC";
$stmt = $pdo->query($sql);
$logs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>活動記録 | インターネット研究会</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <header>
        <h1>活動記録 (Memory)</h1>
        <nav>
            <a href="index.php">TOP</a>
            <a href="index.php#info">サークルについて</a>
            <a href="memory.php">活動記録</a>
            <a href="works.php">作品</a>
            <a href="chat.php">相談</a>
            <a href="contact.php">お問い合わせ</a>
        </nav>
    </header>

    <div class="container">
        <h2>日々の活動日誌</h2>

        <?php if (empty($logs)): ?>
            <p>現在、記録はありません。</p>
        <?php else: ?>
            <?php foreach ($logs as $log): ?>
                <div class="log-entry">
                    <h3 style="margin-bottom:5px;"><?php echo htmlspecialchars($log['title'], ENT_QUOTES); ?></h3>
                    <p class="log-date">活動日: <?php echo $log['post_date']; ?></p>

                    <div class="log-content">
                        <?php echo nl2br(htmlspecialchars($log['content'], ENT_QUOTES)); ?>
                    </div>

                    <?php if ($log['image_path']): ?>
                        <img src="../<?php echo $log['image_path']; ?>" alt="活動の様子" class="log-img">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2025 internet otsuma</p>
    </footer>

</body>

</html>