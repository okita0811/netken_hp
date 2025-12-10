<?php
session_start();
require '../inc/connect_db.php';

// ログインチェック
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$error = "";

// ---------------------------------------------------
// 削除処理
// ---------------------------------------------------
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $sql = "DELETE FROM activity_logs WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $delete_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $message = "記事を削除しました。";
    } else {
        $error = "削除に失敗しました。";
    }
}

// ---------------------------------------------------
// 投稿ボタンが押された時の処理
// ---------------------------------------------------
if (isset($_POST['add_post'])) {
    // 1. 入力値の取得
    $title = $_POST['title'];
    $content = $_POST['content'];
    $post_date = $_POST['post_date'];

    // 2. バリデーション（必須チェック）
    if (empty($title) || empty($content) || empty($post_date)) {
        $error = "タイトル、本文、日付は必須です。";
    } else {
        // 3. 画像アップロード処理
        $image_path = ""; // 画像がない場合は空文字またはNULL

        // 画像が選択されていて、エラーがない場合
        if (isset($_FILES['upload_image']) && $_FILES['upload_image']['error'] === UPLOAD_ERR_OK) {

            // ファイル名が重複しないように現在時刻と乱数で名前を生成する
            $file_name = date('YmdHis') . "_" . mt_rand(100, 999);

            // 元の拡張子を取得 (例: .jpg)
            $extension = pathinfo($_FILES['upload_image']['name'], PATHINFO_EXTENSION);

            // 保存先のパス (img/uploads/20251207... .jpg)
            $save_filename = $file_name . "." . $extension;
            $save_path = "../img/uploads/" . $save_filename;

            // ディレクトリ作成
            if (!file_exists('../img/uploads')) {
                mkdir('../img/uploads', 0777, true);
            }

            // ファイルを一時フォルダから指定の場所に移動
            if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $save_path)) {
                // データベースには「保存先パス」を記録する
                $image_path = "img/uploads/" . $save_filename;
            } else {
                $error = "画像のアップロードに失敗しました。";
            }
        }

        // 4. データベースへの保存
        if (empty($error)) {
            $sql = "INSERT INTO activity_logs (title, content, image_path, post_date, created_at) 
                        VALUES (:title, :content, :image_path, :post_date, NOW())";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
            $stmt->bindValue(':post_date', $post_date, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $message = "記事を投稿しました！";
                // 再送信防止のリダイレクトをするとより良いですが、ここではメッセージ表示のみとします
            } else {
                $error = "データベースへの保存に失敗しました。";
            }
        }
    }
}

// ---------------------------------------------------
// 記事一覧取得
// ---------------------------------------------------
$sql = "SELECT * FROM activity_logs ORDER BY post_date DESC";
$stmt = $pdo->query($sql);
$logs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>活動記録管理 (投稿・削除)</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .log-table th,
        .log-table td {
            vertical-align: top;
        }

        .log-img-thumb {
            width: 80px;
            height: auto;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <h1>活動記録管理</h1>
    <p><a href="index.php">&laquo; ダッシュボードに戻る</a></p>

    <?php if ($message): ?>
        <p class="msg"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="err"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <div class="container">
        <h2>新規投稿</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <label>タイトル (必須)</label>
            <input type="text" name="title" required>

            <label>活動日 (必須)</label>
            <input type="date" name="post_date" required value="<?php echo date('Y-m-d'); ?>">

            <label>記事本文 (必須)</label>
            <textarea name="content" required></textarea>

            <label>画像 (任意)</label>
            <input type="file" name="upload_image" accept="image/*">
            <p><small>※jpg, png, gifなどの画像ファイルを選択してください。</small></p>

            <button type="submit" name="add_post" style="margin-top:10px;">投稿する</button>
        </form>
    </div>

    <hr>

    <div style="margin-top: 30px;">
        <h2>投稿済み記事一覧</h2>
        <table class="log-table" border="1" cellpadding="5" style="width:100%;">
            <tr>
                <th>ID</th>
                <th>日付</th>
                <th>画像</th>
                <th>タイトル/本文</th>
                <th>操作</th>
            </tr>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo $log['id']; ?></td>
                    <td><?php echo $log['post_date']; ?></td>
                    <td>
                        <?php if ($log['image_path']): ?>
                            <!-- 管理画面からimgフォルダへのパスは ../img/... -->
                            <img src="../<?php echo $log['image_path']; ?>" class="log-img-thumb">
                        <?php else: ?>
                            <span style="font-size:0.8em; color:#aaa;">(画像なし)</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($log['title'], ENT_QUOTES); ?></strong><br>
                        <small
                            style="color:#555;"><?php echo mb_substr(htmlspecialchars($log['content'], ENT_QUOTES), 0, 100); ?>...</small>
                    </td>
                    <td>
                        <form method="post" action="" onsubmit="return confirm('本当に削除しますか？');">
                            <input type="hidden" name="delete_id" value="<?php echo $log['id']; ?>">
                            <button type="submit"
                                style="background-color:#d9534f; border:none; color:white; padding:5px 10px; margin:0;">削除</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>