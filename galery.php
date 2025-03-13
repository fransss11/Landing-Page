<?php
include 'database.php';

// Fetch data from the 'media' table
$sql = "SELECT id, galery, foto, kategori, uploaded_on FROM media WHERE status = '1'";
$result = $conn->query($sql);

$images = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $images[$row['kategori']][] = $row;
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

    <style>
        /* Animasi Fade In */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animasi untuk setiap gambar */
        .gallery-item {
            animation: fadeIn 1s ease-in-out;
            opacity: 1;
        }

        /* Efek hover untuk gambar */
        .gallery-item img {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            background-color: rgba(0, 0, 0, 0.05);
            padding: 10px;
            border-radius: 8px;
        }

        .gallery-item img:hover {
            transform: scale(1.05);
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.2);
        }

        /* Animasi WOW.js */
        .wow {
            visibility: hidden;
        }

        /* Animasi untuk tombol */
        .btn-primary {
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            background-color: #0056b3;
        }
    </style>
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
    $pageTitle = "Galeri";
    include 'includes/header.php';
    ?>
    <!-- Header End -->

    <!-- Gallery Start -->
    <div class="container py-5">
        <?php foreach ($images as $kategori => $kategori_images): ?>
            <div class="row text-center mb-4">
                <h3 style="background: #9300ff ;"><?php echo htmlspecialchars($kategori); ?></h3>
            </div>
            <div class="row">
                <?php foreach ($kategori_images as $index => $image): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="client-card wow fadeInUp" data-wow-delay="<?php echo $index * 0.2; ?>s">
                            <!-- <a href="detail.php?id=<?php echo $image['id']; ?>"> -->
                            <a>
                                <img src="admin/uploads/<?php echo $image['foto']; ?>" class="img-fluid" alt="<?php echo $image['galery']; ?>">
                            </a>
                            <h6 class="mt-2"><?php echo $image['galery']; ?></h6>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Gallery End -->

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