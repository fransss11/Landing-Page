<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil input dari form dan lakukan escaping
    $ad_name = $conn->real_escape_string($_POST['ad_name']);
    $ad_email = $conn->real_escape_string($_POST['ad_email']);
    $ad_password = $conn->real_escape_string($_POST['ad_password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    // Validasi agar password dan konfirmasi password sama
    if ($ad_password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak sama.";
    } else {
        // Periksa apakah email sudah terdaftar
        $sql_check = "SELECT * FROM admin WHERE ad_email='$ad_email' LIMIT 1";
        $result_check = $conn->query($sql_check);
        if ($result_check && $result_check->num_rows > 0) {
            $error = "Email sudah terdaftar. Silahkan gunakan email lain.";
        } else {
            // Hash password untuk keamanan
            $hashed_password = password_hash($ad_password, PASSWORD_DEFAULT);
            // Insert data admin baru ke tabel admin
            $sql_insert = "INSERT INTO admin (ad_name, ad_email, ad_password) VALUES ('$ad_name', '$ad_email', '$hashed_password')";
            if ($conn->query($sql_insert) === TRUE) {
                $message = "Admin baru berhasil didaftarkan.";
            } else {
                $error = "Terjadi kesalahan: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Admin Baru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Sertakan CSS Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Daftar Admin Baru</h2>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <?php if (isset($message)) { echo "<div class='alert alert-success'>$message</div>"; } ?>
    <form action="" method="post">
        <div class="mb-3">
            <label for="ad_name" class="form-label">Nama Admin</label>
            <input type="text" name="ad_name" id="ad_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="ad_email" class="form-label">Email Admin</label>
            <input type="email" name="ad_email" id="ad_email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="ad_password" class="form-label">Password</label>
            <input type="password" name="ad_password" id="ad_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Konfirmasi Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
        <a href="login.php" class="btn btn-success btn-lg">Login</a>
        <button type="submit" class="btn btn-success btn-lg">Daftar</button>
    </form>
</div>

<!-- Sertakan JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
