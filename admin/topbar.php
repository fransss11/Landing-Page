<?php
// Mengasumsikan Anda memiliki file koneksi database
include 'conn.php';

// Memeriksa apakah sesi sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pastikan session "ad_id" sudah ada, jika tidak redirect ke login
if (!isset($_SESSION['ad_id'])) {
    header("Location: login.php");
    exit;
}
$ad_id = (int) $_SESSION['ad_id']; // Casting ke integer untuk keamanan


// Mengambil data pengguna dari database
$query = "SELECT * FROM admin WHERE ad_id = $ad_id"; // Sesuaikan query jika diperlukan
$hasil = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($hasil);

// Menentukan path gambar profil
$profile_image = !empty($user['pict']) ? "images/admin/" . $user['pict'] : "images/admin/default.jpg"; // Jika gambar tidak ada, gunakan gambar default
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light d-flex justify-content-between align-items-center">
    <!-- Left navbar links -->
    <ul class="navbar-nav d-flex align-items-center">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href=""><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <span class="nav-link font-weight-bold">Selamat Datang di Admin Panel</span>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav d-flex align-items-center">
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
                <div class="image">
                    <!-- Menampilkan gambar profil sesuai database -->
                    <img src="<?= $profile_image ?>" class="img-circle elevation-2" alt="User Image" width="40" height="40">
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header"><?php echo $user['ad_name']; ?></span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> <?php echo $user['ad_email']; ?>
                </a>
                <a href="data_admin.php" class="dropdown-item">
                    <i class="fas fa-user-cog mr-2"></i> Data Admin
                </a>
                <a href="logout.php" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                </a>
            </div>
        </li>
    </ul>
</nav>
