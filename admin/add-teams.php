<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';
date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d H:i:s"); // Format tanggal standar
// Cek apakah parameter 'edit' tersedia dan valid
$edit = (isset($_GET['edit']) && intval($_GET['edit']) > 0) ? intval($_GET['edit']) : 0;
// Jika mode edit, ambil data dari database
if ($edit > 0) {
    $resultt = mysqli_query($con, "SELECT * FROM teams WHERE id = '$edit'");
    $roww = mysqli_fetch_array($resultt);
} else {
    $roww = []; // Untuk mode insert
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title       = mysqli_real_escape_string($con, $_POST['title']);
    $designation = mysqli_real_escape_string($con, $_POST['designation']);
    $descrip     = mysqli_real_escape_string($con, $_POST['Deskripsi']);
    $facebook    = mysqli_real_escape_string($con, $_POST['facebook']);
    $twitter     = mysqli_real_escape_string($con, $_POST['twitter']);
    $instagram   = mysqli_real_escape_string($con, $_POST['instagram']);
    $linkedin    = mysqli_real_escape_string($con, $_POST['linkedin']);
    $whatsapp    = mysqli_real_escape_string($con, $_POST['whatsapp']);
    $url         = mysqli_real_escape_string($con, $_POST['url']);
    // Jika ada data lama (mode edit), gunakan gambarnya; jika tidak, kosongkan
    $lis_img = isset($roww["img"]) ? $roww["img"] : '';
    if (!empty($_FILES['lis_img']['name'])) {
        $img_name = $_FILES['lis_img']['name'];
        $img_tmp  = $_FILES['lis_img']['tmp_name'];
        $img_size = $_FILES['lis_img']['size'];
        $img_ext  = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $valid_ext = ['png', 'jpeg', 'jpg', 'webp'];
        if (in_array($img_ext, $valid_ext) && $img_size <= 512000) { // Maksimal 500KB
            $lis_img = rand() . '_' . $img_name;
            $folder = "images/team/" . $lis_img;
            move_uploaded_file($img_tmp, $folder);
        }
    }
    if ($edit > 0) {
        // Mode update
        $query = "UPDATE teams SET title=?, designation=?, descrip=?, img=?, facebook=?, twitter=?, instagram=?, linkedin=?, whatsapp=?, url=?, date=? WHERE id=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "sssssssssssi", $title, $designation, $descrip, $lis_img, $facebook, $twitter, $instagram, $linkedin, $whatsapp, $url, $today, $edit);
    } else {
        // Mode insert
        $query = "INSERT INTO teams (title, designation, descrip, img, facebook, twitter, instagram, linkedin, whatsapp, url, date, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '0')";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "sssssssssss", $title, $designation, $descrip, $lis_img, $facebook, $twitter, $instagram, $linkedin, $whatsapp, $url, $today);
    }
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = ($edit > 0) ? "Berhasil Diperbarui" : "Berhasil Diposting";
        $_SESSION['msgClass'] = "success";
    } else {
        $_SESSION['msg'] = ($edit > 0) ? "Terjadi kesalahan saat memperbarui tim." : "Terjadi kesalahan saat memposting tim.";
        $_SESSION['msgClass'] = "danger";
    }
    mysqli_stmt_close($stmt);
    header("Location: add-teams.php" . ($edit > 0 ? "?edit=" . $edit : ""));
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?php include "title.php"; ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tema (AdminLTE) -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        .alert-container {
            max-width: 600px;
            margin: 0 auto 20px auto;
        }
    </style>
    <?php include '../includes/logo.php'; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include "topbar.php"; ?>
    <?php include "sidebar.php"; ?>
    <div class="content-wrapper">
        <!-- Header Konten -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><?php echo ($edit > 0) ? 'Edit Tim' : 'Tambah Tim'; ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <a href="view-teams.php" class="btn btn-success">Lihat Tim</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Konten Utama -->
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <!-- Tampilkan pesan jika ada -->
                    <?php if (!empty($_SESSION['msg'])): ?>
                        <div class="alert-container">
                            <div class="alert alert-<?php echo $_SESSION['msgClass']; ?> alert-dismissible fade show" role="alert">
                                <?php echo $_SESSION['msg']; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <?php 
                        unset($_SESSION['msg']); 
                        unset($_SESSION['msgClass']);
                        ?>
                    <?php endif; ?>
                    <!-- Form dengan validasi Bootstrap -->
                    <form id="teamForm" action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="card card-outline card-info">
                            <!-- Title -->
                            <div class="card-header">
                                <div class="form-group">
                                    <label for="validationTitle">Masukkan Nama <span class="text-danger">*</span></label>
                                    <input name="title" value="<?php echo isset($roww["title"]) ? htmlspecialchars($roww["title"]) : ''; ?>" type="text" class="form-control" id="validationTitle" placeholder="Masukkan nama..." required>
                                    <div class="invalid-feedback">
                                        Silahkan masukkan nama.
                                    </div>
                                </div>
                            </div>
                            <!-- Designation -->
                            <div class="card-header">
                                <div class="form-group">
                                    <label for="validationDesignation">Masukkan Jabatan <span class="text-danger">*</span></label>
                                    <input name="designation" value="<?php echo isset($roww["designation"]) ? htmlspecialchars($roww["designation"]) : ''; ?>" type="text" class="form-control" id="validationDesignation" placeholder="Masukkan jabatan..." required>
                                    <div class="invalid-feedback">
                                        Silahkan masukkan jabatan.
                                    </div>
                                </div>
                            </div>
                            <!-- Description -->
                            <!-- <div class="card-body pad">
                                <label>Deskripsi</label>
                                <div class="mb-3">
                                    <textarea name="Deskripsi" class="textarea" placeholder="Deskripsi" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo isset($roww["descrip"]) ? htmlspecialchars($roww["descrip"]) : ''; ?></textarea>
                                    <div class="invalid-feedback">
                                        Silahkan masukkan deskripsi.
                                    </div>
                                </div>
                            </div> -->
                            <!-- Social Media -->
                            <div class="card-header">
                                <div class="form-group">
                                    <label>Facebook</label>
                                    <input name="facebook" value="<?php echo isset($roww["facebook"]) ? htmlspecialchars($roww["facebook"]) : ''; ?>" type="text" class="form-control" placeholder="Masukkan URL atau username Facebook...">
                                </div>
                            </div>
                            <div class="card-header">
                                <div class="form-group">
                                    <label>Twitter</label>
                                    <input name="twitter" value="<?php echo isset($roww["twitter"]) ? htmlspecialchars($roww["twitter"]) : ''; ?>" type="text" class="form-control" placeholder="Masukkan URL atau username Twitter...">
                                </div>
                            </div>
                            <div class="card-header">
                                <div class="form-group">
                                    <label>Instagram</label>
                                    <input name="instagram" value="<?php echo isset($roww["instagram"]) ? htmlspecialchars($roww["instagram"]) : ''; ?>" type="text" class="form-control" placeholder="Masukkan URL atau username Instagram...">
                                </div>
                            </div>
                            <div class="card-header">
                                <div class="form-group">
                                    <label>LinkedIn</label>
                                    <input name="linkedin" value="<?php echo isset($roww["linkedin"]) ? htmlspecialchars($roww["linkedin"]) : ''; ?>" type="text" class="form-control" placeholder="Masukkan URL atau username LinkedIn...">
                                </div>
                            </div>
                            <div class="card-header">
                                <div class="form-group">
                                    <label>WhatsApp</label>
                                    <input name="whatsapp" value="<?php echo isset($roww["whatsapp"]) ? htmlspecialchars($roww["whatsapp"]) : ''; ?>" type="text" class="form-control" placeholder="Masukkan nomor WhatsApp...">
                                </div>
                            </div>
                            <!-- URL -->
                            <div class="card-header">
                                <div class="form-group">
                                    <label for="validationURL">Masukkan URL</label>
                                    <input name="url" value="<?php echo isset($roww["url"]) ? htmlspecialchars($roww["url"]) : ''; ?>" type="text" class="form-control" id="validationURL" placeholder="Masukkan URL...">
                                </div>
                            </div>
                            <!-- Image Upload -->
                            <div class="card-header">
                                <div class="form-group">
                                    <label for="validationImage">Pilih Gambar <span style="color:red;">(Maksimal 500KB, Hanya PNG, JPG, JPEG, WEBP)</span> <?php echo ($edit == 0 ? '<span class="text-danger">*</span>' : ''); ?></label>
                                    <input name="lis_img" type="file" id="validationImage" class="form-control" accept="image/*" <?php echo ($edit == 0 ? 'required' : ''); ?>>
                                    <small id="imageError" class="text-danger"></small>                              
                                    <?php 
                                    if (!empty($roww["img"])) {
                                        $imagePath = "images/team/" . $roww["img"];
                                        if (file_exists($imagePath)) {
                                            echo '<br><img src="' . htmlspecialchars($imagePath) . '" alt="Gambar Saat Ini" style="width:150px; margin-top:10px;">';
                                        } else {
                                            echo '<br><p>File gambar tidak ditemukan</p>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- Tombol Submit -->
                            <div class="card-header">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <?php echo ($edit > 0) ? 'Perbarui' : 'Publikasikan'; ?>
                                    </button>
                                    <a href="view-teams.php" class="btn btn-danger btn-lg">Kembali</a>
                                </div>
                            </div>
                        </div><!-- /.card -->
                    </form>
                </div><!-- /.col-md-8 -->
            </div><!-- /.row -->
        </section>
    </div>
    <?php include "footer.php"; ?>
</div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- SCRIPT VALIDASI BOOTSTRAP -->
<script>
(function () {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          var summernoteContent = $('.textarea').summernote('code');
          if ($('.textarea').summernote('isEmpty') || summernoteContent.trim() === "" || summernoteContent.trim() === "<p><br></p>") {
            $('.note-editor').addClass('is-invalid');
          } else {
            $('.note-editor').removeClass('is-invalid');
          }
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
document.getElementById("validationImage").addEventListener("change", function() {
    var file = this.files[0];
    var errorText = document.getElementById("imageError");
    if (file) {
        var fileSize = file.size;
        if (fileSize > 512000) { // 500KB = 512000 byte
            errorText.textContent = "Ukuran gambar tidak boleh lebih dari 500KB!";
            this.value = "";
        } else {
            errorText.textContent = "";
        }
    }
});
</script>
</body>
</html>