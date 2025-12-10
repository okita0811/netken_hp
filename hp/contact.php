$error = "管理者メールアドレスが設定されていないため送信できません。";
}
}
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>お問い合わせ | インターネット研究会</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header>
        <h1>インターネット研究会</h1>
        <nav>
            <a href="index.php">TOP</a>
            <a href="index.php#info">サークル紹介</a>
            <a href="contact.php">お問い合わせ</a>
        </nav>
    </header>

    <div class="container">
        <h2>業務的なお問い合わせ</h2>
        <p>企業様や大学関係者様からのご連絡はこちらからお願いします。</p>

        <?php if ($message): ?>
            <p style="color:green; font-weight:bold;"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p style="color:red; font-weight:bold;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <label>お名前 (必須):</label><br>
            <input type="text" name="name" required style="width: 300px;">
            <br><br>

            <label>メールアドレス (必須):</label><br>
            <input type="email" name="email" required style="width: 300px;">
            <br><br>

            <label>件名:</label><br>
            <input type="text" name="subject" value="お問い合わせ" style="width: 300px;">
            <br><br>

            <label>お問い合わせ内容 (必須):</label><br>
            <textarea name="body" rows="8" style="width: 100%;" required></textarea>
            <br><br>

            <button type="submit">送信する</button>
        </form>

        <p><a href="index.php">&laquo; TOPに戻る</a></p>
    </div>

    <footer>
        <p>&copy; 2025 Internet otsuma</p>
    </footer>
</body>

</html>