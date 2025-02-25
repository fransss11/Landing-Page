<?php
function getCurrentPage() {
    return basename($_SERVER['PHP_SELF']);
}
?>

<div class="container-fluid position-relative p-0">
    <nav class="navbar navbar-expand-lg navbar-light bg-white px-4 px-lg-5 py-3 py-lg-0">
        <a href="index.php" class="navbar-brand p-0">
            <img src="img/Logo LMM (Persigi Panjang Tanpa Alamat).png" alt="Logo">
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