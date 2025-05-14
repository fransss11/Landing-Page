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
        $maxFileSize = 500 * 1024; // 500KB
        if ($_FILES['image']['size'] > $maxFileSize) {
            $msg      = "Ukuran file harus kurang dari 500KB.";
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
    <link href="css/style.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <style>
        .form-label {
            color: black;
        }
        .card-title {
            min-height: 80px;
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

    <div class="container-fluid team bg-light">
        <!-- Kegiatan Section -->
        <div class="container py-5" id="kegiatan">
            <h1 style="background-color: #eeeeeec7; border-radius: 20px;" class="text-center mb-4">Kegiatan Kami</h1>
            <div class="row">
                <?php if (count($kegiatan) > 0): ?>
                    <?php foreach ($kegiatan as $k): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($k['nama_kegiatan']) ?></h5>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($k['deskripsi'])) ?></p>
                                    <p><strong>Tanggal:</strong> <?= date('d-m-Y', strtotime($k['created_at'])) ?></p>
                                    <p><strong>Jam:</strong> <?= date('H:i:s', strtotime($k['created_at'])) ?></p>
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

            <div class="text-center mt-4">
                <form id="kegiatanForm" method="GET">
                    <label for="kegiatanSelect" class="form-label">Pilih Kegiatan</label>
                    <select id="kegiatanSelect" name="kegiatan" class="form-select mb-3" required onchange="updateFormAction()">
                        <option value="" disabled selected>-- Pilih Kegiatan --</option>
                        <?php foreach ($kegiatan as $k): ?>
                            <option value="<?= htmlspecialchars($k['url']) ?>">
                                <?= htmlspecialchars($k['nama_kegiatan']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-success btn-lg">Berikan Testimoni</button>
                </form>
            </div>
        </div>

        <!-- Form Testimoni Start -->
        <div class="container py-5" id="testimoni">
            <h1 style="background-color: #eeeeeec7; border-radius: 20px;" class="text-center mb-4">Berikan Testimoni Anda Untuk Website Ini</h1>

            <?php if (!empty($msg)): ?>
                <div class="alert <?= $msgClass ?>" role="alert">
                    <?= $msg ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">Mohon isi nama Anda.</div>
                </div>
                <div class="mb-3">
                    <label for="designation" class="form-label">Jabatan</label>
                    <input type="text" class="form-control" id="designation" name="designation" required>
                    <div class="invalid-feedback">Mohon isi jabatan Anda.</div>
                </div>
                <div class="mb-3">
                    <label for="comments" class="form-label">Komentar</label>
                    <textarea class="form-control" id="comments" name="comments" rows="5" required></textarea>
                    <div class="invalid-feedback">Mohon isi komentar Anda.</div>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Unggah Gambar (Opsional, Maksimal 500KB)</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <button style="color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset;" type="submit" class="btn btn-primary">Kirim Testimoni</button>
                <a href="index.php#testimoni" class="btn btn-secondary">Kembali</a>
            </form>
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
