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
    <link href="libr/animate/animate.min.css" rel="stylesheet">
    <link href="libr/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
    <!-- jQuery (for toggling) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <style>
        /* General styling */
        .projek {
            padding: 50px 0;
        }
        
        /* Sidebar styling */
        .sidebar {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        
        .sidebar h3 {
            color: #333;
            font-weight: 600;
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 20px;
            text-align: center;
            font-family: 'Playfair Display', serif;
        }
        
        .sidebar h3::after {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background: #0d6efd;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .year-list {
            list-style-type: none;
            padding: 0;
        }
        
        .year-list li {
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .year-list a {
            display: block;
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-left: 4px solid transparent;
            border-radius: 5px;
            color: #555;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .year-list a:hover {
            background-color: #e9ecef;
            border-left-color: #0d6efd;
            color: #0d6efd;
            transform: translateX(5px);
        }
        
        .year-list a.active {
            background-color: #e7f1ff;
            border-left-color: #0d6efd;
            color: #0d6efd;
            font-weight: 600;
        }
        
        /* Table styling */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        th {
            background-color: #0d6efd !important;
            color: white !important;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9em;
            letter-spacing: 0.5px;
        }
        
        tbody {
            background-color: #ffffff !important;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
            color: #333;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover td {
            background-color: #f8f9fa;
        }
        
        /* Year message */
        #selected-year-message {
            background-color: #ffffff !important;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            color: #333;
            position: relative;
            overflow: hidden;
            font-family: 'Playfair Display', serif;
        }
        
        #selected-year-message::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 5px;
            background-color: #0d6efd;
        }
        
        /* Pagination styling */
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 30px 0 0;
        }
        
        .pagination li {
            margin: 0 5px;
        }
        
        .pagination a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #ffffff;
            border-radius: 50%;
            color: #555;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .pagination li.active a {
            background-color: #0d6efd;
            color: white;
        }
        
        .pagination a:hover {
            background-color: #e9ecef;
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .empty-state i {
            font-size: 50px;
            color: #ccc;
            margin-bottom: 20px;
            display: block;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                margin-bottom: 30px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            th, td {
                padding: 12px;
            }
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
        $pageTitle = "Projek Kami";
        include 'includes/header.php'; 
        ?>
        <!-- Header End -->
        <!-- Projek Start -->
        <div class="container-fluid projek py-5">
            <div class="container">
                <div class="row" data-aos="fade-up">
                    <!-- Sidebar with Year Navigation -->
                    <div class="col-lg-3 col-md-4">
                        <div class="sidebar" data-aos="fade-right" data-aos-delay="300">
                            <h3><i class="fas fa-calendar-alt me-2"></i>Tahun Proyek</h3>
                            <ul class="year-list">
                                <?php foreach ($years as $year): ?>
                                    <li>
                                        <a href="?year=<?php echo $year; ?>" 
                                        class="<?php echo ($selectedYear == $year) ? 'active' : ''; ?>">
                                            <i class="far fa-calendar-check me-2"></i><?php echo $year; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <!-- Main Content Area -->
                    <div class="col-lg-9 col-md-8" data-aos="fade-up" data-aos-delay="500">
                        <h2 id="selected-year-message" data-aos="fade-up" data-aos-delay="500">
                            <i class="fas fa-project-diagram me-2"></i>Proyek Kami di Tahun <?php echo $selectedYear; ?>
                        </h2>
                        <!-- Tampilkan proyek jika ada -->
                        <?php if (!empty($projects)): ?>
                            <div class="table-responsive" data-aos="fade-up" data-aos-delay="500">
                                <table>
                                    <thead>
                                        <tr>
                                            <th width="10%">No</th>
                                            <th width="30%">Nama Mitra</th>
                                            <th width="60%">Deskripsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($projects as $index => $project): ?>
                                            <tr>
                                                <td><?php echo $startFrom + $index + 1; ?></td>
                                                <td><strong><?php echo $project['mitra']; ?></strong></td>
                                                <td><?php echo $project['deskrip']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination Links -->
                            <?php if ($totalPages > 1): ?>
                                <ul class="pagination" data-aos="fade-up" data-aos-delay="600">
                                    <?php if ($page > 1): ?>
                                        <li>
                                            <a href="?year=<?php echo $selectedYear; ?>&page=<?php echo ($page-1); ?>" aria-label="Previous">
                                                <i class="fas fa-angle-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                                            <a href="?year=<?php echo $selectedYear; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $totalPages): ?>
                                        <li>
                                            <a href="?year=<?php echo $selectedYear; ?>&page=<?php echo ($page+1); ?>" aria-label="Next">
                                                <i class="fas fa-angle-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="empty-state" data-aos="fade-up" data-aos-delay="500">
                                <i class="fas fa-clipboard-list"></i>
                                <p>Tidak ada proyek untuk tahun <?php echo $selectedYear; ?>.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Projek End -->
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="libr/wow/wow.min.js"></script>
    <script src="libr/easing/easing.min.js"></script>
    <script src="libr/waypoints/waypoints.min.js"></script>
    <script src="libr/owlcarousel/owl.carousel.min.js"></script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
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