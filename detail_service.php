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
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
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
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5">
            <h3 class="text-white display-3 mb-4 wow fadeInDown" data-wow-delay="0.1s">Detail Layanan</h3>
            <ol class="breadcrumb justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="service.php">Layanan</a></li>
                <li class="breadcrumb-item active text-primary">Detail Layanan</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->
    <!-- Detail Service Start -->
    <div class="container detail-service-container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
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
                            <a href="service.php" class="detail-service-back-btn">Kembali Ke Layanan</a>
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
                document.getElementById("detail-tanggal-service").innerText = serviceDetail.date; // Tanggal sudah dalam format Indonesia
                document.getElementById("detail-gambar-service").src = "admin/images/services/" + serviceDetail.img;
                // Gunakan innerHTML agar bisa mendukung format HTML dalam deskripsi
                document.getElementById("detail-konten-service").innerHTML = serviceDetail.descrip;
            } else {
                document.getElementById("detail-service-container").innerHTML = `<h3 class="text-danger">Service not found!</h3>`;
            }
        });
    </script>
    <!-- Detail Service End -->
    <!-- Footer -->
    <div class="container-fluid footer py-5 text-center">
        <p>&copy; 2025 Lisa Mitra Mandiri. All Rights Reserved.</p>
    </div>
    <!-- Footer End -->
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <!-- Inisialisasi WOW.js -->
    <script>
        new WOW().init();
    </script>
</body>
</html>