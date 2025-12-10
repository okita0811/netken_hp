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

// 投稿ボタンが押された時の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. 入力値の取得
    $title = $_POST['title'];
    $content = $_POST['content'];
    $post_date = $_POST['post_date'];

    // 2. バリデーション（必須チェック）
    if (empty($title) || empty($content) || empty($post_date)) {
        $error = "タイトル、本文、日付は必須です。";
    } else {
        // 3. 画像アップロード処理
        $image_path = null; // 画像がない場合はNULL

        // 画像が選択されていて、エラーがない場合
        if (isset($_FILES['upload_image']) && $_FILES['upload_image']['error'] === UPLOAD_ERR_OK) {

            // ファイル名が重複しないように現在時刻と乱数で名前を生成する
            $file_name = date('YmdHis') . "_" . mt_rand(100, 999);

            // 元の拡張子を取得 (例: .jpg)
            $extension = pathinfo($_FILES['upload_image']['name'], PATHINFO_EXTENSION);

            // 保存先のパス (img/uploads/20251207... .jpg)
            $save_filename = $file_name . "." . $extension;
            $save_path = "../img/uploads/" . $save_filename;

            // ファイルを一時フォルダから指定の場所に移動
            if (move_uploaded_file($_FILES['upload_image']['tmp_name'], $save_path)) {
                // データベースには「保存先パス」を記録する
                // (../img/uploads/ は管理画面からの相対パスなので、表示用に修正して保存してもよいが、今回はそのまま保存し表示時に調整する想定)
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
            $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR); // 画像がない場合はNULLが入る
            $stmt->bindValue(':post_date', $post_date, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $message = "記事を投稿しました！";
            } else {
                $error = "データベースへの保存に失敗しました。";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>活動記録の新規投稿</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <div class="container">
        <h1>活動記録の新規投稿</h1>
        <p><a href="index.php">&laquo; 管理メニューに戻る</a></p>

        <?php if ($message): ?>
            <p class="msg"><?php echo $message; ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="err"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post" action="" enctype="multipart/form-data">

            <label>タイトル (必須)</label>
            <input type="text" name="title" required>

            <label>活動日 (必須)</label>
            <input type="date" name="post_date" required>

            <label>記事本文 (必須)</label>
            <textarea name="content" required></textarea>

            <label>画像 (任意)</label>
            <input type="file" name="upload_image" accept="image/*">
            <p><small>※jpg, png, gifなどの画像ファイルを選択してください。</small></p>

            <button type="submit" style="margin-top:20px; padding:10px 20px;">投稿する</button>
        </form>
    </div>
</body>

</html>