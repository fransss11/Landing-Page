<?php
include 'database.php';

// Get article ID from query string
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the requested article
$sqlArticle = "SELECT * FROM articles WHERE id = $id";
$resultArticle = $conn->query($sqlArticle);
if ($resultArticle->num_rows === 0) {
    header("Location: artikel.php");
    exit;
}
$article = $resultArticle->fetch_assoc();
$conn->close();

// Indonesian month & day names
$bulanIndo = ["Januari","Februari","Maret","April","Mei","Juni",
              "Juli","Agustus","September","Oktober","November","Desember"];
$hariIndo  = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];

// Format full date
function formatTanggalIndonesia($tanggal) {
    global $bulanIndo, $hariIndo;
    $ts   = strtotime($tanggal);
    $hari = $hariIndo[date('w',$ts)];
    $tgl  = date('j',$ts);
    $bln  = $bulanIndo[date('n',$ts)-1];
    $thn  = date('Y',$ts);
    return "$hari, $tgl $bln $thn";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($article['title']); ?> – Lisa Mitra Mandiri</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS Libraries -->
    <link href="libr/animate/animate.min.css" rel="stylesheet">
    <link href="libr/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Bootstrap & Custom CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <style>
        .hidden { opacity: 0; transition: opacity .5s ease; }
        .visible { opacity: 1; }
    </style>
</head>
<body>
    <div class="bckg">
        <!-- Spinner, Topbar, Navbar -->
        <?php include 'includes/spinner.php'; ?>
        <?php include 'includes/topbar.php'; ?>
        <?php include 'includes/navbar.php'; ?>

        <!-- Header with dynamic page title -->
        <?php 
        $pageTitle = $article['title'];
        include 'includes/header.php';
        ?>

        <!-- Detail Article Start -->
        <div class="container-fluid article bg-light py-5">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <!-- Main Column: Article Detail -->
                    <div class="col-lg-8">
                        <div class="article-item rounded bg-white shadow-sm mb-5 overflow-hidden" data-aos="fade-up">
                            <div class="article-img">
                                <?php if (!empty($article['img'])): ?>
                                    <img src="admin/images/artikel/<?= htmlspecialchars($article['img']); ?>"
                                         class="img-fluid w-100" alt="<?= htmlspecialchars($article['title']); ?>">
                                <?php else: ?>
                                    <img src="img/default-article-icon.png"
                                         class="img-fluid w-100" alt="Default Icon">
                                <?php endif; ?>
                            </div>
                            <div class="p-4">
                                <h2 class="mb-3"><?= htmlspecialchars($article['title']); ?></h2>
                                <small class="text-muted"><?= formatTanggalIndonesia($article['created_at']); ?>
                                    <?php if (!empty($article['author'])): ?>
                                        – Penulis: <?= htmlspecialchars($article['author']); ?>
                                    <?php endif; ?>
                                </small>
                                <div class="mt-4 article-content">
                                    <?= $article['content']; ?>
                                </div>
                                <a href="artikel.php" class="btn btn-danger rounded-pill px-4 mt-4">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Detail Article End -->

        <!-- Footer & Back to Top -->
        <?php include 'includes/footer.php'; ?>
        <?php include 'includes/copyright.php'; ?>
        <?php include 'includes/back_to_top.php'; ?>
    </div>

    <!-- JavaScript Libraries -->
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
        function revealOnScroll() {
            document.querySelectorAll('.hidden, .visible').forEach(el => {
                const top = el.getBoundingClientRect().top;
                el.classList.toggle('visible', top < window.innerHeight - 150);
                el.classList.toggle('hidden', top >= window.innerHeight - 150);
            });
            if (window.scrollY === 0) {
                document.querySelectorAll('.visible').forEach(el => {
                    el.classList.remove('visible');
                    el.classList.add('hidden');
                });
            }
        }
        window.addEventListener('load', revealOnScroll);
        window.addEventListener('scroll', revealOnScroll);
    </script>
</body>
</html>
