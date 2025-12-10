<?php
// 管理者ログインページ
session_start();

// すでにログイン済みならトップページへ転送
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// エラー表示：htmlに表示させる
$error = "";

// ログインボタンが押された時の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../inc/connect_db.php'; // 前の階層に移動➡inc内共通ファイルを読み込み

    $username = $_POST['username'];
    $password = $_POST['password'];

    // ユーザー名で検索
    $sql = "SELECT * FROM admins WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $admin = $stmt->fetch();

    // パスワードが合っているか確認
    if ($admin && password_verify($password, $admin['password_hash'])) {
        // ログイン成功ならセッションにIDを保存
        session_regenerate_id(true); // セキュリティ対策
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: index.php"); // 管理画面へ移動
        exit;
    } elseif (empty($username) || empty($password)) {
        $error = "IDとパスワードを登録してください。";
    } else {
        // エラーがあった場合格納
        $error = "IDまたはパスワードが間違っています。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>管理者ログイン</title>
</head>

<body>
    <h1>ログイン</h1>
    <h2>管理者ログイン</h2>
    <!--error（変数）にある場合は該当のエラー表示-->
    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <p>
            ID:<br>
            <input type="text" name="username" required>
        </p>
        <p>
            パスワード:<br>
            <input type="password" name="password" required>
        </p>
        <button type="submit">ログイン</button>
    </form>
</body>

</html>