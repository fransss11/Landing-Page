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
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5">
            <h3 class="text-white display-3 mb-4 wow fadeInDown" data-wow-delay="0.3s">Detail Berita</h3>
            <ol class="breadcrumb justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.5s">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="berita.php">Berita</a></li>
                <li class="breadcrumb-item active text-primary">Detail Berita</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->

    <!-- Detail Berita Start -->
    <div class="container py-5">
        <h2 class="text-center mb-4">Detail Berita</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div id="berita-container">
                    <?php if ($berita): ?>
                        <div class="card shadow-lg animated-content">
                            <img id="gambar-berita" class="img-fluid card-img-top" src="admin/images/blog/<?php echo $berita['img']; ?>" alt="Gambar Berita">
                            <div class="card-body">
                                <h2 id="judul-berita" class="wow fadeInUp" data-wow-delay="0.5s"><?php echo $berita['title']; ?></h2>
                                <p class="text-muted wow fadeInUp" data-wow-delay="0.6s"><i class="fa fa-calendar-alt text-primary"></i> <span id="tanggal-berita"><?php echo formatTanggalIndonesia($berita['date']); ?></span></p>
                                <p id="konten-berita" class="wow fadeInUp" data-wow-delay="0.7s"><?php echo $berita['descrip']; ?></p>
                                <a href="berita.php" class="btn btn-primary wow fadeInUp" data-wow-delay="0.8s">Kembali ke Berita</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <h3 class="text-danger">Berita tidak ditemukan!</h3>
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