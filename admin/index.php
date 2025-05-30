<?php 
include '../includes/auth.php'; 
include '../config/db.php'; 

// Durum değiştirme işlemi
if (isset($_GET['toggle_id'])) {
    $toggle_id = (int)$_GET['toggle_id'];
    // Mevcut durumu öğren
    $stmt = $conn->prepare("SELECT is_active FROM job_posts WHERE id = ?");
    $stmt->bind_param("i", $toggle_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {
        $row = $result->fetch_assoc();
        $new_status = $row['is_active'] ? 0 : 1;
        $stmt_update = $conn->prepare("UPDATE job_posts SET is_active = ? WHERE id = ?");
        $stmt_update->bind_param("ii", $new_status, $toggle_id);
        $stmt_update->execute();
        $stmt_update->close();
    }
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Paneli - İş İlanları</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        /* Genel ayarlar */
        body {
            font-family: 'Roboto', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgb(0 0 0 / 0.1);
        }
        h1 {
            margin-bottom: 20px;
            color: #1877f2;
            font-weight: 700;
            font-size: 28px;
        }
        .topnav {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
            gap: 15px;
        }
        .topnav a {
            text-decoration: none;
            color: #1877f2;
            font-weight: 600;
            padding: 8px 15px;
            border-radius: 6px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            background-color: #e7f3ff;
        }
        .topnav a:hover {
            background-color: #1877f2;
            color: white;
            border-color: #1877f2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
            color: #333;
        }
        thead {
            background-color: #1877f2;
            color: white;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }
        tbody tr:hover {
            background-color: #f5f9ff;
        }
        .actions a {
            margin-right: 12px;
            color: #1877f2;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .actions a:hover {
            color: #e2e2e2;
        }
        .msg {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 600;
        }
        .msg.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .msg.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status-active {
            color: #28a745;
            font-weight: 700;
        }
        .status-inactive {
            color: #dc3545;
            font-weight: 700;
        }
        .toggle-btn {
            background-color: #e2e2e2;
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .toggle-btn:hover {
            background-color: #0f52ba;
        }
    </style>
</head>
<script>
document.querySelectorAll('.toggle-btn').forEach(function(el) {
    el.addEventListener('click', function(e) {
        if(!confirm('Durumu değiştirmek istediğinize emin misiniz?')) {
            e.preventDefault();
        }
    });
});
</script>

<body>
    <div class="container">
        <h1>Admin Paneli - İş İlanları</h1>
        <div class="topnav">
            <a href="add_job.php">+ Yeni İş İlanı Ekle</a>
            <a href="view_applications.php">Başvuruları Gör</a>
            <a href="logout.php">Çıkış</a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] === 'deleted'): ?>
                <div class="msg success">İş ilanı başarıyla silindi.</div>
            <?php elseif ($_GET['msg'] === 'error'): ?>
                <div class="msg error">Silme işlemi sırasında hata oluştu.</div>
            <?php endif; ?>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>Açıklama</th>
                    <th>Oluşturulma Tarihi</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $stmt = $conn->prepare("SELECT id, title, description, created_at, is_active FROM job_posts ORDER BY created_at DESC");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars(mb_strimwidth($row['description'], 0, 60, '...')) ?></td>
                    <td><?= date('d.m.Y H:i', strtotime($row['created_at'])) ?></td>
                    <td class="<?= $row['is_active'] ? 'status-active' : 'status-inactive' ?>">
                        <?= $row['is_active'] ? 'Aktif' : 'Pasif' ?>
                    </td>
                    <td class="actions">
                        <a href="edit_job.php?id=<?= $row['id'] ?>">Düzenle</a>
                        <a href="?toggle_id=<?= $row['id'] ?>" class="toggle-btn" onclick="return confirm('Durumu değiştirmek istediğinize emin misiniz?');">
                            <?= $row['is_active'] ? 'Pasif Yap' : 'Aktif Yap' ?>
                        </a>
                        <a href="delete_job.php?id=<?= $row['id'] ?>" onclick="return confirm('Bu iş ilanını silmek istediğinize emin misiniz?');" style="color:#e03e2f;">Sil</a>
                    </td>
                </tr>
            <?php endwhile; 
            $stmt->close();
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>
