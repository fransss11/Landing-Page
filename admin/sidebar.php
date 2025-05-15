<?php
// Tentukan halaman saat ini berdasarkan nama file
$current_page = basename($_SERVER['PHP_SELF']);
// Ambil data logo dari tabel info
$info_result = mysqli_query($con, "SELECT * FROM info WHERE id_info='1'");
if (!$info_result) {
    die("Error fetching info: " . mysqli_error($con));
}
$info_row = mysqli_fetch_array($info_result);
// Gunakan path absolut untuk logo, sehingga selalu terhubung dengan benar di semua halaman
$logo = (!empty($info_row['logo']))
    ? "images/logo/" . $info_row['logo']
    : "/img/Logo LMM (Persigi Panjang Putih Tanpa Alamat).png";
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <div class="sidebar" style="font-size: 15px;">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div>
                <a target="_blank" href="../index.php" class="navbar-brand p-0">
                    <img src="<?php echo $logo; ?>" alt="Logo" class="logo" style="width: 217px; height: 60px;"><br><br>
                </a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                        <i class="fas fa-home nav-icon"></i>
                        <p>Beranda</p>
                    </a>
                </li>
                <!-- Settings -->
                <li class="nav-item">
                    <a href="settings.php" class="nav-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">
                        <i class="fas fa-tools nav-icon"></i>
                        <p>Pengaturan</p>
                    </a>
                </li>
                <!-- Portofolio Section -->
                <li class="nav-item">
                    <a href="add-portofolio.php" class="nav-link <?php echo ($current_page == 'add-portofolio.php') ? 'active' : ''; ?>">
                        <i class="fas fa-briefcase nav-icon"></i>
                        <p>Lihat Portofolio</p>
                    </a>
                </li>
                <!-- Jam Kerja Section -->
                <li class="nav-item">
                    <a href="jam_kerja.php" class="nav-link <?php echo ($current_page == 'jam_kerja.php') ? 'active' : ''; ?>">
                        <i class="fas fa-clock nav-icon"></i>
                        <p>Lihat Jam Kerja</p>
                    </a>
                </li>
                <!-- About Section -->
                <li class="nav-header">Tentang Kami</li>
                <li class="nav-item">
                    <a href="add-about.php" class="nav-link <?php echo ($current_page == 'add-about.php') ? 'active' : ''; ?>">
                        <i class="fas fa-info-circle nav-icon"></i>
                        <p>Lihat Tentang Kami</p>
                    </a>
                </li>
                <!-- Service Section -->
                <li class="nav-header">Layanan</li>
                <li class="nav-item">
                    <a href="add-services.php" class="nav-link <?php echo ($current_page == 'add-services.php') ? 'active' : ''; ?>">
                        <i class="fas fa-bars nav-icon"></i>
                        <p>Tambah Layanan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view-services.php" class="nav-link <?php echo ($current_page == 'view-services.php') ? 'active' : ''; ?>">
                        <i class="fas fa-bars nav-icon"></i>
                        <p>Lihat Layanan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="add-sub_layanan.php" class="nav-link <?php echo ($current_page == 'add-sub_layanan.php') ? 'active' : ''; ?>">
                        <i class="fa fa-chart-bar nav-icon"></i>
                        <p>Tambah Sub Layanan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view-sub_layanan.php" class="nav-link <?php echo ($current_page == 'view-sub_layanan.php') ? 'active' : ''; ?>">
                        <i class="fa fa-chart-bar nav-icon"></i>
                        <p>Lihat Sub Layanan</p>
                    </a>
                </li>
                <!-- Kegiatan Section -->
                <li class="nav-header">Kegiatan</li>
                <li class="nav-item">
                    <a href="add-kegiatan.php" class="nav-link <?php echo ($current_page == 'add-kegiatan.php') ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-alt nav-icon"></i>
                        <p>Tambah Kegiatan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view-kegiatan.php" class="nav-link <?php echo ($current_page == 'view-kegiatan.php') ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-alt nav-icon"></i>
                        <p>Lihat Kegiatan</p>
                    </a>
                </li>
                <!-- Blog Section -->
                <li class="nav-header">Berita</li>
                <li class="nav-item">
                    <a href="add-category.php" class="nav-link <?php echo ($current_page == 'add-category.php') ? 'active' : ''; ?>">
                        <i class="fas fa-newspaper nav-icon"></i>
                        <p>Tambah Kategori Berita</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="add-blog.php" class="nav-link <?php echo ($current_page == 'add-blog.php') ? 'active' : ''; ?>">
                        <i class="fas fa-newspaper nav-icon"></i>
                        <p>Tambah Berita</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view-blog.php" class="nav-link <?php echo ($current_page == 'view-blog.php') ? 'active' : ''; ?>">
                        <i class="fas fa-newspaper nav-icon"></i>
                        <p>Lihat Berita</p>
                    </a>
                </li>
                <!-- Testimonials Section -->
                <li class="nav-header">Testimoni</li>
                <li class="nav-item">
                    <a href="add-testimonials.php" class="nav-link <?php echo ($current_page == 'add-testimonials.php') ? 'active' : ''; ?>">
                        <i class="fas fa-comments nav-icon"></i>
                        <p>Tambah Testimoni</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view-testimonials.php" class="nav-link <?php echo ($current_page == 'view-testimonials.php') ? 'active' : ''; ?>">
                        <i class="fas fa-comments nav-icon"></i>
                        <p>Lihat Testimoni</p>
                    </a>
                </li>
                <!-- Partnership Section -->
                <li class="nav-header">Klien</li>
                <li class="nav-item">
                    <a href="add-partner.php" class="nav-link <?php echo ($current_page == 'add-partner.php') ? 'active' : ''; ?>">
                        <i class="fas fa-handshake nav-icon"></i>
                        <p>Tambah Klien</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view-partner.php" class="nav-link <?php echo ($current_page == 'view-partner.php') ? 'active' : ''; ?>">
                        <i class="fas fa-handshake nav-icon"></i>
                        <p>Lihat Klien</p>
                    </a>
                </li>
                <!-- Gallery Section -->
                <li class="nav-header">Galeri</li>
                <li class="nav-item">
                    <a href="add-kat_gal.php" class="nav-link <?php echo ($current_page == 'add-kat_gal.php') ? 'active' : ''; ?>">
                        <i class="fas fa-images nav-icon"></i>
                        <p>Tambah Kategori Galeri</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="add-gallery.php" class="nav-link <?php echo ($current_page == 'add-gallery.php') ? 'active' : ''; ?>">
                        <i class="fas fa-images nav-icon"></i>
                        <p>Tambah Galeri</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view-gallery.php" class="nav-link <?php echo ($current_page == 'view-gallery.php') ? 'active' : ''; ?>">
                        <i class="fas fa-images nav-icon"></i>
                        <p>Lihat Galeri</p>
                    </a>
                </li>
                <!-- Team Section -->
                <li class="nav-header">Tim</li>
                <li class="nav-item">
                    <a href="add-teams.php" class="nav-link <?php echo ($current_page == 'add-teams.php') ? 'active' : ''; ?>">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Tambah Tim</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view-teams.php" class="nav-link <?php echo ($current_page == 'view-teams.php') ? 'active' : ''; ?>">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Lihat Tim</p>
                    </a>
                </li>
                <!-- Team Section -->
                <li class="nav-header">Projek</li>
                <li class="nav-item">
                    <a href="add-projek.php" class="nav-link <?php echo ($current_page == 'add-projek.php') ? 'active' : ''; ?>">
                        <i class="fas fa-project-diagram nav-icon"></i>
                        <p>Tambah Projek</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view-projek.php" class="nav-link <?php echo ($current_page == 'view-projek.php') ? 'active' : ''; ?>">
                        <i class="fas fa-project-diagram nav-icon"></i>
                        <p>Lihat Projek</p>
                    </a>
                </li>
                <li class="nav-header">Artikel</li>
                <li class="nav-item">
                    <a href="add-artikel.php" class="nav-link <?php echo ($current_page == 'add-artikel.php') ? 'active' : ''; ?>">
                        <i class="fas fa-newspaper nav-icon"></i>
                        <p>Tambah Artikel</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view-artikel.php" class="nav-link <?php echo ($current_page == 'view-artikel.php') ? 'active' : ''; ?>">
                        <i class="fas fa-newspaper nav-icon"></i>
                        <p>Lihat Artikel</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<!-- Tambahkan CSS agar tampilan lebih rapi -->
<style>
    .nav-sidebar .nav-item .nav-link {
        display: flex;
        align-items: center;
    }
    .nav-sidebar .nav-item .nav-icon {
        margin-right: 8px;
    }
    .nav-header {
        padding: 10px;
        font-size: 14px;
        font-weight: bold;
        color: white;
        text-transform: uppercase;
    }
    .user-panel .navbar-brand img {
        transition: transform 0.3s ease;
    }

    .user-panel .navbar-brand img:hover {
        transform: scale(.9);
    }
</style>
<!-- Pastikan FontAwesome dimuat -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">