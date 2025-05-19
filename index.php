<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'database.php';
// Fetch data from the 'klien' table
$sql = "SELECT klien, gambar FROM klien";
$result = $conn->query($sql);
$clients = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}

// Fetch data from the 'services' table
$sql = "SELECT * FROM services ORDER BY id DESC LIMIT 4";
$result = $conn->query($sql);
$services = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
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
    while($row = $result->fetch_assoc()) {
        $teamList[] = $row;
    }
}

// Pisahkan data tim menjadi tiga kategori: direktur, manajer, dan staf (lainnya)
$directorTeam = array();
$managerTeam  = array();
$staffTeam    = array();

foreach ($teamList as $member) {
    $designation = strtolower(trim($member['designation']));
    // Jika mengandung kata "direktur"
    if (strpos($designation, 'direktur') !== false) {
        $directorTeam[] = $member;
    }
    // Jika mengandung kata "manajer"
    else if (strpos($designation, 'manajer') !== false) {
        $managerTeam[] = $member;
    }
    // Jika tidak mengandung kata kunci tersebut, dianggap sebagai staf
    else {
        $staffTeam[] = $member;
    }
}

// Gabungkan data pimpinan (direktur dan manajer)
$pimpinan = array_merge($directorTeam, $managerTeam);

// Jika jumlah pimpinan >= 8, tampilkan semua pimpinan
// Jika kurang dari 8, tambahkan data staf hingga total tampil 8 (jika ada)
if (count($pimpinan) >= 8) {
    $limitedTeamList = $pimpinan;
} else {
    $needed = 8 - count($pimpinan);
    $limitedTeamList = array_merge($pimpinan, array_slice($staffTeam, 0, $needed));
}

// ---- TAMBAHAN PAGINATION BAGIAN TIM ----
// Set jumlah data per halaman
$perPage = 8;
$totalTeams = count($limitedTeamList);
$totalPages = ceil($totalTeams / $perPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) {
    $currentPage = 1;
}
if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}
$startIndex = ($currentPage - 1) * $perPage;
$teamsToShow = array_slice($limitedTeamList, $startIndex, $perPage);
// ---------------------------------------

// Fetch data from the 'testimonials' table
$sql = "SELECT title, designation, descrip, img, date FROM testimonials";
$result = $conn->query($sql);
$testimonials = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}

// Fetch data from the 'blog' table
$sql = "SELECT id, title, category, descrip, img, date FROM blog ORDER BY id DESC LIMIT 3";
$result = $conn->query($sql);
$beritaList = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $beritaList[] = $row;
    }
}


// Fetch link YouTube dari tabel info
$sql       = "SELECT profile, sapaan FROM info WHERE id_info='1'";
$videoRes  = $conn->query($sql);
$video     = $videoRes ? $videoRes->fetch_assoc() : null;
$embedUrl = '';
if (!empty($video['profile'])) {
    $url = trim($video['profile']);
    // coba ambil video ID dari berbagai macam format
    if (preg_match('/(?:youtu\.be\/|youtube\.com\/watch\?v=|youtube\.com\/embed\/)([A-Za-z0-9_-]+)/', $url, $m)) {
        $videoId  = $m[1];
        $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
    }
    // jika ternyata sudah embed atau format lain, fallback ke apa yang diinput
    else {
        $embedUrl = $url;
    }
}

