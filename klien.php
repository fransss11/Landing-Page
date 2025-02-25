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
    $pageTitle = "Klien Kami";
    include 'includes/header.php'; 
    ?>
    <!-- Header End -->


    <!-- Our Client Start -->
    <div class="container-fluid team py-5">
        <div class="container py-5">
            <div class="section-title mb-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="sub-style">
                    <h2 class="sub-title px-3 mb-0">Klien Kami</h2>
                </div>
            </div>
            <div class="clients-container">
                <?php foreach ($clients as $client): ?>
                    <div class="client-card">
                        <img src="img/<?php echo $client['gambar']; ?>" alt="<?php echo $client['klien']; ?>">
                        <p><?php echo $client['klien']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- Our Client End -->

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