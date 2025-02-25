<?php
include 'database.php';

// Fetch data from the 'klien' table
$sql = "SELECT klien, gambar FROM klien";
$result = $conn->query($sql);

$clients = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}

// Fetch data from the 'services' table
$sql = "SELECT id, title, short, descrip, img, url, date FROM services";
$result = $conn->query($sql);

$services = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

// Fetch data from the 'about' table
$sql = "SELECT * FROM about ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$about = $result->fetch_assoc();

// Fetch data from the 'teams' table
$sql = "SELECT title, designation, descrip, img, facebook, twitter, instagram, linkedin, whatsapp FROM teams";
$result = $conn->query($sql);

$teamList = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $teamList[] = $row;
    }
}

// Fetch data from the 'testimonials' table
$sql = "SELECT title, designation, descrip, img, date FROM testimonials";
$result = $conn->query($sql);

$testimonials = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}

// Fetch data from the 'blog' table
$sql = "SELECT id, title, category, descrip, img, date, url FROM blog";
$result = $conn->query($sql);

$beritaList = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $beritaList[] = $row;
    }
}

$conn->close();

function formatTanggalIndonesia($tanggal) {
    $bulanIndo = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    $hariIndo = [
        "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"
    ];

    $dateObj = strtotime($tanggal);
    $hari = $hariIndo[date('w', $dateObj)];
    $tanggalNum = date('j', $dateObj);
    $bulan = $bulanIndo[date('n', $dateObj) - 1];
    $tahun = date('Y', $dateObj);

    return "$hari, $tanggalNum $bulan $tahun";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Lisa Mitra Mandiri</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Spinner Start -->
    <?php include 'includes/spinner.php'; ?>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <?php include 'includes/topbar.php'; ?>
    <!-- Topbar End -->


    <!-- Navbar & Hero Start -->
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
                    <a href="index.php" class="nav-item nav-link active">Beranda</a>
                    <a href="about.php" class="nav-item nav-link">Tentang Kami</a>
                    <a href="service.php" class="nav-item nav-link">Layanan</a>
                    <a href="portofolio.php" class="nav-item nav-link">Portofolio</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Informasi</a>
                        <div class="dropdown-menu m-0">
                            <a href="klien.php" class="dropdown-item">Klien Kami</a>
                            <a href="team.php" class="dropdown-item">Tim Kami</a>
                        </div>
                    </div>
                    <a href="galery.php" class="nav-item nav-link">Galeri</a>
                    <a href="berita.php" class="nav-item nav-link">Berita</a>
                </div>
                <a href="contact.php" class="contact-button">
                    <i class="fas fa-paper-plane"></i> Kontak
                </a>
            </div>
        </nav>


        <!-- Carousel Start -->
        <!-- Header Carousel Start -->
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
                    <path fill="white" fill-opacity="1" d="M0,192L60,192C120,192,240,192,360,208C480,224,600,256,720,240C840,224,960,160,1080,128C1200,96,1320,96,1380,96L1440,96L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z">
                    </path>
                </svg>
            </div>
        </div>
        <!-- Carousel End -->

    </div>
    <!-- Navbar & Hero End -->

    <div class="curved-divider"></div>

    <!-- Client Reviews Section -->
    <div class="container-fluid py-5 client-reviews-section">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center">Kerjasama</h2>
                <div class="client-reviews owl-carousel owl-theme">
                    <?php foreach ($clients as $client) : ?>
                        <div class="single-review">
                            <div class="reviewer media mt-3">
                                <div class="reviewer-thumb">
                                    <div class="reviewer-meta media-body align-self-center ml-4">
                                        <h5 class="reviewer-name color-primary mb-2"><?php echo $client['klien']; ?></h5>
                                    </div>
                                    <img class="avatar-lg radius-200" src="img/<?php echo $client['gambar']; ?>" alt="img">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Client Reviews Section End -->

    <!-- Services Start -->
    <div class="container-fluid service py-5">
        <div class="container py-5">
            <div class="section-title mb-5 wow fadeInUp" data-wow-delay="0.2s">
                <div class="sub-style">
                    <h4 class="sub-title px-3 mb-0">Layanan</h4>
                </div>
            </div>
            <div class="row g-4 justify-content-center" id="services-container">
                <?php foreach ($services as $service) : ?>
                    <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="service-item rounded">
                            <div class="service-img rounded-top">
                                <img src="img/<?php echo $service['img']; ?>" class="img-fluid rounded-top w-100" alt="<?php echo $service['title']; ?>">
                            </div>
                            <div class="service-content rounded-bottom bg-light p-4 d-flex flex-column">
                                <h5 class="mb-4"><?php echo $service['title']; ?></h5>
                                <p class="mb-4"><?php echo $service['descrip']; ?></p>

                                <!-- Wadah untuk tanggal + tombol -->
                                <div class="mt-auto text-center">
                                    <p class="text-muted mb-2">
                                        <small><?php echo formatTanggalIndonesia($service['date']); ?></small>
                                    </p>
                                    <a href="<?php echo $service['url']; ?>" class="btn btn-primary rounded-pill text-white py-2 px-4">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.2s">
                    <a class="btn btn-primary rounded-pill text-white py-3 px-5" href="service.php">Layanan Lainnya</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Services End -->


    <!-- About Start -->
    <div class="container-fluid about bg-light py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="about-img pb-5 ps-5">
                        <img src="img/<?php echo $about['img']; ?>" class="img-fluid rounded w-100" style="object-fit: cover;" alt="Image">
                    </div>
                </div>
                <div class="col-lg-7 wow fadeInRight" data-wow-delay="0.4s">
                    <div class="section-title text-start mb-5">
                        <!-- <h4 class="sub-title pe-3 mb-0">About Us</h4> -->
                        <h4 class="display-3 mb-4"><?php echo $about['title']; ?></h4>
                        <p class="mb-4"><?php echo $about['descrip']; ?></p>
                        <!-- <a href="<?php echo $about['url']; ?>" class="btn btn-primary rounded-pill text-white py-3 px-5">Our Service</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Team Start -->
    <div class="container-fluid team py-5">
        <div class="container py-5">
            <div class="section-title mb-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="sub-style">
                    <h4 class="sub-title px-3 mb-0">Tim Kami</h4>
                </div>
                <!-- <h1 class="display-3 mb-4">Physiotherapy Services from Professional Therapist</h1>
                <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat deleniti amet at atque sequi quibusdam cumque itaque repudiandae temporibus, eius nam mollitia voluptas maxime veniam necessitatibus saepe in ab? Repellat!</p> -->
            </div>
            <div class="row g-4 justify-content-center">
                <?php foreach ($teamList as $team) : ?>
                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="team-item rounded">
                            <div class="team-img rounded-top h-100">
                                <img src="img/<?php echo $team['img']; ?>" class="img-fluid rounded-top w-100" alt="<?php echo $team['title']; ?>">
                                <div class="team-icon d-flex justify-content-center">
                                    <?php if (!empty($team['facebook'])) : ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="<?php echo $team['facebook']; ?>"><i class="fab fa-facebook-f"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($team['twitter'])) : ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="<?php echo $team['twitter']; ?>"><i class="fab fa-twitter"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($team['instagram'])) : ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="<?php echo $team['instagram']; ?>"><i class="fab fa-instagram"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($team['linkedin'])) : ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="<?php echo $team['linkedin']; ?>"><i class="fab fa-linkedin-in"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($team['whatsapp'])) : ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="https://wa.me/<?php echo $team['whatsapp']; ?>"><i class="fab fa-whatsapp"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="team-content text-center border border-primary border-top-0 rounded-bottom p-4">
                                <h5><?php echo $team['title']; ?></h5>
                                <p class="mb-0"><?php echo $team['designation']; ?></p>
                                <p class="mb-0" style="font-style: italic;"><?php echo $team['descrip']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Team End -->


    <!-- Testimonial Start -->
    <div class="container-fluid testimonial py-5 wow zoomInDown" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="section-title mb-5">
                <div class="sub-style">
                    <h4 class="sub-title text-white px-3 mb-0">Testimoni</h4>
                </div>
                <h1 class="display-3 mb-4">Silahkan Lihat dan Berikan Testimoni Anda</h1>
            </div>
            <div class="testimonial-carousel owl-carousel">
                <?php foreach ($testimonials as $testimonial) : ?>
                    <div class="testimonial-item">
                        <div class="testimonial-inner p-5">
                            <div class="testimonial-inner-img mb-4">
                                <img src="img/<?php echo $testimonial['img']; ?>" class="img-fluid rounded-circle" alt="">
                            </div>
                            <div class="text-center">
                                <h5 class="mb-2"><?php echo $testimonial['title']; ?></h5>
                                <p class="mb-2 text-white-50"><?php echo $testimonial['designation']; ?></p>
                                <p class="text-mutedd"><small><?php echo formatTanggalIndonesia($testimonial['date']); ?></small></p>
                            </div>
                            <p class="text-white fs-7"><?php echo $testimonial['descrip']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!-- Berikan Testimoni Start -->
            <div class="container-fluid py-5 text-center">
                <a href="testimoni.php" class="btn btn-primary rounded-pill text-white py-2 px-4">Berikan Testimoni</a>
            </div>
            <!-- Berikan Testimoni End -->
        </div>
    </div>
    <!-- Testimonial End -->

    <!-- Daftar Berita -->
    <div class="container py-5">
        <h4 class="text-center mb-4 fade-in" style="font-size: 300%;">Daftar Berita</h4>
        <div class="row g-4" id="berita-container">
            <?php foreach ($beritaList as $berita): ?>
                <div class="col-md-6 col-lg-4 d-flex align-items-stretch wow fadeInUp" data-wow-delay="0.2s">
                    <div class="card shadow-lg">
                        <img src="img/<?php echo $berita['img']; ?>" class="card-img-top" alt="<?php echo $berita['title']; ?>">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $berita['title']; ?></h4>
                            <p class="text-muted"><i class="fa fa-calendar-alt text-primary"></i> <?php echo formatTanggalIndonesia($berita['date']); ?></p>
                            <h5 class="card-category"><?php echo $berita['category']; ?></h5>
                            <p class="card-text"><?php echo $berita['descrip']; ?></p>
                            <a href="<?php echo $berita['url']; ?>" class="btn btn-primary">Lebih Banyak</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Script untuk Daftar Berita -->
    <!-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            let beritaContainer = document.getElementById("berita-container");
            const beritaList = <?php echo json_encode($beritaList); ?>;

            beritaList.forEach((berita, index) => {
                beritaContainer.innerHTML += `
                    <div class="col-md-6 col-lg-4 d-flex align-items-stretch wow fadeInUp" data-wow-delay="${index * 0.2}s">
                        <div class="card shadow-lg">
                            <img src="img/${berita.img}" class="card-img-top" alt="${berita.title}">
                            <div class="card-body">
                                <h4 class="card-title">${berita.title}</h4>
                                <p class="text-muted"><i class="fa fa-calendar-alt text-primary"></i> <?php echo formatTanggalIndonesia($berita['date']); ?></p>
                                <h5 class="card-category">${berita.category}</h5>
                                <p class="card-text">${berita.descrip}</p>
                                <a href="${berita.url}" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                `;
            });
        });
    </script> -->
    <!-- end berita -->


     <!-- Footer Start -->
     <?php include 'includes/footer.php'; ?>
     <!-- Footer End -->
        
    <!-- Copyright Start -->
    <?php include 'includes/copyright.php'; ?>
    <!-- Copyright End -->

    <!-- Back to Top -->
    <?php include 'includes/back_to_top.php'; ?>
    <!-- Back to Top End -->
        
            <!-- JavaScript Libraries -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="lib/wow/wow.min.js"></script>
            <script src="lib/easing/easing.min.js"></script>
            <script src="lib/waypoints/waypoints.min.js"></script>
            <script src="lib/owlcarousel/owl.carousel.min.js"></script>
            
            <script>
                $(document).ready(function(){
                    $(".client-reviews").owlCarousel({
                        loop: true,
                        margin: 30,
                        nav: false,
                        autoplay: true,
                        autoplayTimeout: 3000,
                        autoplaySpeed: 1000,
                        autoplayHoverPause: false,
                        items: 1,
                        responsive: {
                            0: { items: 1 },
                            600: { items: 2 },
                            1000: { items: 3 }
                        }
                    });
                });
            </script>

            <!-- Template Javascript -->
            <script src="js/main.js"></script>
        
    </body>

</html>