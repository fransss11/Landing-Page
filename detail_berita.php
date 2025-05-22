<?php
include 'database.php';
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
    $jam = date('H:i:s', $dateObj);
    return "$hari, $tanggalNum $bulan $tahun $jam";
}
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT id, title, category, descrip, img, date, url FROM blog WHERE id = $id";
$result = $conn->query($sql);
$berita = null;
if ($result->num_rows > 0) {
    $berita = $result->fetch_assoc();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Detail Berita - Lisa Mitra Mandiri</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
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
    <?php include 'includes/logo.php'; ?>
    <style>
    html, body {
        overflow-x: hidden;
        width: 100%;
        position: relative;
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        .card-img-top {
            width: 100%;
            height: auto;
            max-width: 100%;
        }
        p#konten-berita {
            font-size: 1rem;
        }
        /* Ensure all content stays within viewport */
        .container {
            max-width: 100%;
            padding-left: 15px;
            padding-right: 15px;
        }
        img {
            max-width: 100%;
            height: auto;
        }
    }
    .text-white {
        background-color: #ffffffba !important;
        border-radius: 8px;
    }
    .sub-title{
        background-color: #ffffffba !important;
        border-radius: 8px;
    }
    .card-img-top {
        width: auto;
        height: auto;
        margin-top: auto;
        max-height: 100%;
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
        $pageTitle = "Detail Berita";
        include 'includes/header.php';
        ?>
        <!-- Header End -->
        <!-- Detail Berita Start -->
        <div class="container py-5">
            <div class="section-title mb-5">
                <div class="sub-style">
                    <h1 class="sub-title px-3 mb-0">Detail Berita</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto" style="width: 100%;">
                    <div id="berita-container">
                        <?php if ($berita): ?>
                            <div class="card shadow-lg animated-content">
                                <img id="gambar-berita" class="img-fluid card-img-top rounded-top" src="admin/images/blog/<?php echo $berita['img']; ?>" alt="Gambar Berita">
                                <div class="card-body">
                                    <h3 id="judul-berita" class="wow fadeInUp" data-wow-delay="0.5s"><?php echo $berita['title']; ?></h3>
                                    <p class="text-muted wow fadeInUp" data-wow-delay="0.6s"><i class="fa fa-calendar-alt text-primary"></i> <span id="tanggal-berita"><?php echo formatTanggalIndonesia($berita['date']); ?></span></p>
                                    <p id="konten-berita" class="wow fadeInUp" data-wow-delay="0.7s"><?php echo nl2br($berita['descrip']); ?></p>
                                    <a style="color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset;" href="berita.php" class="btn btn-primary wow fadeInUp" data-wow-delay="0.8s">Kembali ke Berita</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <h3 class="text-danger text-center">Berita tidak ditemukan!</h3>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Detail Berita End -->
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
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <!-- Inisialisasi WOW.js -->
    <script>
        new WOW().init();
    </script>
</body>
</html>