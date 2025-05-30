<?php include '../includes/auth.php'; include '../config/db.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $stmt = $conn->prepare("INSERT INTO job_posts (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $desc);
    $stmt->execute();
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>İş İlanı Ekle</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Yeni İş İlanı</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Pozisyon Başlığı" required>
        <textarea name="description" placeholder="Pozisyon Açıklaması" required></textarea>
        <button type="submit">Kaydet</button>
    </form>
</div>
</body>
</html>

---