<?php
session_start();
include '../database.php';

// Proses login ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escape input untuk keamanan
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query untuk mengambil data admin berdasarkan username
    $sql = "SELECT * FROM admin WHERE ad_name='$username' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        // Verifikasi password menggunakan password_verify (pastikan password di database sudah di-hash)
        if (password_verify($password, $admin['ad_password'])) {
            // Set session login
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['ad_name'];
            header("Location: admin.php");
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Sertakan CSS Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Login Admin</h2>
            <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <form action="" method="post" autocomplete="off">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" required autocomplete="off">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <a href="daftar.php" class="btn btn-success btn-lg mt-3 d-block">Daftar</a>
            </form>
        </div>
    </div>
</div>
<!-- Sertakan JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>