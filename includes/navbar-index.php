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
$logo = isset($info_row['logo']) && !empty($info_row['logo'])
    ? "admin/images/logo/" . $info_row['logo']
    : "img/Logo LMM (Persigi Panjang Tanpa Alamat).png";

// Ambil data gambar dari tabel slider berdasarkan urutan yang ditentukan
$activity_imgs = [];
$slider_result = mysqli_query($conn, "SELECT * FROM slider ORDER BY urutan ASC");
if (!$slider_result) {
    // Jika query error, tampilkan pesan error di konsol
    echo "<!-- Error fetching slider: " . mysqli_error($conn) . " -->";
} else {
    // Ambil data gambar dari hasil query
    while ($slider_row = mysqli_fetch_assoc($slider_result)) {
        $activity_imgs[] = 'admin/gambar/kegiatan/' . $slider_row['gambar_beranda'];
    }
}

// Fallback jika tidak ada gambar di database
if (empty($activity_imgs)) {
    $activity_imgs[] = 'admin/gambar/kegiatan/default_activity.png';
}
?>
<!-- NAVBAR -->
<div class="container-fluid position-relative p-0">
    <nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-white px-4 px-lg-5 py-3 py-lg-0">
        <a href="index.php" class="navbar-brand p-0">
            <img src="<?= $logo ?>" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0">
            <a href="index.php"      class="nav-item nav-link <?= getCurrentPage()=='index.php'?'active':'' ?>">Beranda</a>
            <a href="about.php"      class="nav-item nav-link <?= getCurrentPage()=='about.php'?'active':'' ?>">Tentang Kami</a>
            <a href="service.php"    class="nav-item nav-link <?= getCurrentPage()=='service.php'?'active':'' ?>">Layanan</a>
            <a href="portofolio.php" class="nav-item nav-link <?= getCurrentPage()=='portofolio.php'?'active':'' ?>">Portofolio</a>
            <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle <?= in_array(getCurrentPage(),['klien.php','team.php','projek.php','testimoni.php','artikel.php'])?'active':'' ?>"
                data-bs-toggle="dropdown">Informasi</a>
            <div class="dropdown-menu m-0">
                <a href="klien.php"    class="dropdown-item <?= getCurrentPage()=='klien.php'?'active':'' ?>">Klien Kami</a>
                <a href="team.php"     class="dropdown-item <?= getCurrentPage()=='team.php'?'active':'' ?>">Tim Kami</a>
                <a href="projek.php"   class="dropdown-item <?= getCurrentPage()=='projek.php'?'active':'' ?>">Projek Kami</a>
                <a href="testimoni.php"class="dropdown-item <?= getCurrentPage()=='testimoni.php'?'active':'' ?>">Testimoni</a>
                <a href="artikel.php"  class="dropdown-item <?= getCurrentPage()=='artikel.php'?'active':'' ?>">Artikel</a>
            </div>
            </div>
            <a href="galery.php" class="nav-item nav-link <?= getCurrentPage()=='galery.php'?'active':'' ?>">Galeri</a>
            <a href="berita.php" class="nav-item nav-link <?= getCurrentPage()=='berita.php'?'active':'' ?>">Berita</a>
        </div>
        <a href="contact.php" class="contact-button <?php echo getCurrentPage() == 'contact.php' ? 'active' : ''; ?>">
            <i class="fas fa-paper-plane"></i> Kontak
        </a>
        </div>
    </nav>
</div>
<!-- NAVBAR END -->
<div id="activityCarousel"
     class="carousel slide"
     data-bs-ride="carousel"
     data-bs-interval="3000"
     data-bs-pause="false"
     data-bs-wrap="true"
     style="color:white;">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <?php foreach ($activity_imgs as $i => $img): ?>
        <button type="button"
                data-bs-target="#activityCarousel"
                data-bs-slide-to="<?= $i ?>"
                class="<?= $i===0?'active':'' ?>"
                aria-current="<?= $i===0?'true':'false' ?>">
        </button>
        <?php endforeach; ?>
    </div>
    <!-- Slides -->
    <div class="carousel-inner">
        <?php foreach ($activity_imgs as $i => $img): ?>
        <div class="carousel-item <?= $i===0?'active':'' ?>">
            <img src="<?= $img ?>"
                 alt="Gambar Beranda <?= $i+1 ?>"
                 class="d-block w-100">
        </div>
        <?php endforeach; ?>
    </div>
    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#activityCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#activityCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<style>
#activityCarousel .carousel-item img {
    object-fit: contain; /* Ensure the entire image is visible */
    width: 100%;
    height: auto;
    max-height: auto; /* Limit height for better visibility */
}

/* Margin adjustments for specific screen widths */
@media (min-width: 991.9px) and (max-width: 1172px) {
    #activityCarousel .carousel-item img {
        margin-top: 119px;
    }
}

@media (min-width: 1158px) {
    /* Ensure carousel images are not cropped on tablet and mobile */
    #activityCarousel .carousel-item img {
        margin-top: 95px;
    }
}
@media (max-width: 991.8px) {
    /* Ensure carousel images are not cropped on tablet and mobile */
    #activityCarousel .carousel-item img {
        margin-top: 0px;
    }
}
/* Adjustments for smaller screens */
@media (max-width: 768px) {
    #activityCarousel .carousel-item img {
        /* max-height: 50vh; Reduce height for tablets */
        margin-top: auto;
    }
}

@media (max-width: 576px) {
    #activityCarousel .carousel-item img {
        /* max-height: 26vh; Further reduce height for mobile */
        margin-top: auto;
    }
}
</style>

<div class="curved-divider"></div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const overlay = document.querySelector('.hero .overlay');
    overlay.classList.add("active");

    let lastScrollTop = 0;
    const navbar = document.getElementById('navbar');
    const scrollThreshold = 200;

    navbar.style.transition = 'top 0.3s';

    window.addEventListener('scroll', function () {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
            navbar.style.top = '-100px';
        } else if (scrollTop < lastScrollTop) {
            navbar.style.top = '0';
        }
        lastScrollTop = scrollTop;
    });
    // Pastikan carousel terus cycling
    const carouselEl = document.querySelector('#activityCarousel');
    const carousel = new bootstrap.Carousel(carouselEl, {
        interval: 3000,
        pause: false,
        wrap: true
    });
});
</script>