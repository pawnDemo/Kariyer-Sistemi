<?php include './config/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Kariyer Başvuru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/css/style.css">	
</head>
<body>

<div class="container">
	<div class="logo">
      <img src="./assets/logo.png" alt="Logo" class="logo" />
    </div>
    <h2>İş Başvuru Formu</h2>	
    <form action="/thankyou.php" method="POST" enctype="multipart/form-data">
        <label>Ad Soyad:</label>
        <input type="text" name="full_name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Eğitim Durumu:</label>
        <select name="education_level" required>
            <option value="Önlisans">Önlisans</option>
            <option value="Lisans">Lisans</option>
            <option value="Yüksek Lisans">Yüksek Lisans</option>
        </select>

        <label>Doğum Yılı:</label>
        <input type="number" name="birth_year" min="1950" max="2023" required>

        <label>Deneyim Süresi (Yıl):</label>
        <input type="number" name="experience_years" min="0" max="50" required>

        <label>Yabancı Dil Seviyesi:</label>
        <input type="text" name="language_level" placeholder="Örn: 0-100 Arası" required>

        <label>İlgili Pozisyon:</label>
        <select name="job_id" required>
            <?php
            $result = $conn->query("SELECT * FROM job_posts ORDER BY created_at DESC");
			$result = $conn->query("SELECT * FROM job_posts WHERE is_active = 1 ORDER BY created_at DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
            }
            ?>
        </select>

        <label>CV Yükle (PDF/DOC):</label>
        <input type="file" name="cv" accept=".pdf,.doc,.docx" required>

        <button type="submit">Başvuruyu Gönder</button>
    </form>
</div>
</body>
</html>