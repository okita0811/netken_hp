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

// 削除処理
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $sql = "DELETE FROM works WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $delete_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $message = "削除しました。";
    }
}

// ---------------------------------------------------
// 新規追加処理（画像アップロード含む）
// ---------------------------------------------------
if (isset($_POST['add_work'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    /**
     * 【学習ポイント】画像アップロード処理
     * フォームから送信されたファイルは、スーパーグローバル変数 $_FILES に格納されます。
     * $_FILES['input名']['name']     : 元のファイル名
     * $_FILES['input名']['tmp_name'] : サーバー上の一時保存パス
     * $_FILES['input名']['error']    : エラーコード (0なら成功)
     */
    $image_path = "";

    // 画像が選択されていて、エラーがない場合 (UPLOAD_ERR_OK = 0)
    if (isset($_FILES['work_image']) && $_FILES['work_image']['error'] === UPLOAD_ERR_OK) {

        // ファイル名の衝突を避けるため、日時と乱数でユニークな名前を生成します
        $file_name = date('YmdHis') . "_" . mt_rand(100, 999);

        // pathinfo関数で元の拡張子(.jpgなど)を取得します
        $extension = pathinfo($_FILES['work_image']['name'], PATHINFO_EXTENSION);

        // 保存するファイル名
        $save_filename = $file_name . "." . $extension;

        // サーバー上の実際の保存先パス (admin/ から見た相対パス)
        $save_path = "../img/uploads/" . $save_filename;

        // 保存先ディレクトリが存在しない場合は作成します (0777は書き込み権限あり)
        if (!file_exists('../img/uploads')) {
            mkdir('../img/uploads', 0777, true);
        }

        // move_uploaded_file() で一時フォルダから指定の場所にファイルを移動させます
        // これが成功すればアップロード完了です
        if (move_uploaded_file($_FILES['work_image']['tmp_name'], $save_path)) {
            // データベースには、公開ページ(hp/)から見たパスを保存しておくと表示時に便利です
            // 例: "img/uploads/20251210_123.jpg"
            $image_path = "img/uploads/" . $save_filename;
        } else {
            $error = "画像のアップロードに失敗しました。";
        }
    }

    if (empty($error)) {
        if (!empty($title) && !empty($content)) {
            $sql = "INSERT INTO works (title, content, image_path) VALUES (:title, :content, :image_path)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);
            if ($stmt->execute()) {
                $message = "作品を追加しました。";
            }
        } else {
            $error = "タイトルと説明文は必須です。";
        }
    }
}

// 作品一覧取得
$sql = "SELECT * FROM works ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$works = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>作品管理</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <h1>作品管理</h1>
    <p><a href="index.php">&laquo; ダッシュボードに戻る</a></p>

    <?php if ($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <h2>新規作品追加</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <label>作品名:</label>
        <input type="text" name="title" required style="width:300px;">
        <br><br>
        <label>作品説明:</label><br>
        <textarea name="content" rows="4" cols="50" required></textarea>
        <br><br>
        <label>作品画像:</label>
        <input type="file" name="work_image" accept="image/*">
        <br><br>
        <button type="submit" name="add_work">追加する</button>
    </form>

    <hr>

    <h2>登録済み作品一覧</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>画像</th>
            <th>作品名</th>
            <th>説明</th>
            <th>操作</th>
        </tr>
        <?php foreach ($works as $work): ?>
            <tr>
                <td><?php echo $work['id']; ?></td>
                <td>
                    <?php if ($work['image_path']): ?>
                        <img src="../<?php echo $work['image_path']; ?>" width="100">
                    <?php else: ?>
                        (画像なし)
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($work['title'], ENT_QUOTES); ?></td>
                <td><?php echo nl2br(htmlspecialchars($work['content'], ENT_QUOTES)); ?></td>
                <td>
                    <form method="post" action="" onsubmit="return confirm('本当に削除しますか？');">
                        <input type="hidden" name="delete_id" value="<?php echo $work['id']; ?>">
                        <button type="submit">削除</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>