<?php
include 'database.php';
// Fetch data from the 'blog' table
$sql = "SELECT id, title, category, descrip, img, date, url FROM blog";
$result = $conn->query($sql);
$beritaList = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $beritaList[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lisa Mitra Mandiri</title>
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
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
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
    $pageTitle = "Berita";
    include 'includes/header.php';
    ?>
    <!-- Header End -->
    <!-- Daftar Berita -->
    <div class="container py-5">
        <h4 class="text-center mb-4 fade-in" style="font-size: 300%;">Daftar Berita</h4>
        <div class="row g-4" id="berita-container"></div>
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
                // Jika hanya satu berita, kita bisa menambahkan 'mx-auto' agar card-nya benar-benar center
                // (bisa dihilangkan jika sudah cukup dengan d-flex justify-content-center pada container)
                beritaContainer.innerHTML += `
                    <div class="col-md-6 col-lg-4 ${beritaList.length === 1 ? 'mx-auto' : ''} d-flex align-items-stretch wow fadeInUp" data-wow-delay="${index * 0.2}s">
                        <div class="card shadow-lg">
                            <img src="admin/images/blog/${berita.img}" class="card-img-top" alt="${berita.title}">
                            <div class="card-body">
                                <h4 class="card-title">${berita.title}</h4>
                                <p class="text-muted">
                                    <i class="fa fa-calendar-alt text-primary"></i> ${berita.date}
                                </p>
                                <h5 class="card-category">${berita.category}</h5>
                                <p class="card-text">${short}</p>
                                <a href="detail_berita.php?id=${berita.id}" class="btn btn-primary">Detail</a>
                            </div>
                        </div>
                    </div>
                `;
            });
        });
    </script>
    <!-- end berita -->
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
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
</body>
</html>