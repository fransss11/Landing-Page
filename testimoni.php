<?php
include 'database.php';

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for form submission
$msg = "";
$msgClass = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = mysqli_real_escape_string($conn, $_POST['name']);
    $designation = mysqli_real_escape_string($conn, $_POST['designation']);
    $comments    = mysqli_real_escape_string($conn, $_POST['comments']);
    $img         = "";

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $maxFileSize = 10 * 1024 * 1024; // 10MB
        if ($_FILES['image']['size'] > $maxFileSize) {
            $msg      = "Ukuran file harus kurang dari 10MB.";
            $msgClass = "alert-danger";
        } else {
            $img     = rand() . '_' . $_FILES['image']['name'];
            $temp    = $_FILES['image']['tmp_name'];
            $folder  = "admin/images/testimonial/" . $img;
            move_uploaded_file($temp, $folder);
        }
    }

    // Insert testimonial if no upload error
    if (empty($msg)) {
        $sql = "INSERT INTO testimonials 
                (title, designation, descrip, img, date, status) 
                VALUES 
                ('$name', '$designation', '$comments', '$img', NOW(), '0')";
        if ($conn->query($sql) === TRUE) {
            $msg      = "Testimoni berhasil dikirim.";
            $msgClass = "alert-success";
        } else {
            $msg      = "Terjadi kesalahan saat mengirim testimoni.";
            $msgClass = "alert-danger";
        }
    }
}

// Fetch all kegiatan with their respective Google Form links
$kegiatanQuery = "SELECT * FROM kegiatan ORDER BY created_at DESC";
$kegiatanResult = $conn->query($kegiatanQuery);
$kegiatan = [];
if ($kegiatanResult && $kegiatanResult->num_rows > 0) {
    while ($row = $kegiatanResult->fetch_assoc()) {
        $kegiatan[] = $row;
    }
}

