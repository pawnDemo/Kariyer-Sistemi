<?php
$conn = new mysqli("localhost", "root", "", "kariyer");
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
?>
