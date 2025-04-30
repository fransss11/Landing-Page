<?php
function getCurrentPage() {
    return basename($_SERVER['PHP_SELF']);
}

// Koneksi ke database dan ambil data logo
include 'database.php';  // Pastikan koneksi database Anda sudah benar

// Ambil data logo dari tabel info
$info_result = mysqli_query($conn, "SELECT * FROM info WHERE id_info='1'");
if (!$info_result) {
    die("Error fetching info: " . mysqli_error($conn));
}
$info_row = mysqli_fetch_array($info_result);

// Tentukan path logo, jika logo tidak ada di database maka gunakan logo default
$logo = isset($info_row['logo']) && !empty($info_row['logo']) ? "admin/images/logo/" . $info_row['logo'] : "img/Logo LMM (Persigi Panjang Tanpa Alamat).png";
?>

<div class="container-fluid position-relative p-0">
    <nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-white px-4 px-lg-5 py-3 py-lg-0">
        <a href="index.php" class="navbar-brand p-0">
            <!-- Menggunakan logo yang diambil dari database -->
            <img src="<?php echo $logo; ?>" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="index.php" class="nav-item nav-link <?php echo getCurrentPage() == 'index.php' ? 'active' : ''; ?>">Beranda</a>
                <a href="about.php" class="nav-item nav-link <?php echo getCurrentPage() == 'about.php' ? 'active' : ''; ?>">Tentang Kami</a>
                <a href="service.php" class="nav-item nav-link <?php echo getCurrentPage() == 'service.php' ? 'active' : ''; ?>">Layanan</a>
                <a href="portofolio.php" class="nav-item nav-link <?php echo getCurrentPage() == 'portofolio.php' ? 'active' : ''; ?>">Portofolio</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle <?php echo in_array(getCurrentPage(), ['klien.php', 'team.php','projek.php']) ? 'active' : ''; ?>" data-bs-toggle="dropdown">Informasi</a>
                    <div class="dropdown-menu m-0">
                        <a href="klien.php" class="dropdown-item <?php echo getCurrentPage() == 'klien.php' ? 'active' : ''; ?>">Klien Kami</a>
                        <a href="team.php" class="dropdown-item <?php echo getCurrentPage() == 'team.php' ? 'active' : ''; ?>">Tim Kami</a>
                        <a href="projek.php" class="dropdown-item <?php echo getCurrentPage() == 'projek.php' ? 'active' : ''; ?>">Projek Kami</a>
                        <a href="testimoni.php" class="dropdown-item <?php echo getCurrentPage() == 'testimoni.php' ? 'active' : ''; ?>">Testimoni</a>
                    </div>
                </div>
                <a href="galery.php" class="nav-item nav-link <?php echo getCurrentPage() == 'galery.php' ? 'active' : ''; ?>">Galeri</a>
                <a href="berita.php" class="nav-item nav-link <?php echo getCurrentPage() == 'berita.php' ? 'active' : ''; ?>">Berita</a>
            </div>
            <a href="contact.php" class="contact-button <?php echo getCurrentPage() == 'contact.php' ? 'active' : ''; ?>">
                <i class="fas fa-paper-plane"></i> Kontak
            </a>
        </div>
    </nav>
</div>

<script>
    let lastScrollTop = 0;
    const navbar = document.getElementById('navbar');
    const scrollThreshold = 200; // Batas tinggi scroll sebelum navbar disembunyikan

    navbar.style.transition = 'top 0.3s';

    window.addEventListener('scroll', function () {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
            // Scroll ke bawah dan melewati batas - sembunyikan navbar
            navbar.style.top = '-100px';
        } else {
            // Scroll ke atas atau belum melewati batas - tampilkan navbar
            navbar.style.top = '0';
        }
        lastScrollTop = scrollTop;
    });
</script>
