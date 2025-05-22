<?php
include 'database.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// Fungsi untuk format tanggal ke Bahasa Indonesia
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
// Fetch data dari tabel 'services'
$sql = "SELECT title, descrip, img, date FROM services WHERE id = $id";
$result = $conn->query($sql);
$serviceDetail = $result->fetch_assoc();
if ($serviceDetail) {
    $serviceDetail['date'] = formatTanggalIndonesia($serviceDetail['date']); // Ubah format tanggal
}
// Fetch data dari tabel 'service_categories' berdasarkan `core` (service_id)
$categories = [];
$sqlCategories = "SELECT * FROM service_categories WHERE core = $id";
$resultCategories = $conn->query($sqlCategories);
while ($row = $resultCategories->fetch_assoc()) {
    $categories[] = $row;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Detail Service - Lisa Mitra Mandiri</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet"> 
    <!-- Bootstrap & FontAwesome -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <!-- Animations & Carousel -->
    <link href="libr/animate/animate.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <style>
        /* Animasi Hover untuk Gambar */
        .service-img img {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border-radius: 10px;
            width: 100%;
            height: auto;
        }
        .service-img img:hover {
            transform: scale(1.05);
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.2);
        }
        /* Animasi WOW.js */
        .wow {
            visibility: hidden;
        }
        /* Animasi hover tombol */
        .btn-primary {
            transition: all 0.3s ease-in-out;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            background-color:rgb(255, 255, 255);
        } 
        /* Responsive style untuk mobile */
        @media (max-width: 768px) {
            .detail-service-container {
                padding: 20px;
            }
            .detail-service-card {
                margin: 0;
                border: none;
                box-shadow: none;
            }
            .detail-service-img img {
                border-radius: 0;
            }
            .detail-service-content h2 {
                font-size: 24px;
            }
            .detail-service-content p {
                font-size: 16px;
            }
            .detail-service-back-btn {
                display: block;
                width: 100%;
                text-align: center;
                margin-top: 20px;
            }
        }
        .detail-service-content {
            background: #f1f1f1;
        }
        .text-muted {
            color: #750000 !important;
        }
        .text-white {
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .sub-title{
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .fa-globe:before {
            color: #005aff;
        }
        .detail-service-img img {
            max-width: 100%;
            max-height: 100%;
            width: 100%;
        }
        .card-img-top {
            margin-top: auto;
        }
        .list-group-item {
            border: 3px solid rgb(0 0 0);
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
        $pageTitle = "Detail Layanan";
        include 'includes/header.php';
        ?>
        <!-- Header End -->
        <!-- Detail Service Start -->
        <div class="container detail-service-container">
            <div class="row">
                <div class="col-lg-8 mx-auto" style="width: 100%;">
                    <div id="detail-service-container">
                        <div class="card detail-service-card">
                            <div class="detail-service-img">
                                <img id="detail-gambar-service" class="card-img-top" alt="Service Image">
                            </div>
                            <div class="card-body detail-service-content">
                                <h1 id="detail-judul-service"></h1>
                                <p class="text-muted">
                                    <i class="fa fa-calendar-alt text-primary"></i> 
                                    <span id="detail-tanggal-service"></span>
                                </p>
                                <p id="detail-konten-service"></p>
                                <!-- Tampilkan kategori layanan -->
                                <?php if (!empty($categories)): ?>
                                    <div class="mt-4">
                                        <h3>Jenis Layanan :</h3>
                                        <ul class="list-group">
                                            <?php foreach ($categories as $category): ?>
                                                <li class="list-group-item">
                                                    <i class="fas fa-globe me-4 animate__animated animate__rotateIn service-icon" style="font-size: 27px;"> <?php echo htmlspecialchars($category['name']); ?></i>
                                                    <p><?php echo $category['description']; // Render HTML tags safely ?></p>
                                                    <?php if (!is_null($category['offline_price'])): ?>
                                                        <p style="font-size:20px; color:red;"><i class="fas fa-money-bill-wave me-2"></i><strong>Harga Offline:</strong>
                                                        Rp <?php echo number_format($category['offline_price'],2,',','.'); ?></p>
                                                    <?php endif; ?>
                                                    <?php if (!is_null($category['online_price'])): ?>
                                                        <p style="font-size:20px; color:blue;"><i class="fas fa-tags me-2"></i><strong>Harga Online:</strong>
                                                        Rp <?php echo number_format($category['online_price'],2,',','.'); ?></p>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                <div class="d-flex justify-content-center gap-2">
                                    <a style="color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset;" href="service.php" class="btn btn-primary rounded-pill text-white py-2 px-4">Kembali Ke Layanan</a>
                                    <?php if (!empty($social['whatsapp'])): ?>
                                        <a style="color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset;" href="https://wa.me/<?php echo $social['whatsapp']; ?>" class="btn btn-primary rounded-pill text-white py-2 px-4">Hubungi Kami</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Detail Service End -->
        <!-- Script untuk Ambil ID dari URL & Tampilkan Detail -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const serviceDetail = <?php echo json_encode($serviceDetail); ?>;
                if (serviceDetail) {
                    document.getElementById("detail-judul-service").innerText = serviceDetail.title;
                    document.getElementById("detail-tanggal-service").innerText = serviceDetail.date;
                    if (serviceDetail.img) {
                        document.getElementById("detail-gambar-service").src = "admin/images/services/" + serviceDetail.img;
                    } else {
                        document.getElementById("detail-gambar-service").src = "img/default-service-icon.png";
                    }
                    document.getElementById("detail-konten-service").innerHTML = serviceDetail.descrip;
                } else {
                    document.getElementById("detail-service-container").innerHTML = `<h3 class="text-danger">Service not found!</h3>`;
                }
            });
        </script>
        <!-- Detail Service End -->
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
    <!-- Inisialisasi WOW.js -->
    <script>
        new WOW().init();
    </script>
</body>
</html>