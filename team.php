<?php
include 'database.php';

// Fetch data from the 'klien' table
$sql = "SELECT klien, gambar FROM klien";
$result = $conn->query($sql);
$clients = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}

// Fetch data from the 'services' table
$sql = "SELECT * FROM services ORDER BY id DESC LIMIT 3";
$result = $conn->query($sql);
$services = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

// Fetch data from the 'about' table
$sql = "SELECT * FROM about ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$about = $result->fetch_assoc();

// Fetch data from the 'teams' table
$sql = "SELECT title, designation, img, facebook, twitter, instagram, linkedin, whatsapp FROM teams";
$result = $conn->query($sql);
$teamList = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teamList[] = $row;
    }
}
$conn->close();

// Pisahkan data berdasarkan designation (dengan pengecekan substring untuk kata tambahan)
// Direktur: jika contains "direktur"
// Manajer: jika contains "manajer"
// Staf: sisanya
$directorTeam = array();
$managerTeam  = array();
$staffTeam    = array();

foreach ($teamList as $member) {
    $designation = strtolower(trim($member['designation']));
    if (strpos($designation, 'direktur') !== false) {
        $directorTeam[] = $member;
    } else if (strpos($designation, 'manajer') !== false) {
        $managerTeam[] = $member;
    } else {
        $staffTeam[] = $member;
    }
}

// Gabungkan data sehingga direktur dan manajer tampil di atas, diikuti oleh staf
$combinedTeam = array_merge($directorTeam, $managerTeam, $staffTeam);

// Pagination untuk bagian tim (8 data per halaman)
$itemsPerPage = 8;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) {
    $currentPage = 1;
}
$totalItems = count($combinedTeam);
$totalPages = ceil($totalItems / $itemsPerPage);
$offset = ($currentPage - 1) * $itemsPerPage;
$limitedTeamList = array_slice($combinedTeam, $offset, $itemsPerPage);

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
    return "$hari, $tanggalNum $bulan $tahun";
}
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
        $pageTitle = "Tim Kami";
        include 'includes/header.php';
        ?>
        <!-- Header End -->
        <!-- Team Start -->
        <div class="container-fluid team py-5">
            <div class="container py-5">
                <div class="section-title mb-5" data-aos="flip-left" data-aos-delay="100">
                    <div class="sub-style">
                        <h1 class="sub-title px-3 mb-0">Tim Kami</h1>
                    </div>
                </div>
                <!-- Tampilkan tim dengan paging: 8 per halaman -->
                <div class="row g-4 justify-content-center align-items-stretch">
                    <?php foreach ($limitedTeamList as $index => $team): ?>
                    <div class="col-md-6 col-lg-6 col-xl-3"
                        data-aos="<?php echo $index % 2 == 0 ? 'fade-up' : 'fade-down'; ?>"
                        data-aos-delay="<?php echo $index * 300; ?>">
                        <div class="team-item rounded h-100 d-flex flex-column">
                            <div class="team-img rounded-top">
                                <img src="admin/images/team/<?php echo htmlspecialchars($team['img']); ?>"
                                    class="img-fluid team-image"
                                    alt="<?php echo htmlspecialchars($team['title']); ?>"
                                    style="width: 100%; height: 250px; object-fit: contain;">
                                <div class="team-icon d-flex justify-content-center">
                                    <?php if (!empty($team['facebook'])): ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="<?php echo $team['facebook']; ?>"><i class="fab fa-facebook-f"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($team['twitter'])): ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="<?php echo $team['twitter']; ?>"><i class="fab fa-twitter"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($team['instagram'])): ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="<?php echo $team['instagram']; ?>"><i class="fab fa-instagram"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($team['linkedin'])): ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="<?php echo $team['linkedin']; ?>"><i class="fab fa-linkedin-in"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($team['whatsapp'])): ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="https://wa.me/<?php echo $team['whatsapp']; ?>"><i class="fab fa-whatsapp"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="team-content text-center border border-primary border-top-0 rounded-bottom p-4 d-flex flex-column justify-content-between flex-grow-1">
                                <h5 class="team-title"><?php echo htmlspecialchars($team['title']); ?></h5>
                                <p class="team-designation mb-0"><?php echo htmlspecialchars($team['designation']); ?></p>
                                <!-- <p class="team-description mb-0" style="font-style: italic;"><?php echo htmlspecialchars($team['descrip']); ?></p> -->
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Pagination -->
                <div class="pagination text-center mt-4">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?php echo $currentPage - 1; ?>" class="btn btn-outline-primary mx-1">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="btn btn-primary mx-1"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>" class="btn btn-outline-primary mx-1"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?>" class="btn btn-outline-primary mx-1">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Team End -->
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
    </script>
</body>
</html>