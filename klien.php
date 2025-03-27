<?php
include 'database.php';
// Tentukan jumlah klien yang akan ditampilkan per halaman
$clientsPerPage = 8;
// Tentukan halaman saat ini dari URL atau default ke halaman 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
// Tentukan offset untuk query SQL
$offset = ($page - 1) * $clientsPerPage;
// Query untuk mengambil data klien dengan paginasi
$sql = "SELECT klien, gambar FROM klien LIMIT $clientsPerPage OFFSET $offset";
$result = $conn->query($sql);
$clients = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}
// Menghitung jumlah total klien untuk paginasi
$sqlCount = "SELECT COUNT(*) AS total FROM klien";
$countResult = $conn->query($sqlCount);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $clientsPerPage);
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
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        .hidden {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        .visible {
            opacity: 1;
        }
        .pagination {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            color:rgb(0, 0, 0);
            text-decoration: none;
        }
        .pagination a.active {
            background-color:rgb(0, 0, 0);
            color: white;
            border: 1px solid rgb(230, 242, 255);
        }
        .pagination a:hover {
            background-color: #ddd;
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
        $pageTitle = "Klien Kami";
        include 'includes/header.php'; 
        ?>
        <!-- Header End -->
        <!-- Our Client Start -->
        <div class="container-fluid about team py-5">
            <div class="container py-5">
                <div class="section-title mb-5">
                    <div class="sub-style">
                        <h1 class="sub-title px-3 mb-0">Klien Kami</h1>
                    </div>
                </div>
                <div class="clients-container" data-aos="fade-up" data-aos-delay="500">
                    <?php foreach ($clients as $client): ?>
                        <div class="client-card wow fadeInUp">
                            <img src="admin/images/partnership/<?php echo htmlspecialchars($client['gambar']); ?>" 
                                alt="<?php echo htmlspecialchars($client['klien']); ?>">
                            <p><?php echo htmlspecialchars($client['klien']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Paginasi Start -->
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="prev-btn">Sebelumnya</a>
                    <?php endif; ?>
                </div>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                </div>
                <div class="pagination">
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="next-btn">Selanjutnya</a>
                <?php endif; ?>
                </div>
                <!-- Paginasi End -->
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