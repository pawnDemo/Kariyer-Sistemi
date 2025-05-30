<?php 
include '../config/db.php';
include '../includes/auth.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Başvurular</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-width: 1000px;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 6px;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 14px 12px;
            text-align: left;
            font-size: 15px;
        }
        th {
            background-color: #e9ecef;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f1f3f5;
        }
        h2 {
            margin-bottom: 10px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        form {
            margin-bottom: 20px;
        }
        .back-button {
            display: inline-block;
            margin-bottom: 15px;
            padding: 8px 14px;
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }
        .back-button:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="/admin" class="back-button">← Geri Dön</a>

    <h2>Başvurular</h2>

    <form method="GET">
        <label for="job_id">Pozisyona Göre Filtrele:</label>
        <select name="job_id" id="job_id">
            <option value="">Tüm Pozisyonlar</option>
            <?php
            $jobs = $conn->query("SELECT id, title FROM job_posts");
            while($job = $jobs->fetch_assoc()): ?>
                <option value="<?= $job['id'] ?>" <?= isset($_GET['job_id']) && $_GET['job_id'] == $job['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($job['title']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Filtrele</button>
    </form>

    <table>
        <tr>
            <th>Ad Soyad</th>
            <th>Email</th>
            <th>Eğitim</th>
            <th>Doğum Yılı</th>
            <th>Deneyim (Yıl)</th>
            <th>Yabancı Dil</th>
            <th>Başvurulan Pozisyon</th>
            <th>CV</th>
            <th>Tarih</th>
        </tr>
        <?php
        $where = '';
        if (!empty($_GET['job_id'])) {
            $job_id = (int)$_GET['job_id'];
            $where = "WHERE applications.job_id = $job_id";
        }

        $sql = "SELECT applications.*, job_posts.title AS job_title 
                FROM applications 
                LEFT JOIN job_posts ON applications.job_id = job_posts.id 
                $where
                ORDER BY applications.applied_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0):
            while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['education_level']) ?></td>
                    <td><?= htmlspecialchars($row['birth_year']) ?></td>
                    <td><?= htmlspecialchars($row['experience_years']) ?></td>
                    <td><?= htmlspecialchars($row['language_level']) ?></td>
                    <td><?= htmlspecialchars($row['job_title'] ?? 'Belirtilmemiş') ?></td>
                    <td><a href="<?= '/' . ltrim($row['cv_path'], '/') ?>" target="_blank">Görüntüle</a></td>
                    <td><?= $row['applied_at'] ?></td>
                </tr>
            <?php endwhile;
        else: ?>
            <tr>
                <td colspan="9">Seçilen pozisyona ait başvuru bulunamadı.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
