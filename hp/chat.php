<?php
/**
 * hp/chat.php
 * 
 * 匿名相談窓口ページです。
 * ユーザーからの入力を受け取り、データベース(chatsテーブル)に保存します。
 * また、保存されたデータを読み込んで表示します。
 * 
 * 【学習ポイント】
 * 1. POSTメソッド: フォームから送信されたデータを受け取る方法です。
 * 2. PDO (PHP Data Objects): データベースと安全にやり取りするためのクラスです。
 * 3. プリペアドステートメント: SQLインジェクション攻撃を防ぐための安全なSQL実行方法です。
 * 4. PRG (Post-Redirect-Get) パターン: フォーム送信後にリロードしても二重送信されないようにリダイレクトします。
 */
require '../inc/connect_db.php';

$message = "";
$error = "";

// ---------------------------------------------------
// 1. 投稿処理 (POSTメソッドでデータが送られてきた場合)
// ---------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_name = $_POST['sender_name'];
    $chat_message = $_POST['chat_message'];

    // 名前が空なら「匿名」にする
    if (empty($sender_name)) {
        $sender_name = '匿名学生';
    }

    // メッセージが空でなければ保存処理を行います
    if (!empty($chat_message)) {
        // SQL文の準備
        $sql = "INSERT INTO chats (sender_name, message, is_admin, created_at) VALUES (:name, :message, 0, NOW())";
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':name', $sender_name, PDO::PARAM_STR);
        $stmt->bindValue(':message', $chat_message, PDO::PARAM_STR);

        // SQLを実行
        if ($stmt->execute()) {
            // 送信成功時 (PRGパターン)
            header("Location: chat.php");
            exit;
        } else {
            $error = "送信に失敗しました。";
        }
    } else {
        $error = "メッセージを入力してください。";
    }
}

// ---------------------------------------------------
// 2. チャットログ取得 (データの読み込み)
// ---------------------------------------------------
// 最新の50件のみを取得します
$sql = "SELECT * FROM chats ORDER BY created_at DESC LIMIT 50";
$stmt = $pdo->query($sql);
$chats = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>相談窓口 | インターネット研究会</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header>
        <h1>インターネット研究会</h1>
        <nav>
            <a href="index.php">TOP</a>
            <a href="memory.php">活動記録</a>
            <a href="chat.php">相談</a>
        </nav>
    </header>

    <div class="container">
        <h2>気軽な相談窓口</h2>
        <p>活動内容についての質問や、パソコンに関する相談など、匿名で気軽に書き込めます。</p>

        <?php if ($error): ?>
                <p style="color:red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
        <?php endif; ?>

        <div class="chat-box">
            <?php foreach ($chats as $chat): ?>
                    <div class="chat-item">
                        <span style="font-size:0.8em; color:#888;"><?php echo $chat['created_at']; ?></span>
                        <br>
                        <strong>
                            <?php if ($chat['is_admin']): ?>
                                    <span class="admin-msg">管理者</span>
                            <?php else: ?>
                                    <?php echo htmlspecialchars($chat['sender_name'], ENT_QUOTES); ?>
                            <?php endif; ?>
                        </strong>:
                        <?php echo nl2br(htmlspecialchars($chat['message'], ENT_QUOTES)); ?>
                    </div>
            <?php endforeach; ?>
        </div>

        <form method="post" action="">
            <label>お名前 (匿名可):</label>
            <input type="text" name="sender_name" placeholder="匿名学生">
            <br><br>
            <label>メッセージ:</label><br>
            <textarea name="chat_message" rows="3" style="width:100%;" required></textarea>
            <br>
            <button type="submit" style="margin-top:10px;">送信する</button>
        </form>

        <p><a href="index.php">&laquo; TOPに戻る</a></p>
    </div>

    <footer>
        <p>&copy; 2025 Internet otsuma</p>
    </footer>
</body>
</html>