// Check if there are any kegiatan without a URL
$missingUrl = array_filter($kegiatan, function($k) {
    return empty($k['url']);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lisa Mitra Mandiri</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS Libraries -->
    <link href="libr/animate/animate.min.css" rel="stylesheet">
    <link href="libr/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <style>
        .form-label {
            color: #333;
            font-weight: 500;
        }
        .card-title {
            min-height: 80px;
            color: #2b5c3d;
            font-weight: 600;
        }
        .section-title {
            display: flex;
            background: linear-gradient(to right, #3a7c5e, #519872);
            color: white;
            padding: 15px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            justify-content: space-around;
            font-size: 35px;
        }
        .section-divider {
            height: 3px;
            background: linear-gradient(to right, #ffffff, #3a7c5e, #ffffff);
            margin: 40px auto;
            width: 80%;
        }
        .kegiatan-card {
            transition: all 0.3s;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            height: 100%;
        }
        .kegiatan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .kegiatan-card .card-body {
            padding: 1.5rem;
        }
        .form-container {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .btn-testimoni {
            background: linear-gradient(to right, #3a7c5e, #61b08b);
            border: none;
            color: white;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 30px;
            transition: all 0.3s;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn-testimoni:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .btn-back {
            background: #6c757d;
            border: none;
            color: white;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 30px;
            transition: all 0.3s;
        }
        .team {
            background: linear-gradient(135deg, #f5f7fa, #e9ecef);
            padding-top: 30px;
            padding-bottom: 50px;
        }
        #kegiatanSelect {
            max-width: 500px;
            margin: 0 auto;
            border-radius: 30px;
            padding: 12px 20px;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ced4da;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3a7c5e;
            box-shadow: 0 0 0 0.25rem rgba(58, 124, 94, 0.25);
        }
        .date-badge {
            background-color: #3a7c5e;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: inline-block;
        }
        .kegiatan-deskripsi {
            color: #444;
            font-size: 1.05rem;
            min-height: 90px;
            height: 100%;
            margin-bottom: 15px;
            line-height: 1.7;
            letter-spacing: 0.01em;
            background: #f8faf9;
            border-radius: 8px;
            padding: 12px 16px;
            box-shadow: 0 2px 8px rgba(60, 124, 94, 0.06);
        }
    </style>
</head>
<body>
    <?php include 'includes/spinner.php'; ?>
    <?php include 'includes/topbar.php'; ?>
    <?php include 'includes/navbar.php'; ?>

    <?php
    $pageTitle = "Berikan Testimoni";
    include 'includes/header.php';
    ?>

    <div class="container-fluid testimoni">
        <!-- Kegiatan Section -->
        <div class="container py-5" id="kegiatan">
            <h1 class="text-center section-title wow fadeInUp" data-wow-delay="0.1s">Kegiatan Kami</h1>
            <div class="row">
                <?php if (count($kegiatan) > 0): ?>
                    <?php foreach ($kegiatan as $k): ?>
                        <div class="col-md-4 mb-4 wow fadeInUp" data-wow-delay="0.<?= $loop ?? 2 ?>s">
                            <div class="card kegiatan-card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($k['nama_kegiatan']) ?></h5>
                                    <p class="kegiatan-deskripsi">
                                        <?php
                                            $words = explode(' ', strip_tags($k['deskripsi']));
                                            $short = implode(' ', array_slice($words, 0, 50));
                                            echo nl2br(htmlspecialchars($short));
                                            if (count($words) > 50) echo '...';
                                        ?>
                                    </p>
                                    <div class="date-badge">
                                        <i class="far fa-calendar-alt me-2"></i><?= date('d-m-Y', strtotime($k['created_at'])) ?>
                                    </div>
                                    <p><i class="far fa-clock me-2"></i> <?= date('H:i', strtotime($k['created_at'])) ?> WIB</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>Belum ada kegiatan.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-center mt-5 wow fadeInUp" data-wow-delay="0.3s">
                <label for="kegiatanSelect" class="form-label">Pilih Kegiatan untuk Memberikan Testimoni</label>
                <form id="kegiatanForm" method="GET" class="mt-3">
                    <select id="kegiatanSelect" name="kegiatan" class="form-select mb-4" required onchange="updateFormAction()">
                        <option value="" disabled selected>-- Pilih Kegiatan --</option>
                        <?php foreach ($kegiatan as $k): ?>
                            <option value="<?= htmlspecialchars($k['url']) ?>">
                                <?= htmlspecialchars($k['nama_kegiatan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-testimoni">Berikan Testimoni Kegiatan</button>
                </form>
            </div>
        </div>
        <div class="section-divider"></div>
        <!-- Form Testimoni Start -->
        <div class="container py-5" id="testimoni">
            <h1 class="text-center section-title wow fadeInUp" data-wow-delay="0.1s">Berikan Testimoni Website</h1>

            <?php if (!empty($msg)): ?>
                <div class="alert <?= $msgClass ?> alert-dismissible fade show wow fadeInUp" data-wow-delay="0.2s" role="alert">
                    <?= $msg ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="form-container">
                        <form action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label for="name" class="form-label"><i class="fas fa-user me-2"></i>Nama</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Mohon isi nama Anda.</div>
                            </div>
                            <div class="mb-4">
                                <label for="designation" class="form-label"><i class="fas fa-briefcase me-2"></i>Jabatan</label>
                                <input type="text" class="form-control" id="designation" name="designation">
                                <div class="invalid-feedback">Mohon isi jabatan Anda.</div>
                            </div>
                            <div class="mb-4">
                                <label for="comments" class="form-label"><i class="fas fa-comment-alt me-2"></i>Komentar</label>
                                <textarea class="form-control" id="comments" name="comments" rows="5" required></textarea>
                                <div class="invalid-feedback">Mohon isi komentar Anda.</div>
                            </div>
                            <div class="mb-4">
                                <label for="image" class="form-label"><i class="fas fa-image me-2"></i>Unggah Gambar (Opsional, Maksimal 500KB)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-testimoni me-2">Kirim Testimoni</button>
                                <a href="index.php#testimoni" class="btn btn-back">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/copyright.php'; ?>
    <?php include 'includes/back_to_top.php'; ?>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="libr/wow/wow.min.js"></script>
    <script src="libr/easing/easing.min.js"></script>
    <script src="libr/waypoints/waypoints.min.js"></script>
    <script src="libr/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
    <script>
        // Initialize WOW.js for animations
        new WOW().init();
        
        (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
            });
        })();

        function updateFormAction() {
            const kegiatanSelect = document.getElementById('kegiatanSelect');
            const kegiatanForm = document.getElementById('kegiatanForm');
            kegiatanForm.action = kegiatanSelect.value;
        }
    </script>
</body>
</html>
