<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';

date_default_timezone_set('Asia/Kolkata');
$today = date("D d M Y");

// Inisialisasi variabel alert
$msg = "";
$msgClass = "";

// Periksa apakah parameter 'edit' ada di URL
$edit = isset($_GET['edit']) ? mysqli_real_escape_string($con, $_GET['edit']) : null;

if ($edit) {
    $stmt = $con->prepare("SELECT * FROM media WHERE id = ?");
    $stmt->bind_param("i", $edit);
    $stmt->execute();
    $resultt = $stmt->get_result();
    $roww = $resultt->fetch_assoc();
    $stmt->close();
} else {
    // Nilai default untuk data baru
    $roww = ['galery' => '', 'foto' => '', 'kategori' => ''];
}

if (isset($_POST['publise'])) {
    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    $kategori_id = mysqli_real_escape_string($con, $_POST['kategori_gal']);

    // Ambil nama kategori berdasarkan id
    $stmt2 = $con->prepare("SELECT kat_gal FROM kategori_gal WHERE id = ?");
    $stmt2->bind_param("i", $kategori_id);
    $stmt2->execute();
    $kategori_result = $stmt2->get_result();
    $kategori_row = $kategori_result->fetch_assoc();
    $kategori = $kategori_row['kat_gal'];
    $stmt2->close();

    if ($edit) {
        // Mode edit: jika file baru diunggah, gunakan file baru; jika tidak, gunakan gambar lama.
        if (isset($_FILES['gambar']) && $_FILES['gambar']['name'] != '') {
            $gambar = rand() . $_FILES['gambar']['name'];
            $tempname = $_FILES['gambar']['tmp_name'];
            $folder = "uploads/" . $gambar;
            $valid_ext = array('png', 'jpeg', 'jpg');
            $file_extension = strtolower(pathinfo($folder, PATHINFO_EXTENSION));
            if (in_array($file_extension, $valid_ext)) {
                compressImage($tempname, $folder, 60);
            }
        } else {
            $gambar = $roww['foto'];
        }
        $update = mysqli_query($con, "UPDATE media SET galery='$nama', foto='$gambar', kategori='$kategori', uploaded_on=NOW(), status='1' WHERE id='$edit'");
        if ($update) {
            $_SESSION['msg'] = "Berhasil Diperbarui";
            $_SESSION['msgClass'] = "success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat memperbarui galeri.";
            $_SESSION['msgClass'] = "danger";
        }
        
        echo "<script>window.location.href = 'view-gallery.php';</script>";
        exit;
    } else {
        // Mode tambah: unggah file berganda
        if (isset($_FILES['gambar'])) {
          $total_files = count($_FILES['gambar']['name']);
          $inserted = false;
          for ($i = 0; $i < $total_files; $i++) {
              if (!empty($_FILES['gambar']['name'][$i])) {
                  $image_size = $_FILES['gambar']['size'][$i];
                  $max_size = 500 * 1024; // 500KB dalam bytes
      
                  if ($image_size > $max_size) {
                      $_SESSION['msg'] = "Error: Satu atau lebih gambar melebihi batas 500KB.";
                      $_SESSION['msgClass'] = "danger";
                      echo "<script>window.location.href = 'view-gallery.php';</script>";
                      exit;
                  }
      
                  $image_name = rand() . $_FILES['gambar']['name'][$i];
                  $tempname = $_FILES['gambar']['tmp_name'][$i];
                  $folder = "uploads/" . $image_name;
                  $valid_ext = array('png', 'jpeg', 'jpg');
                  $file_extension = strtolower(pathinfo($folder, PATHINFO_EXTENSION));
      
                  if (in_array($file_extension, $valid_ext)) {
                      compressImage($tempname, $folder, 60);
                      mysqli_query($con, "INSERT INTO media (galery, foto, kategori, uploaded_on, status) VALUES ('$nama', '$image_name', '$kategori', NOW(), '1')");
                      $inserted = true;
                  }
              }
          }
        }
          if ($inserted) {
              $_SESSION['msg'] = "Berhasil Diposting";
              $_SESSION['msgClass'] = "success";
          } else {
              $_SESSION['msg'] = "Terjadi kesalahan saat memperbarui galeri.";
              $_SESSION['msgClass'] = "danger";
          }
      }      
    echo "<script>window.location.href = 'view-gallery.php';</script>";
}