$conn->close();

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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="libr/animate/animate.min.css" rel="stylesheet">
    <link href="libr/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="css/style.css?v=20251505" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <!-- AOS Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <style>
        body {
            overflow-x: hidden;
        }
        .reviewer-name {
            min-height: auto;
        }
        .client-reviews{
            height: 100%;
        }
        .single-review{
            max-width: 100%;
        }
        .client-reviews .single-review {
            height: auto;
            width: 309px;
        }
        .client-reviews .reviewer-thumb img {
            width: 100%;
        }
        .mb-3 {
            background: #ffffffba;
            border-radius: 20px;
        }
        .text-white {
            background: #ffffffba;
            border-radius: 8px;
        }
        .sub-title {    
            background-color: #eeeeeec7;
            border-radius: 20px;
        }
        .service .service-item .service-img {
                width: auto;
                height: auto;
            }
        .service .service-item .service-img img {
            width: auto;
            height: auto;
        }
        .team .team-item .team-content {
            height: auto;
        }
        .card {
            width: 100%;
        }
        .card-title {
            min-height: auto;
        }
        .row {
            justify-content: space-around;
        }
        /* b, strong {
            background-color:rgba(255, 255, 255, 0.75);
            border-radius: 10px;
        } */
        .carousel-control-next-icon {
            background-color: black;
            margin-left: auto;
        }
        .carousel-control-prev-icon {
            background-color: black;
            margin-right: auto;
        }
        .carousel-indicators [data-bs-target]{
            width: 30px;
            height: 6px;
            background-color: #000000;
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
        <?php include 'includes/navbar-index.php'; ?>
        <!-- Navbar End -->
        <!-- Sapaan -->
        <div class="container-fluid" data-aos="fade-up" data-aos-delay="200" style="padding-bottom: 2px; padding-top: 50px;">
            <div class="row g-5 align-items-center" style="background: #ffffffba; border-radius: 20px;">
                <?php if (!empty($info_row['gambar']) && file_exists("admin/images/info/" . $info_row['gambar'])): ?>
                    <div class="col-lg-5" data-aos="fade-up" data-aos-delay="500">
                        <div>
                            <img src="admin/images/info/<?php echo htmlspecialchars($info_row['gambar']); ?>" class="img-fluid rounded w-100" style="object-fit: cover;" alt="Image">
                        </div>
                    </div>
                    <div class="col-lg-7" data-aos="fade-down" data-aos-delay="400">
                        <div class="section-title text-start mb-5">
                            <p class="mb-4"><?= $info_row['sapaan']; ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-lg-12 text-center" data-aos="fade-down" data-aos-delay="400">
                        <div class="section-title mb-5">
                            <p class="mb-4"><?= $info_row['sapaan']; ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <!-- <div class="row">
                <div class="col-lg-9">
                    <a href="klien.php">
                        <h1 class="text-center" style="background: #ffffffba; border-radius: 20px; ">Selamat Datang di Website Kami</h1>
                    </a>
                    Tambahan: tampilkan sapaan
                    <div class="sapaan-content my-4" style="background:rgba(255, 255, 255, 0.76); border-radius: 10px; height: 100%;">
                        <?= $info_row['sapaan']; ?>
                    </div>
                </div>
            </div> -->
        </div>
        <!-- Sapaan End -->
        <!-- Video Profil Start -->
        <?php if ($embedUrl): ?>
        <div class="container-fluid video-section py-5 bg-light" data-aos="fade-up">
            <div class="container" style="max-width: 900px; margin: 0 auto;">
                <div class="section-title mb-4 text-center">
                    <h1 class="display-3 mb-3">Video Profil</h1>
                </div>
                <div class="video-container" style="position: relative; width: 100%; padding-bottom: 56.25%; height: 0; overflow: hidden; margin: 0 auto;" data-aos="fade-right" data-aos-delay="200">
                    <iframe
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                        src="<?php echo htmlspecialchars($embedUrl); ?>"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- Video Profil End -->
        <!-- About Start -->
        <div class="container-fluid about bg-light py-5">
            <div class="container py-5">
                <div class="row g-5 align-items-center" style="background: #ffffffba; border-radius: 20px;">
                    <?php if (!empty($about['img']) && file_exists("admin/images/about/" . $about['img'])): ?>
                        <div class="col-lg-5" data-aos="fade-right" data-aos-delay="500">
                            <!-- <div class="about-img pb-5 ps-5"> -->
                            <div>
                                <img src="admin/images/about/<?php echo htmlspecialchars($about['img']); ?>" class="img-fluid rounded w-100" style="object-fit: cover;" alt="Image">
                            </div>
                        </div>
                        <div class="col-lg-7" data-aos="fade-left" data-aos-delay="400">
                            <div class="section-title text-start mb-5">
                                <h4 class="display-3 mb-4 text-center">Tentang Kami</h4>
                                <?php 
                                $description = strip_tags($about['deskripsi_pendek']);
                                $words = explode(' ', $description);
                                if (count($words) > 100) {
                                    $description = implode(' ', array_slice($words, 0, 100)) .'.';
                                }
                                echo '<p>' . $description . '</p>'; 
                                ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-lg-12" data-aos="fade-down" data-aos-delay="400" >
                            <div class="section-title text-start mb-5">
                                <h4 class="display-3 mb-4 text-center">Tentang Kami</h4>
                                <?php 
                                $description = strip_tags($about['deskripsi_pendek']);
                                $words = explode(' ', $description);
                                if (count($words) > 100) {
                                    $description = implode(' ', array_slice($words, 0, 100)) .'.';
                                }
                                echo '<p>' . $description . '</p>'; 
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Sejarah Section -->
                <div class="row g-5 align-items-center mt-2" style="background: #ffffffba; border-radius: 20px;">
                    <div class="col-lg-12" data-aos="fade-up" data-aos-delay="500">
                        <div class="section-title text-start mb-5">
                            <h4 class="display-3 mb-4 text-center"><?php echo $about['history_title']; ?></h4>
                            <?php 
                            $history = strip_tags($about['sejarah_pendek']);
                            $words = explode(' ', $history);
                            if (count($words) > 200) {
                                $history = implode(' ', array_slice($words, 0, 200)) .'.';
                            }
                            echo '<p>' . $history . '</p>'; 
                            ?>
                        </div>
                    </div>
                </div>
                <div class="mt-auto text-center">
                    <a style="color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset; margin-top: 8px;" href="about.php" class="btn btn-primary rounded-pill text-white py-3 px-5">Lihat Selengkapnya</a>
                </div>
            </div>
        </div>
        <!-- About End -->
        <!-- Services Start -->
        <div class="container-fluid service py-5">
            <div class="container py-5">
                <div class="section-title mb-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="sub-style">
                        <h1 class="sub-title px-3 mb-0">Layanan</h1>
                    </div>
                </div>
                <div class="row g-4 justify-content-center" id="services-container">
                    <?php foreach ($services as $service) : ?>
                        <div class="col-md-6 col-lg-4 col-xl-3" data-aos="fade-up" data-aos-delay="500">
                            <div class="service-item rounded">
                                <div class="service-img rounded-top">
                                    <?php if (!empty($service['icon'])): ?>
                                        <img src="admin/images/services/<?php echo $service['icon']; ?>" 
                                            class="img-fluid rounded-top w-100" 
                                            alt="<?php echo $service['title']; ?>" loading="lazy">
                                    <?php else: ?>
                                        <img src="img/default-service-icon.png" 
                                            class="img-fluid rounded-top w-100" 
                                            alt="Default Icon" loading="lazy">
                                    <?php endif; ?>
                                </div>
                                <div class="service-content rounded-bottom bg-light p-4 d-flex flex-column">
                                    <h5 class="mb-4"><?php echo htmlspecialchars($service['title']); ?></h5>
                                    <!-- <p class="mb-4 short-description">
                                        <?php
                                        $short = strip_tags($service['descrip']);
                                        $short = str_replace('&nbsp;', ' ', $short);
                                        if (strlen($short) > 200) {
                                            $shortCut = substr($short, 0, 200);
                                            $short = substr($shortCut, 0, strrpos($shortCut, ' ')) . '...';
                                        }
                                        echo htmlspecialchars($short);
                                        ?>
                                    </p> -->
                                    <!-- <div class="mt-auto text-center">
                                        <ul class="price-list mb-4">
                                            <li class="d-flex justify-content-between">
                                                <small>
                                                <span>Harga:</span>
                                                <span class="text-primary font-weight-bold">Rp <?php echo number_format($service['price'], 2, ',', '.'); ?></span>
                                                </small>
                                            </li>
                                        </ul>
                                        Tombol Detail dapat diaktifkan kembali bila diperlukan
                                        <a href="detail_service.php?id=<?php echo $service['id']; ?>" class="btn btn-primary rounded-pill text-white py-2 px-4">
                                            Detail
                                        </a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="col-12 text-center" data-aos="fade-up" data-aos-delay="200">
                    </div>
                    <div class="mt-auto text-center">
                        <a style="color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset;" href="service.php" class="btn btn-primary rounded-pill text-white py-3 px-5">Lihat Semua Layanan</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Services End -->
        <!-- Client Reviews Section -->
        <div class="container-fluid client-reviews-section py-5" data-aos="fade-up" data-aos-delay="200">
            <div class="row">
                <div class="col-lg-12">
                    <a href="klien.php">
                        <h1 class="text-center" style="background: #ffffffba; border-radius: 20px; ">Klien Kami</h1>
                    </a>
                    <div class="client-reviews">
                        <?php foreach ($clients as $index => $client) : ?>
                            <div class="single-review" id="review-<?php echo $index; ?>" style="display: <?php echo $index < 4 ? 'block' : 'none'; ?>;" 
                                data-aos="<?php echo $index % 2 == 0 ? 'fade-left' : 'fade-right'; ?>" data-aos-delay="<?php echo ($index % 2 == 0 ? 200 : 400); ?>">
                                <h5 class="reviewer-name"><?php echo htmlspecialchars($client['klien']); ?></h5>
                                <div class="reviewer-thumb">
                                    <img class="avatar-lg radius-200" 
                                        src="admin/images/partnership/<?php echo htmlspecialchars($client['gambar']); ?>" 
                                        alt="Gambar <?php echo htmlspecialchars($client['klien']); ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Client Reviews Section End -->
        <!-- Team Start -->
        <div class="container-fluid team py-5">
            <div class="container py-5">
                <div class="section-title mb-5" data-aos="flip-left" data-aos-delay="100">
                    <div class="sub-style">
                        <h1 class="sub-title px-3 mb-0">Tim Kami</h1>
                    </div>
                </div>
                <!-- Gunakan align-items-stretch agar setiap .col memiliki tinggi sama -->
                <div class="row g-4 justify-content-center align-items-stretch">
                    <?php 
                        // Menampilkan data tim sesuai pagination
                        foreach ($teamsToShow as $team):
                    ?>
                    <div class="col-md-6 col-lg-6 col-xl-3" data-aos="zoom-in-up" data-aos-delay="400">
                        <div class="team-item rounded h-100 d-flex flex-column">
                            <div class="team-img rounded-top" style="background-color:rgba(255, 255, 255, 0.93);">
                                <img src="admin/images/team/<?php echo htmlspecialchars($team['img']); ?>"
                                    class="img-fluid rounded-top w-100"
                                    alt="<?php echo htmlspecialchars($team['title']); ?>"
                                    style="height: 250px; object-fit: contain;">
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
                                <p class="team-designation mb-0" style="background: #ffffffbf;"><?php echo htmlspecialchars($team['designation']); ?></p>
                                <!-- <p class="team-description mb-0" style="font-style: italic;"><?php echo htmlspecialchars($team['descrip']); ?></p> -->
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Pagination untuk bagian Tim -->
                <!-- <div class="mt-4 text-center">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php if($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php if($i == $currentPage) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; ?>
                            <?php if($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div> -->
            </div>
            <div class="mt-auto text-center">
                <a style="color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset;" href="team.php" class="btn btn-primary rounded-pill text-white py-3 px-5">Lihat Semua Tim</a>
            </div>
        </div>
        <!-- Team End -->
        <!-- Daftar Berita -->
        <div class="container-fluid berita py-5">
            <div class="section-title mb-5" data-aos="flip-left" data-aos-delay="100">
                <div class="sub-style">
                    <h1 class="sub-title px-3 mb-0">Berita</h1>
                </div>
            </div>
            <div class="row g-4" id="berita-container"></div>
            <div class="mt-auto text-center" style="padding-top: 20px;">
                <a style="color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset;" href="berita.php" class="btn btn-primary rounded-pill text-white py-3 px-5">Lihat Semua Berita</a>
            </div>
        </div>
        <!-- Script untuk Daftar Berita -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                let beritaContainer = document.getElementById("berita-container");
                const beritaList = <?php echo json_encode($beritaList); ?>;
                // Jika hanya 1 berita, tambahkan kelas agar konten berada di tengah
                if (beritaList.length === 1) {
                    beritaContainer.classList.add("d-flex", "justify-content-center");
                }
                beritaList.forEach((berita, index) => {
                    let short = berita.descrip.replace(/(<([^>]+)>)/gi, ""); // Hapus tag HTML
                    if (short.length > 200) {
                        let shortCut = short.substring(0, 200);
                        short = shortCut.substring(0, shortCut.lastIndexOf(" ")) + "...";
                    }
                    beritaContainer.innerHTML += `
                        <div class="col-md-6 col-lg-4 ${beritaList.length === 1 ? 'mx-auto' : ''} d-flex align-items-stretch" data-aos="fade-right" data-aos-delay="500">
                            <div class="card shadow-lg">
                                <img src="admin/images/blog/${berita.img}" class="card-img-top" alt="${berita.title}">
                                <div class="card-body">
                                    <h4 class="card-title">${berita.title}</h4>
                                    <p class="text-muted">
                                        <i class="fa fa-calendar-alt text-primary"></i> ${berita.date}
                                    </p>
                                    <h5 class="card-category">${berita.category}</h5>
                                    <p class="card-text">${short}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
            });
        </script>
        <!-- end berita -->
        <!-- Testimonial Start -->
        <section id="testimoni" class="py-5" data-aos="fade-up">
            <div class="container-fluid testimonial py-5" data-aos="zoom-in-down" data-aos-delay="100">
                <div class="container py-5">
                    <div class="section-title mb-5">
                        <div class="sub-style">
                            <h1 class="sub-title text-white px-3 mb-0">Testimoni</h1>
                        </div>
                        <h1 class="display-3 mb-4">Silahkan Lihat dan Berikan Testimoni Anda</h1>
                    </div>
                    <div class="testimonial-carousel owl-carousel" data-aos="flip-left">
                        <?php foreach ($testimonials as $testimonial) : ?>
                            <div class="testimonial-item">
                                <div class="testimonial-inner p-5">
                                    <div class="testimonial-inner-img mb-4">
                                        <?php if (!empty($testimonial['img'])): ?>
                                            <img src="admin/images/testimonial/<?php echo $testimonial['img']; ?>"
                                                alt="<?php echo htmlspecialchars($testimonial['title']); ?>" class="img-fluid rounded-circle">
                                        <?php else: ?>
                                            <img src="img/user.png" alt="Default Icon" class="img-fluid rounded-circle">
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-center">
                                        <h5 class="mb-2"><?php echo htmlspecialchars($testimonial['title']); ?></h5>
                                        <p class="mb-2 text-white-50"><?php echo htmlspecialchars($testimonial['designation']); ?></p>
                                        <p class="text-mutedd"><small><?php echo formatTanggalIndonesia($testimonial['date']); ?></small></p>
                                    </div>
                                    <p class="text-white fs-7"><?php echo htmlspecialchars($testimonial['descrip']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Berikan Testimoni Start -->
                    <div class="container-fluid py-5 text-center">
                        <a style="color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset;" href="testimoni.php" class="btn btn-primary rounded-pill text-white py-2 px-4">Berikan Testimoni</a>
                    </div>
                    <!-- Berikan Testimoni End -->
                </div>
            </div>
        </section>
        <!-- Testimonial End -->
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
    <script src="libr/waypoints/waypoints.min.js"></script>
    <script src="libr/owlcarousel/owl.carousel.min.js"></script>
    <!-- AOS Library -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let currentIndex = 0;
        const reviews = document.querySelectorAll('.single-review');
        const totalReviews = reviews.length;
        for (let i = 0; i < 4; i++) {
            if (reviews[i]) {
                reviews[i].style.display = 'block';
            }
        }
        setInterval(() => {
            reviews.forEach(review => {
                review.style.display = 'none';
            });
            for (let i = 0; i < 4; i++) {
                const nextReview = reviews[(currentIndex + i) % totalReviews];
                if (nextReview) {
                    nextReview.style.display = 'block';
                }
            }
            currentIndex = (currentIndex + 4) % totalReviews;
        }, 4000);
    });
    </script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>