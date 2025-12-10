<?php
/**
 * hp/contact.php
 * 
 * 社外向けお問い合わせフォームです。
 * 入力された内容をメールで管理者に送信します。
 * 
 * 【学習ポイント】
 * 1. mb_send_mail: 日本語メールを送信するための関数。
 * 2. メールヘッダー: FromやReply-Toなどを設定して、返信しやすくします。
 * 3. データベースからの設定取得: 宛先アドレスをハードコードせずにDBから取得します。
 */
require '../inc/connect_db.php';

$message = "";
$error = "";

// ---------------------------------------------------
// フォーム送信時の処理
// ---------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name']; // お名前
    $email = $_POST['email']; // 返信先メールアドレス
    $subject = $_POST['subject']; // 件名
    $body = $_POST['body']; // 本文

    // 必須チェック
    if (empty($name) || empty($email) || empty($body)) {
        $error = "お名前、メールアドレス、お問い合わせ内容は必須です。";
    } else {
        // ---------------------------------------------------
        // 宛先 (管理者) メールアドレスの取得
        // ---------------------------------------------------
        $sql = "SELECT setting_value FROM site_settings WHERE setting_key = 'admin_email'";
        $stmt = $pdo->query($sql);
        $admin_email = $stmt->fetchColumn();

        if ($admin_email) {
            // ---------------------------------------------------
            // メール送信の準備
            // ---------------------------------------------------

            // 日本語の内部エンコーディングを指定 (文字化け防止)
            mb_language("Japanese");
            mb_internal_encoding("UTF-8");

            // メールの宛先
            $to = $admin_email;

            // メールの件名 (ユーザー名込み)
            $mail_subject = "[HPお問い合わせ] " . $subject . " (" . $name . "様)";

            // メールの本文
            $mail_body = "ホームページのお問い合わせフォームから連絡がありました。\n\n";
            $mail_body .= "【お名前】\n" . $name . "\n\n";
            $mail_body .= "【メールアドレス】\n" . $email . "\n\n";
            $mail_body .= "【お問い合わせ内容】\n" . $body . "\n\n";
            $mail_body .= "---------------------------------------------------\n";
            $mail_body .= "送信日時: " . date("Y-m-d H:i:s");

            // メールヘッダー (送信元、返信先)
            // Fromはサーバーの設定にもよりますが、一般的にはサーバーのドメインのアドレスを指定します。
            // ここでは簡易的にユーザーのアドレスをFromにしていますが、
            // 迷惑メール判定されやすいので、実運用では適切なFromを設定し、Reply-Toにユーザーのアドレスを指定します。
            $header = "From: " . $email . "\n";
            $header .= "Reply-To: " . $email;

            // ---------------------------------------------------
            // メール送信実行
            // ---------------------------------------------------
            if (mb_send_mail($to, $mail_subject, $mail_body, $header)) {
                $message = "お問い合わせを受け付けました。ご連絡ありがとうございます。";
            } else {
                $error = "メールの送信に失敗しました。サーバーの設定をご確認ください。(ローカル環境では送信できない場合があります)";
            }
        } else {
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
            <a href="memory.php">活動記録</a>
            <a href="works.php">作品</a>
            <a href="chat.php">相談</a>
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