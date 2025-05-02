<?php
include 'database.php';
// Fetch data from the 'services' table
$sql = "SELECT * FROM services ORDER BY id DESC";
$result = $conn->query($sql);
$services = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}
// Fetch data from the 'testimonials' table
$sql = "SELECT title, designation, descrip, img, date FROM testimonials";
$result = $conn->query($sql);
$testimonials = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}
// Fetch WhatsApp number
$sql = "SELECT whatsapp FROM social";
$result = $conn->query($sql);
$social = $result->fetch_assoc();
$conn->close();

function formatTanggalIndonesia($tanggal) {
    $bulanIndo = ["Januari","Februari","Maret","April","Mei","Juni",
                  "Juli","Agustus","September","Oktober","November","Desember"];
    $hariIndo  = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
    $dateObj   = strtotime($tanggal);
    $hari      = $hariIndo[date('w', $dateObj)];
    $tanggalNum= date('j', $dateObj);
    $bulan     = $bulanIndo[date('n', $dateObj)-1];
    $tahun     = date('Y', $dateObj);
    return "$hari, $tanggalNum $bulan $tahun";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lisa Mitra Mandiri</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Libraries & Styles -->
    <link href="libr/animate/animate.min.css" rel="stylesheet">
    <link href="libr/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <style>
      /* tambahan spacing untuk list */
      .service-list li { font-size: 1rem; }
      .service-list li i { font-size: 0.75rem; vertical-align: middle; }
    </style>
</head>
<body>
    <div class="bckg">
        <!-- Spinner, Topbar, Navbar -->
        <?php include 'includes/spinner.php'; ?>
        <?php include 'includes/topbar.php'; ?>
        <?php include 'includes/navbar.php'; ?>

        <!-- Header -->
        <?php
        $pageTitle = "Layanan";
        include 'includes/header.php';
        ?>

        <!-- Services Start -->
        <div class="container-fluid service bg-light py-5">
            <div class="container py-5">
                <div class="section-title mb-5">
                    <div class="sub-style">
                        <h1 class="sub-title px-3 mb-0">Layanan</h1>
                    </div>
                </div>

                <!-- Tampilkan 1 Service Pertama -->
                <?php if (!empty($services)): ?>
                <?php $first = $services[0]; ?>
                <div class="row mb-5 justify-content-center" data-aos="zoom-in" data-aos-delay="300">
                    <div class="col-md-6 col-lg-4" style="width: 100%;">
                        <div class="service-item rounded">
                            <div class="service-img rounded-top">
                                <?php if (!empty($first['img'])): ?>
                                <img src="admin/images/services/<?php echo $first['img']; ?>"
                                     class="img-fluid rounded-top w-100"
                                     alt="<?php echo $first['title']; ?>" loading="lazy">
                                <?php else: ?>
                                <img src="img/default-service-icon.png"
                                     class="img-fluid rounded-top w-100"
                                     alt="Default Icon" loading="lazy">
                                <?php endif; ?>
                            </div>
                            <div class="service-content rounded-bottom bg-light p-4 d-flex flex-column">
                                <h5 class="mb-4"><?php echo $first['title']; ?></h5>
                                <p class="mb-4">
                                  <?php
                                  // potong deskripsi jika terlalu panjang
                                  $short = strip_tags($first['descrip']);
                                  $short = str_replace('&nbsp;', ' ', $short);
                                  if (strlen($short) > 200) {
                                      $cut = substr($short, 0, 200);
                                      $short = substr($cut, 0, strrpos($cut, ' ')) . '...';
                                  }
                                  echo htmlspecialchars($short);
                                  ?>
                                </p>
                                <div class="mt-auto text-center">
                                    <ul class="price-list mb-4">
                                        <li class="d-flex justify-content-between">
                                            <small>
                                              <span>Harga:</span>
                                              <span class="text-primary font-weight-bold">
                                                Rp <?php echo number_format($first['price'], 2, ',', '.'); ?>
                                              </span>
                                            </small>
                                        </li>
                                    </ul>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="detail_service.php?id=<?php echo $first['id']; ?>"
                                           class="btn btn-primary rounded-pill text-white py-2 px-4">
                                           Detail
                                        </a>
                                        <?php if (!empty($social['whatsapp'])): ?>
                                        <a href="https://wa.me/<?php echo $social['whatsapp']; ?>"
                                           class="btn btn-primary rounded-pill text-white py-2 px-4">
                                           Hubungi Kami
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Daftar Layanan di Bawah -->
                <div class="row">
                    <div class="col-12">
                        <div>
                            <h1>Layanan Lainnya :</h1>
                        </div>
                        <ul class="list-unstyled service-list">
                            <?php foreach ($services as $i => $svc): ?>
                                <?php if ($i === 0) continue; ?>
                                <li class="mb-3">
                                    <i style="font-size: 20px;" class="fas fa-globe text-danger me-4 animate__animated animate__rotateIn service-icon"></i>
                                    <a href="detail_service.php?id=<?php echo $svc['id']; ?>"
                                    class="text-primary">
                                    <?php echo ($i + 1) . '. ' . $svc['title']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <!-- Services End -->
        <!-- Footer -->
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
      // Reveal on scroll for testimonial
      function revealOnScroll() {
          var els = document.querySelectorAll('.hidden, .visible');
          var winH = window.innerHeight;
          els.forEach(function(el) {
            var top = el.getBoundingClientRect().top;
            if (top < winH - 150) el.classList.add('visible'), el.classList.remove('hidden');
            else el.classList.add('hidden'), el.classList.remove('visible');
          });
          if (window.scrollY === 0) {
            els.forEach(el => el.classList.add('hidden'));
          }
      }
      window.addEventListener('scroll', revealOnScroll);
      window.addEventListener('load', revealOnScroll);
    </script>
</body>
</html>
