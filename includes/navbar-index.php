<?php
function getCurrentPage() {
    return basename($_SERVER['PHP_SELF']);
}
include 'database.php';
$info_result = mysqli_query($conn, "SELECT * FROM info WHERE id_info='1'");
if (!$info_result) {
    die("Error fetching info: " . mysqli_error($conn));
}
$info_row = mysqli_fetch_array($info_result);
$logo = isset($info_row['logo']) && !empty($info_row['logo']) ? "admin/images/logo/" . $info_row['logo'] : "img/Logo LMM (Persigi Panjang Tanpa Alamat).png";
?>
<div class="container-fluid position-relative p-0">
    <nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-white px-4 px-lg-5 py-3 py-lg-0">
        <a href="index.php" class="navbar-brand p-0">
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
                        <a href="testimoni.php" class="dropdown-item <?php echo getCurrentPage() == 'testimoni.php' ? 'active' : ''; ?>">Testimoni</a>
                        <a href="artikel.php" class="dropdown-item <?php echo getCurrentPage() == 'artikel.php' ? 'active' : ''; ?>">Artikel</a>
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
    <div class="hero">
        <div class="overlay"></div>
        <div class="hero-content wow fadeInRight" data-wow-delay="0.5s">
            <h1 style="color: white; margin-top: 40%;">LISA MITRA MANDIRI (LMM)</h1>
            <p style="color: #a9a9a9;">PSYCHOLOGY CONSULTANT.</p>
            <div class="buttons">
                <a href="contact.php" class="btn">Kontak</a>
            </div>
        </div>
        <div class="wave-container">
            <svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
            <path fill="rgb(90, 34, 150)" fill-opacity="1" d="M0,192L60,192C120,192,240,192,360,208C480,224,600,256,720,240C840,224,960,160,1080,128C1200,96,1320,96,1380,96L1440,96L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
            </svg>
        </div>
    </div>
</div>
<div class="curved-divider"></div>
<style>
.hero {
    background: 
        url("admin/images/logo/502247371Logo LMM Black list White.png") no-repeat center center,
        radial-gradient(circle closest-side, rgb(153, 0, 255), rgba(128, 0, 255, 0.6));
    background-size: contain, cover;
    background-position: center 33%, center center;
    background-attachment: fixed;
}
.hero .overlay {
    position: absolute;
    inset: 0;
    background-color: rgba(18, 18, 18, 0.81);
    z-index: 1;
    transform: translateX(-100%);
    transition: transform 3s ease;
}
.hero .overlay.active {
    transform: translateX(0);
}
.wave-container {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    line-height: 0;
    z-index: 2;
}
.hero-content {
    position: relative;
    z-index: 3;
}
@media (max-width: 576px) {
  .hero {
    background-position: center 170px, center center !important;
    height: auto !important;
    min-height: auto !important;
    overflow: visible !important;
    padding: 2rem 1rem !important;
  }
  .hero-content h1 {
    font-size: 1.6rem !important;
    line-height: 2.4rem !important;
  }
  .hero-content p {
    font-size: 1rem !important;
  }
  .wave-container {
    margin-top: 1rem !important;
    width: 100% !important;
  }
}
@media (min-width: 992px) {
    .navbar .nav-item.dropdown:hover > .dropdown-menu {
        display: block;
    }
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const overlay = document.querySelector('.hero .overlay');
  overlay.classList.add("active");
  
  let lastScrollTop = 0;
  const navbar = document.getElementById('navbar');
  const scrollThreshold = 200; // Batas tinggi scroll sebelum navbar disembunyikan

  navbar.style.transition = 'top 0.3s';

  window.addEventListener('scroll', function () {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
          // Scroll ke bawah dan melewati batas - sembunyikan navbar
          navbar.style.top = '-100px';
      } else if (scrollTop < lastScrollTop) {
          // Scroll ke atas - tampilkan navbar
          navbar.style.top = '0';
      }
      lastScrollTop = scrollTop;
  });
});
</script>