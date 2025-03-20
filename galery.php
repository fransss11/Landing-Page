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
    <div class="container-fluid team py-5">
        <div class="container py-5">
            <div class="section-title mb-1 wow fadeInUp" data-wow-delay="0.1s">
                <div class="sub-style">
                    <h4 class="sub-title px-3 mb-0">Galeri Kami</h4>
                </div>
            </div>
            <!-- Petunjuk penggunaan galeri -->
            <div class="text-center mb-4">
                <p class="text-mutedd" style="font-size: 16px;">
                    Klik pada <strong>nama kegiatan</strong> untuk membuka atau menutup gambar berdasarkan kegiatan, atau klik tombol <strong>"Lihat Semua Gambar"</strong> untuk membuka/menutup semua gambar sekaligus.
                </p>
            </div>
            <!-- Tombol Lihat Semua Gambar (posisi diperbaiki) -->
            <div class="text-center mb-4">
                <button class="btn btn-primary" id="lihat-semua">Lihat Semua Gambar</button>
            </div>
            <?php foreach ($images as $kategori => $kategori_images): ?>
                <div class="row text-center mb-4">
                    <h3 class="kategori-header" style="background:var(--bs-primary) !important; cursor:pointer; border-radius: 25px; " data-kategori="<?= htmlspecialchars($kategori); ?>">
                        <?= htmlspecialchars($kategori); ?>
                    </h3>
                </div>
                <div class="row kategori-content" id="kategori-<?= htmlspecialchars($kategori); ?>" style="display: none;">
                    <?php foreach ($kategori_images as $index => $image): ?>
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="client-card wow fadeInUp" data-wow-delay="<?= $index * 0.2; ?>s" data-wow-duration="0.8s">
                                <a>
                                    <img src="admin/uploads/<?= $image['foto']; ?>" class="img-fluid" alt="<?= $image['galery']; ?>">
                                </a>
                                <h6 class="mt-2"><?= $image['galery']; ?></h6>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
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
    <!-- Script JS untuk Toggle Kategori dan Tombol Lihat Semua -->
    <script>
        document.querySelectorAll('.kategori-header').forEach(function(header) {
            header.addEventListener('click', function() {
                const kategori = this.getAttribute('data-kategori');
                const konten = document.getElementById('kategori-' + kategori);
                // Cek apakah konten sedang tidak terlihat
                if (konten.style.display === 'none' || konten.style.display === '') {
                    konten.style.display = 'flex';
                    // Tambahkan class 'active' pada header yang diklik
                    this.classList.add('active');
                } else {
                    konten.style.display = 'none';
                    // Hapus class 'active' jika konten disembunyikan
                    this.classList.remove('active');
                }
            });
        });
        let semuaTerbuka = false;
        document.getElementById('lihat-semua').addEventListener('click', function() {
            semuaTerbuka = !semuaTerbuka;
            document.querySelectorAll('.kategori-content').forEach(function(konten) {
                konten.style.display = semuaTerbuka ? 'flex' : 'none';
            }); 
            this.textContent = semuaTerbuka ? 'Tutup Semua Gambar' : 'Lihat Semua Gambar';
        });
    </script>
</body>
</html>