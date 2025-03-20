<?php
include 'database.php';
// Fetch data from the 'info' table
$sql = "SELECT lokasi, gmail, maps_url FROM info ORDER BY id_info DESC LIMIT 1";
$result = $conn->query($sql);
$info = $result->fetch_assoc();
$maps_url = $info['maps_url'];
// Fetch data from the 'social_table'
$sql = "SELECT facebook, twitter, instagram, linkedin, whatsapp FROM social ORDER BY id DESC LIMIT 1";
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
</head>
<body>
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
    <div class="container-fluid contact py-5">
        <div class="container py-5">
            <div class="section-title mb-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="sub-style mb-4">
                    <h4 class="sub-title text-white px-3 mb-0">Kontak</h4>
                </div>
                <p class="mb-0 text-black-50">Jika ada yang mau ditanyakan, silahkan hubungi kami!</p>
            </div>
            <div class="row g-4 align-items-center">
                <div class="col-lg-5 col-xl-5 contact-form wow fadeInLeft" data-wow-delay="0.1s">
                    <h2 class="display-5 text-white mb-2">Silahkan bertanya</h2>
                        <form action="send_email.php" method="post">
                            <div class="row g-3">
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-transparent border border-white" id="name" name="name" placeholder="Your Name" required>
                                        <label for="name">Nama Anda</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control bg-transparent border border-white" id="email" name="email" placeholder="Your Email" required>
                                        <label for="email">Email Anda</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-floating">
                                        <input type="phone" class="form-control bg-transparent border border-white" id="phone" name="phone" placeholder="Phone" required>
                                        <label for="phone">Telepon Anda</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-transparent border border-white" id="project" name="project" placeholder="Project" required>
                                        <label for="project">Projek Anda</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-transparent border border-white" id="subject" name="subject" placeholder="Subject" required>
                                        <label for="subject">Subject</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control bg-transparent border border-white" placeholder="Leave a message here" id="message" name="message" style="height: 160px" required></textarea>
                                        <label for="message">Pesan</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-light text-primary w-100 py-3" type="submit">Kirim Pesan</button>
                                </div>
                            </div>
                        </form>
                </div>
                <div class="col-lg-2 col-xl-2 wow fadeInUp" data-wow-delay="0.5s">
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
                            <div class="bg-white d-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px; border-radius: 50px;"><i class="fa fa-phone-alt fa-2x text-primary"></i></div>
                            <h4 class="text-dark">Telepon</h4>
                            <p class="mb-0 text-white">031 843 7854</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-xl-5 wow fadeInRight" data-wow-delay="0.3s">
                    <div class="d-flex justify-content-center mb-4">
                        <?php if (!empty($social['facebook'])): ?>
                            <a class="btn btn-lg-square btn-light rounded-circle mx-2" href="<?php echo $social['facebook']; ?>"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($social['twitter'])): ?>
                            <a class="btn btn-lg-square btn-light rounded-circle mx-2" href="<?php echo $social['twitter']; ?>"><i class="fab fa-twitter"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($social['instagram'])): ?>
                            <a class="btn btn-lg-square btn-light rounded-circle mx-2" href="<?php echo $social['instagram']; ?>"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($social['linkedin'])): ?>
                            <a class="btn btn-lg-square btn-light rounded-circle mx-2" href="<?php echo $social['linkedin']; ?>"><i class="fab fa-linkedin-in"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($social['whatsapp'])): ?>
                            <a class="btn btn-lg-square btn-light rounded-circle mx-2" href="https://wa.me/<?php echo $social['whatsapp']; ?>" class="btn btn-light btn-square border rounded-circle nav-fill me-0"><i class="fab fa-whatsapp"></i></a>
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
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <!-- Inisialisasi WOW.js -->
    <script>
        new WOW().init();
    </script>
</body>
</html>