<?php
include 'database.php';    // koneksi $conn
// Ambil semua layanan
$sql = "SELECT * FROM services ORDER BY id ASC";
$result = $conn->query($sql);
$services = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $services[] = $row;
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
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&amp;family=Playfair+Display:wght@400;500;600&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Libraries & Styles -->
    <link href="libr/animate/animate.min.css" rel="stylesheet">
    <link href="libr/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <!-- <style>
        /* Layout styling */
        .service-item {
            background-color: #f4f4f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
            transition: transform 0.3s;
        }

        .service-img {
            position: relative;
            overflow: hidden;
            height: 400px; /* Set a fixed height or adjust based on your needs */
        }

        .service-img img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures the image covers the container properly */
            transition: transform 0.3s ease;
        }

        .service-img:hover img {
            transform: scale(1.1); /* Increased scale effect for the hover */
        }


        .service-content {
            padding: 20px;
            text-align: center;
        }

        .service-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .service-description {
            font-size: 1rem;
            color: #000000;
            margin-bottom: 15px;
        }

        .service-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .service-actions a {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .service-actions a:hover {
            background-color: #0056b3;
        }

        /* Styling for the services list */
        .service-list {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 30px;
        }

        .service .service-item {
            width: 70%;
        }
        /* .service-list .service-item {
            width: 22%;
        } */

        .service-list .service-item img {
            object-fit: cover;
        }
        .service .service-item .service-img {
            position: relative;
            width: auto;
            height: auto;
            /* width: 100%;
            height: 300px; */
            overflow: hidden;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f8f88b;
        }
        .service .service-item .service-img img {
            width: auto;
            height: auto;
            /* width: 100%;
            height: 100%; */
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .service-list .service-item {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .service-list .service-item {
                width: 100%;
            }
        }
        .service .service-item .service-content {
            background-color: #f2d7d3;
        }
        .service-item .btn-primary {
            color: black;
        }
        .text-white {
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .sub-title{
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .btn.btn-primaryy:hover{
            box-shadow: inset 1500px 0 0 0 black !important;
            color:rgb(255, 255, 255) !important;
        }
    </style> -->
    <style>
        .service-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            /* margin-bottom: 60px; */
        }
        .service-item {
            flex: 0 1 22%;      /* 4 kolom di desktop */
            text-align: center;
        }
        .service-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 12px;
            color:rgb(255, 255, 255);
            transition: color .2s;
            background-color:rgb(0, 0, 0);
            border-radius: 10px;
            height: 90px;
            max-height: 100%;
            display: flex;
            align-items: center;
        }
        .service-item a:hover .service-title {
            color:rgb(10, 34, 22);
            background-color:rgb(255, 255, 255);
        }
        /* Kontainer gambar */
        .service-img {
        width: 350px !important;       /* lebar tetap */
        height: 350px !important;      /* tinggi tetap */
        margin: 0 auto;     /* center */
        overflow: hidden;
        border-radius: 10px;
        background: #f4f4f9;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform .3s;
        }
        .service-img img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform .3s;
        }
        .service-img:hover img {
        transform: scale(1.1);
        }

        .service .service-item .service-img img {
            width: 100%;
            height: 250px;
            object-fit: contain;
        }
        /* Responsif */
        @media (max-width: 992px) {
        .service-item { flex: 0 1 30%; }
        }
        @media (max-width: 768px) {
        .service-item { flex: 0 1 45%; }
        }
        @media (max-width: 480px) {
        .service-item { flex: 0 1 90%; }
        .service-img { width: 100%; height: auto; aspect-ratio: 1; }
        }
        .py-5 {
            padding-bottom: 2rem !important;
        }
        .text-white {
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .sub-title{
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .service-item:hover .service-img {
            background-color: rgb(149 142 250 / 89%);
        }
    </style>
</head>
<body>
    <div class="bckg">
        <!-- Spinner, Topbar, Navbar -->
        <?php include 'includes/spinner.php'; ?>
        <?php include 'includes/topbar.php'; ?>
        <?php include 'includes/navbar.php'; ?>

        <!-- Header -->
        <?php
        $pageTitle = "Layanan";
        include 'includes/header.php';
        ?>

        <!-- Services Start -->
        <div class="container-fluid service bg-light py-5">
            <div class="container py-5">
                <div class="section-title mb-5">
                    <div class="sub-style">
                        <h1 class="sub-title px-3 mb-0">Layanan</h1>
                    </div>
                </div>

                <!-- Display all services in a single row -->
                <div class="service-list">
                    <?php foreach ($services as $svc): ?>
                        <div class="service-item" data-aos="fade-up">
                            <a href="detail_service.php?id=<?php echo $svc['id']; ?>" title="Lihat detail <?php echo htmlspecialchars($svc['title']); ?>">
                            <div class="service-title">
                                <?php echo htmlspecialchars($svc['title']); ?>
                            </div>
                            <div class="service-img">
                                <?php if (!empty($svc['icon'])): ?>
                                    <img src="admin/images/services/<?php echo $svc['icon']; ?>"
                                         alt="<?php echo $svc['title']; ?>" class="img-fluid">
                                <?php else: ?>
                                    <img src="img/default-service-icon.png" alt="Default Icon" class="img-fluid">
                                <?php endif; ?>
                            </div>
                            </a>
                            <!-- <div class="service-content">
                                <h3 style="font-family: 'Franklin Gothic Medium'; font-size: 40px;"><?php echo $svc['title']; ?></h3>
                                <div class="service-description">
                                    <?php
                                    // Display the description with HTML tags
                                    echo $svc['descrip'];
                                    ?>
                                </div>
                                <a style="color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset; width: 100%; background-color: #00ff2938;" href="detail_service.php?id=<?php echo $svc['id']; ?>" class="btn btn-primaryy">Detail</a>
                            </div> -->
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- Services End -->

        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
        <?php include 'includes/copyright.php'; ?>
        <?php include 'includes/back_to_top.php'; ?>
    </div>

    <!-- JS Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="libr/wow/wow.min.js"></script>
    <script src="libr/easing/easing.min.js"></script>
    <script src="libr/waypoints/waypoints.min.js"></script>
    <script src="libr/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="js/main.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
