<?php
include 'database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch data from the 'services' table
$sql = "SELECT title, descrip, img, date FROM services WHERE id = $id";
$result = $conn->query($sql);

$serviceDetail = $result->fetch_assoc();

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
    </style>
</head>

<body>

    <!-- Header -->
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5">
            <h3 class="text-white display-3 mb-4 wow fadeInDown" data-wow-delay="0.1s">Service Details</h3>
            <ol class="breadcrumb justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="service.php">Services</a></li>
                <li class="breadcrumb-item active text-primary">Detail Service</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->

    <!-- Detail Service Start -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div id="service-container">
                    <div class="card shadow-lg wow fadeInUp" data-wow-delay="0.2s">
                        <div class="service-img">
                            <img id="gambar-service" class="card-img-top" alt="Service Image">
                        </div>
                        <div class="card-body">
                            <h2 id="judul-service" class="wow fadeInUp" data-wow-delay="0.3s"></h2>
                            <p class="text-muted"><i class="fa fa-calendar-alt text-primary"></i> <span id="tanggal-service"></span></p>
                            <p id="konten-service" class="wow fadeInUp" data-wow-delay="0.5s"></p>
                            <a href="service.php" class="btn btn-primary">Back to Services</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk Ambil ID dari URL & Tampilkan Detail -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const serviceDetail = <?php echo json_encode($serviceDetail); ?>;
            
            if (serviceDetail) {
                document.getElementById("judul-service").innerText = serviceDetail.title;
                document.getElementById("tanggal-service").innerText = serviceDetail.date;
                document.getElementById("gambar-service").src = "img/" + serviceDetail.img;
                document.getElementById("konten-service").innerText = serviceDetail.descrip;
            } else {
                document.getElementById("service-container").innerHTML = `<h3 class="text-danger">Service not found!</h3>`;
            }
        });
    </script>

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