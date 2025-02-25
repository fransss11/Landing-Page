<?php
session_start();
// Pastikan pengguna sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Sertakan CSS Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></h1>
    <p>Ini adalah halaman admin. Session login tetap aktif sehingga jika halaman di-refresh, pengguna tidak perlu login ulang.</p>
    <!-- Tombol logout untuk keluar -->
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>
<!-- Sertakan JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
