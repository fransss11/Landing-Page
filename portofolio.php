<?php
include 'database.php';
// Fetch data from the 'proposal' table
$sql = "SELECT pdf, name FROM proposal";
$result = $conn->query($sql);
$portfolios = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $portfolios[] = $row;
    }
}
$conn->close();
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
    <link href="libr/animate/animate.min.css" rel="stylesheet">
    <link href="libr/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
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
        $pageTitle = "Portofolio";
        include 'includes/header.php'; 
        ?>
        <!-- Header End -->
        <!-- Portofolio Start -->
        <div class="container-fluid about bg-light py-5">
            <div class="container py-5">
                    <div class="section-title mb-5 text-center" style="background: linear-gradient(135deg,rgba(107, 17, 203, 0.35), #2575fc); padding: 20px; border-radius: 10px; color: white;">
                        <h1 class="display-4 font-weight-bold" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);">Proposal Kami</h1>
                    </div>
                    <?php foreach ($portfolios as $portfolio): ?>
                        <?php if (!empty($portfolio['pdf'])): ?>
                            <?php $file = $portfolio['pdf']; ?>
                            <div class="portfolio-item">
                            <p class="pdf-title" style="margin-bottom: 10px; font-size: 20px; text-transform: uppercase;"><?php echo $portfolio['name']; ?></p>
                                <iframe src="pdf/<?php echo $file; ?>" 
                                        style="width:100%; height:600px;" data-aos="fade-up" data-aos-delay="500" frameborder="0"></iframe>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

            </div>
        </div>
        <!-- Portofolio End -->
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
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>