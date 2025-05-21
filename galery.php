<?php
include 'database.php';
// Fetch data from the 'media' table
$sql = "SELECT id, galery, foto, kategori, uploaded_on FROM media WHERE status = '1'";
$result = $conn->query($sql);
$images = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $images[$row['kategori']][] = $row;
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
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <style>
        .text-white {
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .sub-title{
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .kategori-header:hover {
            color:rgb(255, 255, 255);
            cursor: pointer;
            background-color: #000000 !important;
        }
        .client-card {
            max-width: 100%;
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
        $pageTitle = "Galeri";
        include 'includes/header.php';
        ?>
        <!-- Header End -->
        <!-- Gallery Start -->
        <div class="container-fluid about team py-5">
            <div class="container py-5">
                <div class="section-title mb-5">
                    <div class="sub-style">
                        <h1 class="sub-title px-9 mb-0">Galeri Kami</h1>
                    </div>
                </div>
                <!-- Petunjuk penggunaan galeri -->
                <div class="text-center mb-4" data-aos="fade-up" data-aos-delay="500">
                    <p class="text-mutedd" style="font-size: 18px;background-color: #eeeeeec7; border-radius: 20px;">
                        Klik pada <strong>nama kegiatan</strong> untuk membuka atau menutup gambar berdasarkan kegiatan, atau klik tombol <strong>"Lihat Semua Gambar"</strong> untuk membuka/menutup semua gambar sekaligus.
                    </p>
                </div>
                <!-- Tombol Lihat Semua Gambar (posisi diperbaiki) -->
                <div class="text-center mb-4" data-aos="fade-up" style=" margin-top: 2%;">
                    <button style="color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset;" class="btn btn-primary" id="lihat-semua" style="color: black;">Lihat Semua Gambar</button>
                </div>
                <?php foreach ($images as $kategori => $kategori_images): ?>
                    <div class="row text-center mb-4" data-aos="fade-down" data-aos-delay="500">
                        <h3 class="kategori-header" style="background:rgb(255, 255, 255); cursor:pointer; border-radius: 25px; width: 100%; " data-kategori="<?= htmlspecialchars($kategori); ?>">
                            <?= htmlspecialchars($kategori); ?>
                        </h3>
                    </div>
                    <div class="row kategori-content" id="kategori-<?= htmlspecialchars($kategori); ?>" style="display: none;">
                        <?php foreach ($kategori_images as $index => $image): ?>
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="client-card">
                                    <a>
                                        <img src="admin/uploads/<?= $image['foto']; ?>" class="img-fluid" alt="<?= $image['galery']; ?>">
                                    </a>
                                    <h6 class="mt-2"><?= $image['galery']; ?></h6>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Gallery End -->
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
    <script src="libr/easing/easing.min.js"></script>
    <script src="libr/waypoints/waypoints.min.js"></script>
    <script src="libr/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <!-- Script JS untuk Toggle Kategori dan Tombol Lihat Semua -->
    <script>
        document.querySelectorAll('.kategori-header').forEach(function(header) {
            header.addEventListener('click', function() {
                const kategori = this.getAttribute('data-kategori');
                const konten = document.getElementById('kategori-' + kategori);
                const items = konten.querySelectorAll('.col-md-3');
                const itemsPerPage = 8;
                let currentPage = 1;

                function renderPage(page) {
                    items.forEach((item, index) => {
                        item.style.display = (index >= (page - 1) * itemsPerPage && index < page * itemsPerPage) ? 'block' : 'none';
                    });
                }

                if (konten.style.display === 'none' || konten.style.display === '') {
                    konten.style.display = 'flex';
                    konten.style.flexWrap = 'wrap';
                    renderPage(currentPage);

                    let pagination = konten.querySelector('.pagination');
                    if (!pagination) {
                        pagination = document.createElement('div');
                        pagination.className = 'pagination';
                        pagination.style.marginTop = '20px';
                        pagination.style.paddingBottom = '10px';
                        pagination.style.textAlign = 'center';
                        pagination.style.display = 'flex';
                        pagination.style.justifyContent = 'center';
                        pagination.style.width = '100%';
                        konten.appendChild(pagination);

                        const totalPages = Math.ceil(items.length / itemsPerPage);
                        for (let i = 1; i <= totalPages; i++) {
                            const pageButton = document.createElement('button');
                            pageButton.textContent = i;
                            pageButton.className = 'btn';
                            pageButton.style.margin = '0 5px';
                            pageButton.style.padding = '5px 10px';
                            pageButton.style.border = '1px solid #ccc';
                            pageButton.style.borderRadius = '5px';
                            pageButton.style.backgroundColor = i === currentPage ? '#007bff' : '#fff';
                            pageButton.style.color = i === currentPage ? '#fff' : '#000';
                            pageButton.addEventListener('click', function() {
                                currentPage = i;
                                renderPage(currentPage);
                                pagination.querySelectorAll('button').forEach(btn => {
                                    btn.style.backgroundColor = '#fff';
                                    btn.style.color = '#000';
                                });
                                pageButton.style.backgroundColor = '#007bff';
                                pageButton.style.color = '#fff';
                            });
                            pagination.appendChild(pageButton);
                        }
                    }

                    this.classList.add('active');
                } else {
                    konten.style.display = 'none';
                    this.classList.remove('active');
                }
            });
        });
        let semuaTerbuka = false;
        document.getElementById('lihat-semua').addEventListener('click', function() {
            semuaTerbuka = !semuaTerbuka;
            document.querySelectorAll('.kategori-content').forEach(function(konten) {
                const items = konten.querySelectorAll('.col-md-3');
                const pagination = konten.querySelector('.pagination');

                konten.style.display = semuaTerbuka ? 'flex' : 'none';
                items.forEach(item => {
                    item.style.display = semuaTerbuka ? 'block' : 'none';
                });

                if (pagination) {
                    pagination.style.display = semuaTerbuka ? 'none' : 'block';
                }
            });
            this.textContent = semuaTerbuka ? 'Tutup Semua Gambar' : 'Lihat Semua Gambar';
        });
    </script>
</body>
</html>