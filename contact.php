<?php
include 'database.php';
// Fetch data from the 'info' table
$sql = "SELECT lokasi, gmail, maps_url FROM info ORDER BY id_info DESC LIMIT 1";
$result = $conn->query($sql);
$info = $result->fetch_assoc();
$maps_url = $info['maps_url'];
// Fetch data from the 'social_table'
$sql = "SELECT * FROM social ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$social = $result->fetch_assoc();
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
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
        $pageTitle = "Kontak";
        include 'includes/header.php';
        ?>
        <!-- Header End -->
        <!-- Contact Start -->
        <div class="container-fluid about team contact py-5">
            <div class="container py-5">
                <div class="section-title mb-5 text-center">
                    <div class="sub-style mb-4">
                        <h1 class="sub-title text-white px-3 mb-0">Kontak</h1>
                    </div>
                    <p class="mb-0 text-black-50">Jika ada yang mau ditanyakan, silahkan hubungi kami!</p>
                </div>
                <div class="row g-4 align-items-center">
                    <!-- Contact Info Section -->
                    <div class="col-lg-4 col-md-6 col-12" data-aos="fade-up" data-aos-delay="500" style="padding-top: 90px;">
                        <div class="bg-transparent rounded">
                            <div class="d-flex flex-column align-items-center text-center mb-4">
                                <a href="<?php echo $info['lokasi']; ?>" class="bg-white d-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px; border-radius: 50px;">
                                    <i class="fa fa-map-marker-alt fa-2x text-primary"></i>
                                </a>
                                <a href="<?php echo $info['lokasi']; ?>" class="mb-0 text-white"><h4 class="text-dark">Lokasi</h4></a>
                            </div>
                            <div class="d-flex flex-column align-items-center text-center">
                                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo urlencode($info['gmail']); ?>" class="bg-white d-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px; border-radius: 50px;">
                                    <i class="fa fa-envelope-open fa-2x text-primary"></i>
                                </a>
                                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo urlencode($info['gmail']); ?>" class="mb-0 text-white">
                                    <h4 class="text-dark">Email</h4>
                                </a>
                            </div>
                            <div class="d-flex flex-column align-items-center text-center mb-4">
                                <div class="bg-white d-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px; border-radius: 50px;">
                                    <i class="fa fa-phone-alt fa-2x text-primary"></i>
                                </div>
                                <h4 class="text-dark">Telepon</h4>
                                <p class="mb-0 text-white"><?php echo $social['phone']; ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- Map and Social Media Section -->
                    <div class="col-lg-8 col-md-6 col-12 text-center" data-aos="fade-left" data-aos-delay="500">
                        <div class="d-flex justify-content-center mb-4 flex-wrap">
                            <?php if (!empty($social['facebook'])): ?>
                                <a class="btn btn-lg-square btn-light rounded-circle mx-2 mb-2" href="<?php echo $social['facebook']; ?>"><i class="fab fa-facebook-f"></i></a>
                            <?php endif; ?>
                            <?php if (!empty($social['twitter'])): ?>
                                <a class="btn btn-lg-square btn-light rounded-circle mx-2 mb-2" href="<?php echo $social['twitter']; ?>"><i class="fab fa-twitter"></i></a>
                            <?php endif; ?>
                            <?php if (!empty($social['instagram'])): ?>
                                <a class="btn btn-lg-square btn-light rounded-circle mx-2 mb-2" href="<?php echo $social['instagram']; ?>"><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                            <?php if (!empty($social['linkedin'])): ?>
                                <a class="btn btn-lg-square btn-light rounded-circle mx-2 mb-2" href="<?php echo $social['linkedin']; ?>"><i class="fab fa-linkedin-in"></i></a>
                            <?php endif; ?>
                            <?php if (!empty($social['whatsapp'])): ?>
                                <a class="btn btn-lg-square btn-light rounded-circle mx-2 mb-2" href="https://wa.me/<?php echo $social['whatsapp']; ?>"><i class="fab fa-whatsapp"></i></a>
                            <?php endif; ?>
                        </div>
                        <div class="rounded h-100">
                            <iframe class="rounded w-100" 
                                style="height: 500px; border:0;" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade" 
                                src="<?php echo isset($maps_url) ? $maps_url : 'https://www.google.com/maps'; ?>" 
                                allowfullscreen="">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact End -->
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
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <!-- Inisialisasi WOW.js -->
    <script>
        new WOW().init();
    </script>
</body>
</html>