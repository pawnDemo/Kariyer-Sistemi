<?php
include './config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $education_level = $_POST['education_level'];
    $birth_year = $_POST['birth_year'];
    $experience_years = $_POST['experience_years'];
    $language_level = $_POST['language_level'];
    $job_id = $_POST['job_id'];

    $cv_name = $_FILES['cv']['name'];
    $cv_tmp = $_FILES['cv']['tmp_name'];
    $cv_path = "./assets/uploads/" . time() . "_" . basename($cv_name);

    if (move_uploaded_file($cv_tmp, $cv_path)) {
        $stmt = $conn->prepare("INSERT INTO applications (full_name, email, education_level, birth_year, experience_years, language_level, job_id, cv_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiiiss", $full_name, $email, $education_level, $birth_year, $experience_years, $language_level, $job_id, $cv_path);

        if ($stmt->execute()) {
            header("refresh:5;url=index.php");
            echo "
                <html><head><link rel='stylesheet' href='../assets/css/style.css'></head><body>
                <div class='container'>
                    <h2>Başvuru Tamamlandı</h2>
                    <p>Teşekkür ederiz! Başvurunuz başarıyla alındı.</p>
                    <p>5 saniye içinde anasayfaya yönlendirileceksiniz...</p>
                </div></body></html>
            ";
            exit;
        } else {
            echo "Veritabanına kayıt sırasında hata oluştu: " . $stmt->error;
        }
    } else {
        echo "CV dosyası yüklenemedi.";
    }
} else {
    header("Location: index.php");
    exit;
}
?>
