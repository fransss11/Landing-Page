<?php
include 'database.php';

// Fetch only the 5 latest articles for main section
$sqlLatest = "SELECT * FROM articles ORDER BY id DESC LIMIT 5";
$resultLatest = $conn->query($sqlLatest);
$latestArticles = [];
if ($resultLatest->num_rows > 0) {
    while ($row = $resultLatest->fetch_assoc()) {
        $latestArticles[] = $row;
    }
}

// Pagination for sidebar list
$page   = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page   = max($page, 1);
$limit  = 10;
$offset = ($page - 1) * $limit;

// Total articles count
$countRes      = $conn->query("SELECT COUNT(*) AS total FROM articles");
$totalArticles = $countRes->fetch_assoc()['total'];
$totalPages    = ceil($totalArticles / $limit);

// Fetch up to 10 articles for this page
$sqlSidebar      = "SELECT * FROM articles ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$resultSidebar   = $conn->query($sqlSidebar);
$sidebarArticles = [];
if ($resultSidebar->num_rows > 0) {
    while ($row = $resultSidebar->fetch_assoc()) {
        $sidebarArticles[] = $row;
    }
}

$conn->close();

// Nama bulan & hari dalam Bahasa Indonesia
$bulanIndo = ["Januari","Februari","Maret","April","Mei","Juni",
              "Juli","Agustus","September","Oktober","November","Desember"];
$hariIndo  = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];

// Format tanggal
function formatTanggalIndonesia($tanggal) {
    global $bulanIndo, $hariIndo;
    $ts   = strtotime($tanggal);
    $hari = $hariIndo[date('w',$ts)];
    $tgl  = date('j',$ts);
    $bln  = $bulanIndo[date('n',$ts)-1];
    $thn  = date('Y',$ts);
    return "$hari, $tgl $bln $thn";
}

// Group sidebar page articles by month-year
$groupedSidebar = [];
foreach ($sidebarArticles as $a) {
    $ts      = strtotime($a['created_at']);
    $periode = $bulanIndo[date('n',$ts)-1] . ' ' . date('Y',$ts);
    $groupedSidebar[$periode][] = $a;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lisa Mitra Mandiri - Artikel Terbaru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS Libraries -->
    <link href="libr/animate/animate.min.css" rel="stylesheet">
    <link href="libr/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Bootstrap & Custom -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <style>
        .hidden { opacity: 0; transition: opacity .5s ease; }
        .visible { opacity: 1; }
        .pagination { justify-content: center; }
    </style>
</head>
<body>
    <div class="bckg">
        <?php include 'includes/spinner.php'; ?>
        <?php include 'includes/topbar.php'; ?>
        <?php include 'includes/navbar.php'; ?>

        <?php 
        $pageTitle = "Artikel";
        include 'includes/header.php';
        ?>

        <!-- Articles Start -->
        <div class="container-fluid article bg-light py-5">
            <div class="container py-5">
                <div class="section-title mb-5">
                    <div class="sub-style">
                        <h1 class="sub-title px-3 mb-0">Artikel Terbaru</h1>
                    </div>
                </div>
                <div class="row">
                    <!-- Main: 5 Artikel Vertikal -->
                    <div class="col-lg-8">
                        <?php foreach ($latestArticles as $a): ?>
                        <div class="article-item rounded bg-white shadow-sm mb-5 overflow-hidden" data-aos="fade-up">
                            <div class="article-img">
                                <?php if (!empty($a['img'])): ?>
                                    <img src="admin/images/artikel/<?= htmlspecialchars($a['img']); ?>"
                                         class="img-fluid w-100" alt="<?= htmlspecialchars($a['title']); ?>">
                                <?php else: ?>
                                    <img src="img/default-article-icon.png"
                                         class="img-fluid w-100" alt="Default Icon">
                                <?php endif; ?>
                            </div>
                            <div class="p-4">
                                <h4 class="mb-2"><?= htmlspecialchars($a['title']); ?></h4>
                                <small class="text-muted">
                                    <?= formatTanggalIndonesia($a['created_at']); ?>
                                    <?php if (!empty($a['author'])): ?>
                                        – Penulis: <?= htmlspecialchars($a['author']); ?>
                                    <?php endif; ?>
                                </small>
                                <p class="mt-3">
                                    <?php
                                    $txt = strip_tags($a['content']);
                                    if (strlen($txt) > 300) {
                                        $cut = substr($txt, 0, 300);
                                        $txt = substr($cut, 0, strrpos($cut, ' ')) . '...';
                                    }
                                    echo htmlspecialchars($txt);
                                    ?>
                                </p>
                                <a href="detail_artikel.php?id=<?= $a['id']; ?>"
                                   class="btn btn-danger rounded-pill px-4">Baca Selengkapnya</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Sidebar: Max 10 Artikel dengan Pagination -->
                    <div class="col-lg-4">
                        <div class="bg-white p-4 rounded shadow-sm mb-4">
                            <h5 class="border-start border-3 border-danger ps-3 mb-4">Daftar Artikel</h5>
                            <?php foreach ($groupedSidebar as $periode => $items): ?>
                                <h6 class="mt-3 text-danger"><?= htmlspecialchars($periode); ?></h6>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($items as $item): ?>
                                    <li class="mb-2">
                                        <i class="bi bi-chevron-right text-danger me-2"></i>
                                        <a href="detail_artikel.php?id=<?= $item['id']; ?><?= $page>1? '&page='.$page:''; ?>" class="text-dark">
                                            <?= htmlspecialchars($item['title']); ?>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endforeach; ?>
                        </div>
                        <!-- Pagination Controls -->
                        <nav>
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page-1; ?>">Prev</a>
                                </li>
                                <?php endif; ?>
                                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                <li class="page-item <?= $p === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?= $p; ?>"><?= $p; ?></a>
                                </li>
                                <?php endfor; ?>
                                <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page+1; ?>">Next</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- Articles End -->

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
