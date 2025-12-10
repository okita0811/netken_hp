<?php
require '../inc/connect_db.php';

// お知らせ一覧取得
$sql = "SELECT * FROM infos ORDER BY post_date DESC";
$stmt = $pdo->query($sql);
$infos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>お知らせ | インターネット研究会</title>
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
        <h2>お知らせ</h2>

        <?php if (empty($infos)): ?>
            <p>現在お知らせはありません。</p>
        <?php else: ?>
            <ul>
                <?php foreach ($infos as $info): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($info['post_date'], ENT_QUOTES); ?></strong><br>
                        <?php echo nl2br(htmlspecialchars($info['content'], ENT_QUOTES)); ?>
                    </li>
                    <br>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <p><a href="index.php">&laquo; TOPに戻る</a></p>
    </div>

    <footer>
        <p>&copy; 2025 Internet otsuma</p>
    </footer>
</body>

</html>