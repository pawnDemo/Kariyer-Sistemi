<?php
include '../includes/auth.php';
include '../config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];
$error = '';
$success = '';

// Güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    if (empty($title) || empty($description)) {
        $error = "Başlık ve açıklama boş olamaz.";
    } else {
        $stmt = $conn->prepare("UPDATE job_posts SET title = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $description, $id);
        if ($stmt->execute()) {
            $success = "İş ilanı başarıyla güncellendi.";
        } else {
            $error = "Güncelleme sırasında hata oluştu.";
        }
        $stmt->close();
    }
}

// Mevcut veriyi çek
$stmt = $conn->prepare("SELECT title, description FROM job_posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($title, $description);
if (!$stmt->fetch()) {
    $stmt->close();
    header("Location: index.php");
    exit();
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>İş İlanını Düzenle</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <style>
        body { font-family: Arial, sans-serif; background:#f0f2f5; padding:30px; }
        form { background: white; padding: 20px; border-radius: 10px; max-width: 600px; margin: auto; }
        input, textarea { width: 100%; margin-bottom: 15px; padding: 10px; font-size: 16px; border-radius: 6px; border: 1px solid #ccc; }
        button { background: #1877f2; color: white; border: none; padding: 12px 20px; font-size: 16px; border-radius: 30px; cursor: pointer; }
        button:hover { background: #155db2; }
        .msg { margin-bottom: 15px; font-weight: 600; }
        .error { color: #e53e3e; }
        .success { color: #38a169; }
        a { display: inline-block; margin-bottom: 20px; color: #1877f2; text-decoration: none; }
    </style>
</head>
<body>
    <a href="index.php">&laquo; Geri Dön</a>
    <form method="post" action="">
        <h2>İş İlanını Düzenle</h2>
        <?php if ($error): ?>
            <p class="msg error"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($success): ?>
            <p class="msg success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <label for="title">Başlık:</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($title) ?>" required>

        <label for="description">Açıklama:</label>
        <textarea name="description" id="description" rows="5" required><?= htmlspecialchars($description) ?></textarea>

        <button type="submit">Güncelle</button>
    </form>
</body>
</html>
