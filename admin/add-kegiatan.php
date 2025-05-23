<?php
include 'conn.php';
include 'auth.php';

$a = 5;
$msg = "";
$msgClass = "";

// Ambil flash message dari session jika ada
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    $msgClass = $_SESSION['msgClass'];
    unset($_SESSION['msg'], $_SESSION['msgClass']);
}

date_default_timezone_set('Asia/Jakarta');
$today = date("Y-m-d H:i:s");

// Ambil ID dari parameter GET untuk edit
$edit = isset($_GET['edit']) ? mysqli_real_escape_string($con, $_GET['edit']) : '';
$roww = [];

// Jika ada parameter edit, ambil data dari database
if ($edit) {
    $resultt = mysqli_query($con, "SELECT * FROM kegiatan WHERE id = '$edit'");
    $roww = mysqli_fetch_array($resultt);
}

// Jika tidak ada data, set nilai default
if (!$roww) {
    $roww = [
        "nama_kegiatan" => "",
        "deskripsi"     => "",
        "url"           => "",
    ];
}

// Proses simpan data
if (isset($_POST['publise'])) {
    $nama_kegiatan = mysqli_real_escape_string($con, $_POST['nama_kegiatan']);
    $deskripsi     = mysqli_real_escape_string($con, $_POST['deskripsi']);
    $url           = mysqli_real_escape_string($con, $_POST['url']);

    if ($edit == '') {
        // Tambah data baru
        $insertdata = mysqli_query($con, "INSERT INTO kegiatan (nama_kegiatan, deskripsi, url, created_at) 
            VALUES ('$nama_kegiatan', '$deskripsi', '$url', '$today')");
        if ($insertdata) {
            $_SESSION['msg'] = "Kegiatan berhasil ditambahkan.";
            $_SESSION['msgClass'] = "alert-success bg-success text-white";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat menambahkan kegiatan.";
            $_SESSION['msgClass'] = "alert-danger bg-danger text-white";
        }
        header("Location: add-kegiatan.php");
        exit;
    } else {
        // Update data yang sudah ada
        $updatedata = mysqli_query($con, "UPDATE kegiatan 
            SET nama_kegiatan = '$nama_kegiatan', deskripsi = '$deskripsi', url = '$url', created_at = '$today'
            WHERE id = '$edit'");
        if ($updatedata) {
            $_SESSION['msg'] = "Kegiatan berhasil diperbarui.";
            $_SESSION['msgClass'] = "alert-success bg-success text-white";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat memperbarui kegiatan.";
            $_SESSION['msgClass'] = "alert-danger bg-danger text-white";
        }
        header("Location: add-kegiatan.php?edit=$edit");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include "title.php"; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <?php include '../includes/logo.php'; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include "topbar.php"; ?>
    <?php include "sidebar.php"; ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><?php echo ($edit) ? 'Edit Kegiatan' : 'Tambah Kegiatan'; ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <a href="view-kegiatan.php" class="btn btn-success">
                            <i class="fa fa-eye" aria-hidden="true"></i> Lihat Kegiatan
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <?php if (!empty($msg)): ?>
                    <div style="max-width: 350px;">
                        <div class="alert <?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
                            <?php echo $msg; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>        
                <div class="row">
                    <div class="col-md-8">
                        <form class="needs-validation" novalidate action="" method="post">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <div class="form-group">
                                        <label for="validationNamaKegiatan" class="form-label">Nama Kegiatan</label>
                                        <input name="nama_kegiatan" type="text" class="form-control" id="validationNamaKegiatan"
                                               value="<?php echo htmlspecialchars($roww["nama_kegiatan"]); ?>" 
                                               placeholder="Masukkan nama kegiatan..." required>
                                        <div class="invalid-feedback">
                                            Harap masukkan nama kegiatan yang valid.
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pad">
                                    <label for="validationDeskripsi" class="form-label">Deskripsi Singkat Maksimal 50 Kata</label>
                                    <div class="mb-3">
                                        <textarea name="deskripsi" class="form-control" id="validationDeskripsi" 
                                                placeholder="Deskripsi" rows="5" required oninput="countWords(this)"><?php echo htmlspecialchars($roww["deskripsi"]); ?></textarea>
                                        <div class="invalid-feedback">
                                            Harap masukkan deskripsi.
                                            <span class="text-danger"> (Maksimal 50 kata)</span>
                                        </div>
                                        <small id="wordCountInfo" class="form-text text-muted">0/50 kata</small>
                                    </div>
                                </div>
                                <div class="card-header">   
                                    <div class="form-group">
                                        <label for="validationURL" class="form-label">Link Google Form</label>
                                        <input name="url" type="url" class="form-control" id="validationURL"
                                               value="<?php echo htmlspecialchars($roww["url"]); ?>" 
                                               placeholder="Masukkan Link Google Form..." required>
                                    </div>
                                </div>
                                <div class="card-header">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button type="submit" name="publise" class="btn btn-primary btn-lg">
                                                    <?php echo ($edit) ? 'Perbarui' : 'Tambahkan'; ?>
                                                </button>
                                                <a href="view-kegiatan.php" class="btn btn-danger btn-lg">Kembali</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include "footer.php"; ?>
</div>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="plugins/summernote/summernote-bs4.min.js"></script>
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
</script>
<script>
    $(function () {
        $('.textarea').summernote();
    });
</script>
<script>
function countWords(textarea) {
    const maxWords = 50;
    let words = textarea.value.trim().split(/\s+/).filter(Boolean);
    let wordCount = words.length;
    if (wordCount > maxWords) {
        textarea.value = words.slice(0, maxWords).join(" ");
        wordCount = maxWords;
    }
    document.getElementById('wordCountInfo').innerText = wordCount + "/50 kata";
}
// Inisialisasi saat halaman dimuat (untuk edit)
document.addEventListener("DOMContentLoaded", function() {
    const textarea = document.getElementById('validationDeskripsi');
    if (textarea) countWords(textarea);
});
</script>
</body>
</html>