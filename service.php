<?php
include 'database.php';
// Fetch data from the 'services' table
$sql = "SELECT * FROM services ORDER BY id DESC";
$result = $conn->query($sql);
$services = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}
// Fetch data from the 'testimonials' table
$sql = "SELECT title, designation, descrip,img, date FROM testimonials";
$result = $conn->query($sql);
$testimonials = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}
$sql = "SELECT whatsapp FROM social";
$result = $conn->query($sql);
$social = $result->fetch_assoc();
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
    <link href="libr/animate/animate.min.css" rel="stylesheet">
    <link href="libr/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- AOS Library -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <style>
        .hidden {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        .visible {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="bckg">
        <!-- Spinner Start -->
        <?php include 'includes/spinner.php'; ?>
        <!-- Spinner End -->
        <!-- Topbar Start -->
        <?php include 'includes/topbar.php'; ?>
        <!-- Topbar End -->
        <!-- Navbar & Hero Start -->
        <?php include 'includes/navbar.php'; ?>
        <!-- Navbar End -->
        <!-- Header Start -->
        <?php
        $pageTitle = "Layanan";
        include 'includes/header.php';
        ?>
        <!-- Header End -->
        <!-- Services Start -->
        <div class="container-fluid service bg-light py-5">
            <div class="container py-5">
                <div class="section-title mb-5">
                    <div class="sub-style">
                        <h1 class="sub-title px-3 mb-0">Layanan</h1>
                    </div>
                </div>
                <div class="row g-4 justify-content-center" id="services-container">
                    <?php foreach ($services as $service) : ?>
                        <div class="col-md-6 col-lg-4 col-xl-3" data-aos="zoom-in" data-aos-delay="300">
                            <div class="service-item rounded">
                                <div class="service-img rounded-top">
                                    <?php if (!empty($service['img'])): ?>
                                        <img src="admin/images/services/<?php echo $service['img']; ?>" 
                                            class="img-fluid rounded-top w-100" 
                                            alt="<?php echo $service['title']; ?>" loading="lazy">
                                    <?php else: ?>
                                        <img src="img/default-service-icon.png" 
                                            class="img-fluid rounded-top w-100" 
                                            alt="Default Icon" loading="lazy">
                                    <?php endif; ?>
                                </div>
                                <div class="service-content rounded-bottom bg-light p-4 d-flex flex-column">
                                    <h5 class="mb-4"><?php echo $service['title']; ?></h5>
                                    <p class="mb-4 short-description">
                                        <?php 
                                        $short = strip_tags($service['descrip']);
                                        $short = str_replace('&nbsp;', ' ', $short);
                                        if (strlen($short) > 200) {
                                            $shortCut = substr($short, 0, 200);
                                            $short = substr($shortCut, 0, strrpos($shortCut, ' ')) . '...';
                                        }
                                        echo htmlspecialchars($short);
                                        ?>
                                    </p>
                                    <div class="mt-auto text-center">
                                        <ul class="price-list mb-4">
                                            <li class="d-flex justify-content-between">
                                                <small>
                                                <span>Harga:</span>
                                                <span class="text-primary font-weight-bold">Rp <?php echo number_format($service['price'], 2, ',', '.'); ?></span>
                                                </small>
                                            </li>
                                        </ul>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="detail_service.php?id=<?php echo $service['id']; ?>" class="btn btn-primary rounded-pill text-white py-2 px-4">Detail</a>
                                            <?php if (!empty($social['whatsapp'])): ?>
                                                <a href="https://wa.me/<?php echo $social['whatsapp']; ?>" class="btn btn-primary rounded-pill text-white py-2 px-4">Hubungi Kami</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="col-12 text-center"></div>
                </div>
            </div>
        </div>
        <!-- Services End -->
        <!-- Testimonial Start -->
        <section id="testimoni" class="py-5 hidden" data-aos="fade-up">
            <div class="container-fluid testimonial py-5" data-aos="zoom-in-down" data-aos-delay="100">
                <div class="container py-5">
                    <div class="section-title mb-5">
                        <div class="sub-style">
                            <h1 class="sub-title text-white px-3 mb-0">Testimoni</h1>
                        </div>
                        <h1 class="display-3 mb-4">Silahkan Lihat dan Berikan Testimoni Anda</h1>
                    </div>
                    <div class="testimonial-carousel owl-carousel" data-aos="flip-left">
                        <?php foreach ($testimonials as $testimonial) : ?>
                            <div class="testimonial-item">
                                <div class="testimonial-inner p-5">
                                    <div class="testimonial-inner-img mb-4">
                                        <img src="admin/images/testimonial/<?php echo $testimonial['img']; ?>" class="img-fluid rounded-circle" alt="">
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
        </section>
        <!-- Testimonial End -->
        <!-- Footer Start -->
        <?php include 'includes/footer.php'; ?>
        <!-- Footer End -->
        <!-- Copyright Start -->
        <?php include 'includes/copyright.php'; ?>
        <!-- Copyright End -->
        <!-- Back to Top -->
        <?php include 'includes/back_to_top.php'; ?>
        <!-- Back to Top End -->
    </div>
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="libr/wow/wow.min.js"></script>
    <script src="libr/easing/easing.min.js"></script>
    <script src="libr/waypoints/waypoints.min.js"></script>
    <script src="libr/owlcarousel/owl.carousel.min.js"></script>
    <!-- AOS Library Script -->
     <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script>
    // Inisialisasi AOS
    AOS.init({
    });
    // Fungsi untuk menampilkan elemen saat di-scroll
    function revealOnScroll() {
        var reveals = document.querySelectorAll('.hidden, .visible');
        var windowHeight = window.innerHeight;
        var elementVisible = 150;
        for (var i = 0; i < reveals.length; i++) {
            var elementTop = reveals[i].getBoundingClientRect().top;
            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add('visible');
                reveals[i].classList.remove('hidden');
            } else {
                reveals[i].classList.remove('visible');
                reveals[i].classList.add('hidden');
            }
        }
        // Jika di-scroll ke paling atas, sembunyikan semua elemen
        if (window.scrollY === 0) {
            for (var i = 0; i < reveals.length; i++) {
                reveals[i].classList.remove('visible');
                reveals[i].classList.add('hidden');
            }
        }
    }
    window.addEventListener('scroll', revealOnScroll);
    window.addEventListener('load', revealOnScroll);
    </script>
</body>
</html>