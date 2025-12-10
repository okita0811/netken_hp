<?php
require '../inc/connect_db.php';

// 作品一覧取得
$sql = "SELECT * FROM works ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$works = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>作品紹介 | インターネット研究会</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header>
        <h1>インターネット研究会</h1>
        <nav>
            <a href="index.php">TOP</a>
            <a href="index.php#info">サークル紹介</a>
            <a href="memory.php">活動記録</a>
            <a href="works.php">作品</a>
            <a href="chat.php">相談</a>
        </nav>
    </header>

    <div class="container">
        <h2>作品紹介</h2>

        <?php if (empty($works)): ?>
            <p>まだ作品が登録されていません。</p>
        <?php else: ?>
            <?php foreach ($works as $work): ?>
                <div class="work-card">
                    <h3><?php echo htmlspecialchars($work['title'], ENT_QUOTES); ?></h3>
                    <?php if ($work['image_path']): ?>
                        <img src="../<?php echo $work['image_path']; ?>" alt="作品画像" class="work-img">
                    <?php endif; ?>
                    <p><?php echo nl2br(htmlspecialchars($work['content'], ENT_QUOTES)); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <p><a href="index.php">&laquo; TOPに戻る</a></p>
    </div>

    <footer>
        <p>&copy; 2025 Internet otsuma</p>
    </footer>
</body>

</html>