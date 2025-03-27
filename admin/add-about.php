<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';
date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d H:i:s");
// Cek apakah di tabel 'about' sudah ada data
$query  = "SELECT * FROM about LIMIT 1";
$result = mysqli_query($con, $query);
$roww   = mysqli_fetch_assoc($result);
// Flag untuk mengetahui apakah data sudah ada atau belum
$dataExists = ($roww) ? true : false;
// Jika form disubmit
if (isset($_POST['save'])) {
    $title   = mysqli_real_escape_string($con, $_POST['title']);
    $descrip = mysqli_real_escape_string($con, $_POST['descrip']);
    // Jika ada data lama, gunakan gambar lama. Jika upload baru, pakai file baru
    $lis_img = isset($roww['img']) ? $roww['img'] : '';
    if (!empty($_FILES['lis_img']['name'])) {
        $newFileName = rand() . '_' . $_FILES['lis_img']['name'];
        $tempFile    = $_FILES['lis_img']['tmp_name'];
        $folder      = "images/about/" . $newFileName;
        // Validasi ekstensi (opsional)
        $valid_ext = ['jpg', 'jpeg', 'png'];
        $file_ext  = strtolower(pathinfo($newFileName, PATHINFO_EXTENSION));
        if (in_array($file_ext, $valid_ext)) {
            move_uploaded_file($tempFile, $folder);
            $lis_img = $newFileName;
        }
    }
    // Jika data belum ada, lakukan INSERT. Jika sudah ada, lakukan UPDATE.
    if (!$dataExists) {
        // Insert data
        $sql = "INSERT INTO about (title, descrip, img, date, status) 
                VALUES ('$title', '$descrip', '$lis_img', '$today', '0')";
        $exec = mysqli_query($con, $sql);
        if ($exec) {
            $_SESSION['msg'] = "Data berhasil ditambahkan.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat menambahkan data.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    } else {
        // Update data
        $sql = "UPDATE about SET 
                    title   = '$title',
                    descrip = '$descrip',
                    img     = '$lis_img',
                    date    = '$today'
                WHERE id = '".$roww['id']."'";
        $exec = mysqli_query($con, $sql);
        if ($exec) {
            $_SESSION['msg'] = "Data berhasil diperbarui.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat memperbarui data.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    }
    // Redirect agar alert tidak muncul lagi setelah refresh
    header("Location: add-about.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "title.php"; ?>
    <!-- Bootstrap & AdminLTE CSS -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Summernote CSS -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include "topbar.php"; ?>
    <?php include "sidebar.php"; ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Edit Tentang Kami</h1>
        </section>
        <section class="content">
            <div class="container">
                <!-- Cek session untuk alert -->
                <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])): ?>
                    <div class="alert <?php echo $_SESSION['msgClass']; ?> alert-dismissible fade show" role="alert">
                        <?php 
                            echo $_SESSION['msg'];
                            // Hapus session agar hilang saat halaman di-refresh
                            unset($_SESSION['msg']); 
                            unset($_SESSION['msgClass']);
                        ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <!-- Form Edit/Add About dengan Bootstrap Validation -->
                <form action="" method="post" enctype="multipart/form-data" 
                      class="row g-3 needs-validation" novalidate style="margin: 0;">
                    <!-- Judul -->
                    <div class="col-md-12">
                        <label for="validationTitle" class="form-label">Judul</label>
                        <input type="text" name="title" class="form-control" id="validationTitle"
                               value="<?php echo ($dataExists) ? htmlspecialchars($roww['title']) : ''; ?>"
                               placeholder="Judul..." required>
                        <div class="invalid-feedback">
                            Mohon isi judul.
                        </div>
                    </div>
                    <!-- Isi Tentang Kami (Summernote) -->
                    <div class="col-md-12">
                        <label for="validationDescrip" class="form-label">Isi Deskripsi Tentang Kami</label>
                        <textarea name="descrip" class="form-control textarea" 
                                  id="validationDescrip" rows="8" required><?php 
                            echo ($dataExists) ? htmlspecialchars($roww['descrip']) : ''; 
                        ?></textarea>
                        <div class="invalid-feedback">
                            Mohon isi deskripsi.
                        </div>
                    </div>
                    <!-- Gambar (opsional) -->
                    <div class="col-md-12">
                        <label for="validationImage" class="form-label">Gambar</label><br>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="validationImage" name="lis_img">
                            <label class="custom-file-label" for="validationImage">Pilih Gambar</label>
                            <div class="invalid-feedback">
                                Mohon unggah gambar (jpg/jpeg/png).
                            </div>
                        </div>
                        <?php
                        if ($dataExists && !empty($roww['img'])) {
                            $imagePath = "images/about/" . $roww['img'];
                            if (file_exists($imagePath)) {
                                echo '<br><img src="' . htmlspecialchars($imagePath) . '?v=' . time() . '" 
                                           alt="Current Image" style="width: 200px; margin-top: 10px;">';
                            }
                        }
                        ?>
                    </div>
                    <!-- Tombol Aksi -->
                    <div style="padding-top: 3%;" class="col-12">
                        <button type="submit" name="save" class="btn btn-primary">Perbarui</button>
                        <a href="add-about.php" class="btn btn-danger">Kembali</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <?php include "footer.php"; ?>
</div>
<!-- jQuery, Bootstrap, AdminLTE JS -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script>
  $(function() {
    // Inisialisasi Summernote
    $('.textarea').summernote({
      height: 200
    });
    // Ketika form disubmit, copy isi Summernote ke <textarea> 
    // dan lakukan pengecekan kosong
    $('form.needs-validation').on('submit', function(event) {
      // Ambil konten Summernote
      var summernoteContent = $('.textarea').summernote('code');
      $('textarea[name="descrip"]').val(summernoteContent);
      // Periksa apakah summernote kosong
      if ($('.textarea').summernote('isEmpty')) {
        event.preventDefault();
        event.stopPropagation();
        $('.note-editor').addClass('is-invalid');
      } else {
        $('.note-editor').removeClass('is-invalid');
      }
      // Lanjutkan validasi bawaan Bootstrap
      if (!this.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      this.classList.add('was-validated');
    });
  });
  // Starter JavaScript untuk menonaktifkan submit jika form invalid (Bootstrap)
  (function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
  })();
</script>
<script>
document.getElementById("validationImage").addEventListener("change", function () {
    var fileName = this.files[0] ? this.files[0].name : "Tidak ada gambar yang dipilih";
    this.nextElementSibling.innerText = fileName;
});
</script>
</body>
</html>