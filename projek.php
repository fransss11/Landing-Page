<?php
include 'database.php';
// Ambil daftar tahun (distinct) untuk sidebar
$yearsResult = $conn->query("SELECT DISTINCT tahun FROM projek ORDER BY tahun DESC");
$years = [];
if ($yearsResult && $yearsResult->num_rows > 0) {
    while ($row = $yearsResult->fetch_assoc()) {
        $years[] = $row['tahun'];
    }
}
// Set default tahun jika tidak ada parameter 'year'
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
// Batasi jumlah proyek per halaman untuk tahun terpilih (misalnya 10)
$projectsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startFrom = ($page - 1) * $projectsPerPage;
// Query untuk mengambil proyek dari tahun yang dipilih dengan LIMIT
$sql = "SELECT * FROM projek WHERE tahun = '$selectedYear' ORDER BY id ASC LIMIT $startFrom, $projectsPerPage";
$result = $conn->query($sql);
$projects = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}
// Hitung total proyek untuk tahun yang dipilih agar bisa menentukan jumlah halaman
$totalProjectsResult = $conn->query("SELECT COUNT(*) AS total FROM projek WHERE tahun = '$selectedYear'");
$totalProjects = $totalProjectsResult->fetch_assoc()['total'];
$totalPages = ceil($totalProjects / $projectsPerPage);
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
    <!-- jQuery (for toggling) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
    $pageTitle = "Projek Kami";
    include 'includes/header.php'; 
    ?>
    <!-- Header End -->
    <div class="container">
        <div class="row">
            <!-- Sidebar with Year Navigation -->
            <div class="col-md-3">
                <div class="sidebar">
                    <h3>Proyek Kami</h3>
                    <ul class="year-list">
                        <?php foreach ($years as $year): ?>
                            <li>
                                <a href="?year=<?php echo $year; ?>" 
                                   class="<?php echo ($selectedYear == $year) ? 'active' : ''; ?>">
                                    <?php echo $year; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <!-- Main Content Area -->
            <div class="col-md-9">
                <h2 id="selected-year-message">
                    Proyek Kami di Tahun <?php echo $selectedYear; ?>
                </h2>
                <!-- Tampilkan proyek jika ada -->
                <?php if (!empty($projects)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mitra</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $index => $project): ?>
                                <tr>
                                    <td><?php echo $startFrom + $index + 1; ?></td>
                                    <td><?php echo $project['mitra']; ?></td>
                                    <td><?php echo $project['deskrip']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a href="?year=<?php echo $selectedYear; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                <?php else: ?>
                    <p>Tidak ada proyek untuk tahun <?php echo $selectedYear; ?>.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
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
    <script>
    window.addEventListener('load', function() {
        // Jika URL memiliki parameter query, hapus dengan mengganti URL tanpa query
        if(window.location.search) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });
    </script>
</body>
</html>