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
    <nav class="navbar navbar-expand-lg navbar-light bg-white px-4 px-lg-5 py-3 py-lg-0">
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
                    <a href="#" class="nav-link dropdown-toggle <?php echo in_array(getCurrentPage(), ['klien.php', 'team.php']) ? 'active' : ''; ?>" data-bs-toggle="dropdown">Informasi</a>
                    <div class="dropdown-menu m-0">
                        <a href="klien.php" class="dropdown-item <?php echo getCurrentPage() == 'klien.php' ? 'active' : ''; ?>">Klien Kami</a>
                        <a href="team.php" class="dropdown-item <?php echo getCurrentPage() == 'team.php' ? 'active' : ''; ?>">Tim Kami</a>
                        <a href="projek.php" class="dropdown-item <?php echo getCurrentPage() == 'projek.php' ? 'active' : ''; ?>">Projek Kami</a>
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

    <!-- Carousel / Hero Section -->
    <div class="hero">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>We are digital agency & Marketing</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit nihil tenetur minus.</p>
            <div class="buttons">
                <a href="contact.php" class="btn">Kontak</a>
            </div>
        </div>
        <img src="img/LOGO LMM SEGITIGA(1).png" class="person" alt="Person Image">
        <!-- SVG untuk efek lengkungan -->
        <div class="wave-container">
            <svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                <path fill="white" fill-opacity="1" d="M0,192L60,192C120,192,240,192,360,208C480,224,600,256,720,240C840,224,960,160,1080,128C1200,96,1320,96,1380,96L1440,96L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
            </svg>
        </div>
    </div>
</div>
<div class="curved-divider"></div>

<!-- CSS untuk mobile (khusus override tampilan mobile, desktop tidak berubah) -->
<style>
@media (max-width: 576px) {
  /* Pastikan hero auto-height & tidak hidden */
  .hero {
    height: auto !important;
    min-height: auto !important;
    overflow: visible !important;
    padding: 2rem 1rem !important;
  }

  /* Kecilkan font agar tidak melebar */
  .hero-content h1 {
    font-size: 1.6rem !important;
    line-height: 2.4rem !important; /* agar tidak memotong huruf */
  }
  .hero-content p {
    font-size: 1rem !important;
  }

  /* Gambar di hero, tidak absolute */
  .person {
    position: static !important;
    max-width: 200px !important;
    margin: 1rem auto 0 !important;
    display: block !important;
  }

  /* Wave mengikuti alur dokumen */
  .wave-container {
    position: static !important;
    margin-top: 1rem !important;
    width: 100% !important;
  }
}

</style>
