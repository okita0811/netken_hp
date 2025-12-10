<?php
require '../inc/connect_db.php';

// 最新の活動記録を3件だけ取得 (LIMIT 3)
$sql = "SELECT * FROM activity_logs ORDER BY post_date DESC LIMIT 3";
$stmt = $pdo->query($sql);
$latest_logs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>インターネット研究会 | サークル公式サイト</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header>
        <h1>インターネット研究会</h1>
        <nav>
            <a href="index.php">TOP</a>
            <a href="info.php">お知らせ</a>
            <a href="index.php#info">サークルについて</a>
            <a href="memory.php">活動記録</a>
            <a href="works.php">作品</a>
            <a href="chat.php">相談</a>
            <a href="contact.php">お問い合わせ</a>
        </nav>
    </header>

    <div class="container">

        <div class="section" id="info">
            <h2>サークルについて</h2>
            <p>私たちインターネット研究会はパソコンを使用し、作品製作を行っています<br>
                大妻祭出展を目指して楽しく活動をしています</p>
            <ul>
                <li><strong>活動日：</strong> 毎週 月・木曜日 16:30〜</li>
                <li><strong>場所：</strong> H棟 地下1F教室(授業によって変更します)</li>
            </ul>
        </div>

        <div class="section">
            <h2>最新の活動記録</h2>
            <?php if (empty($latest_logs)): ?>
                <p>まだ投稿がありません。</p>
            <?php else: ?>
                <?php foreach ($latest_logs as $log): ?>
                    <div class="log-card">
                        <?php if ($log['image_path']): ?>
                            <img src="../<?php echo $log['image_path']; ?>" alt="活動画像" class="log-thumb">
                        <?php else: ?>
                            <div class="log-thumb" style="display:flex;align-items:center;justify-content:center;">No Image</div>
                        <?php endif; ?>

                        <div>
                            <small><?php echo $log['post_date']; ?></small>
                            <h3><?php echo htmlspecialchars($log['title'], ENT_QUOTES); ?></h3>
                            <p><?php echo mb_substr(htmlspecialchars($log['content'], ENT_QUOTES), 0, 50); ?>...</p>
                            <a href="memory.php">もっと見る</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <p style="text-align:right;"><a href="memory.php">&raquo; 活動記録一覧へ</a></p>

            <h2>SNS</h2>
            <!-- X (Twitter) Icon -->
            <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer" style="text-decoration:none;">
                <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" style="fill: #000;">
                    <path
                        d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z">
                    </path>
                </svg>
            </a>
        </div>

    </div>

    <footer>
        <p>&copy; 2025 Internet otsuma</p>
    </footer>

</body>

</html>