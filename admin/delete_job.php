<?php
include '../includes/auth.php';
include '../config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("DELETE FROM job_posts WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: index.php?msg=deleted");
    exit();
} else {
    $stmt->close();
    header("Location: index.php?msg=error");
    exit();
}
?>