function compressImage($source, $destination, $quality) {
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, $quality);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
        imagegif($image, $destination);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        imagepng($image, $destination, 9);
    }
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
                <h1><?php echo ($edit) ? 'Perbarui Galeri' : 'Tambah Galeri'; ?></h1>
              </div>
              <div class="col-sm-6">
                <a href="view-gallery.php" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Galeri</a>
              </div>
            </div>
          </div>
        </section>

        <!-- Konten Utama -->
        <section class="content">
          <div class="row">
            <div class="col-md-8">
              <?php if (!empty($msg)): ?>
                <div class="alert-container">
                  <div class="alert alert-<?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
                    <?php echo $msg; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Form Galeri dengan validasi Bootstrap -->
              <form id="galleryForm" action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="card card-outline card-info">
                  <div class="card-header">
                    <div class="form-group">
                      <label for="validationGalleryName">Masukkan Nama Galeri <span class="text-danger">*</span></label>
                      <input type="text" name="nama" value="<?php echo isset($roww["galery"]) ? htmlspecialchars($roww["galery"]) : ''; ?>" class="form-control" id="validationGalleryName" placeholder="Masukkan ..." required>
                    </div>
                  </div>

                  <div class="card-header">
                    <div class="form-group">
                      <label for="validationKategori">Pilih Kategori <span class="text-danger">*</span></label>
                      <select name="kategori_gal" class="form-control" id="validationKategori" required>
                        <option value="">Pilih...</option>
                        <?php
                        $location = mysqli_query($con, "SELECT * FROM kategori_gal");
                        while ($location_ft = mysqli_fetch_array($location)) {
                            $selected = (isset($roww["kategori"]) && $roww["kategori"] == $location_ft["kat_gal"]) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($location_ft["id"]) . '" ' . $selected . '>' . htmlspecialchars($location_ft["kat_gal"]) . '</option>';
                        }
                        ?>
                      </select>
                      <div class="invalid-feedback">
                        Silahkan pilih kategori.
                      </div>
                    </div>
                  </div>

                  <div class="card-header">
                    <div class="form-group">
                    <label for="exampleInputFile">
                        Pilih Gambar
                        <?php 
                        // Wajib unggah jika data baru atau belum ada gambar
                        if(empty($roww["foto"])){ 
                            echo '<span class="text-danger">*</span>'; 
                        }
                        ?>
                        <p style="color:red;">Maksimal 500 KB</p>
                    </label>
                      <?php 
                      if ($edit) {
                          // Mode edit: unggah file tunggal
                          ?>
                          <input type="file" name="gambar" id="validationImages" class="form-control" accept="image/*" <?php echo empty($roww["foto"]) ? 'required' : ''; ?>>
                          <?php
                      } else {
                          // Mode tambah: unggah file berganda
                          ?>
                          <input type="file" name="gambar[]" id="validationImages" class="form-control" accept="image/*" required multiple>
                          <?php
                      }
                      ?>
                    <div id="fileErrorBox" style="color: red; display: none;">Ukuran file harus kurang dari 500KB.</div>
                      <?php
                      if ($edit && isset($roww["foto"]) && !empty($roww["foto"])) {
                          $imagePath = "uploads/" . $roww["foto"];
                          if (file_exists($imagePath)) {
                              echo '<br><img src="' . htmlspecialchars($imagePath) . '" alt="Gambar Galeri" style="width:200px; margin-top:10px;">';
                          } else {
                              echo '<br><p>File gambar tidak ditemukan</p>';
                          }
                      }
                      ?>
                    </div>
                  </div>

                  <div class="card-header">
                    <div class="form-group">
                      <button type="submit" name="publise" class="btn btn-primary btn-lg"><?php echo ($edit) ? 'Perbarui' : 'Publikasikan'; ?></button>
                      <a href="view-gallery.php" class="btn btn-danger">Kembali</a>
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
<script>
  $(function() {
    $('.textarea').summernote({
      height: 200
    });
  });
  (function () {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          // Validasi Summernote (jika diperlukan)
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
document.getElementById('validationImages').addEventListener('change', function () {
    var files = this.files;
    var maxSize = 500 * 1024; // 500KB dalam bytes
    var errorBox = document.getElementById('fileErrorBox');

    errorBox.style.display = 'none'; // Sembunyikan pesan error terlebih dahulu

    for (var i = 0; i < files.length; i++) {
        if (files[i].size > maxSize) {
            errorBox.style.display = 'block';
            errorBox.innerHTML = "Error: Salah satu atau lebih gambar melebihi batas 500KB.";
            this.value = ""; // Kosongkan input file agar pengguna harus memilih ulang
            break;
        }
    }
});
</script>
</body>
</html>
