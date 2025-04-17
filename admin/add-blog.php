<?php
include 'conn.php';
include 'auth.php';
date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d H:i:s");
// Ambil pesan flash dari session (jika ada)
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    $msgClass = $_SESSION['msgClass'];
    // Setelah diambil, unset agar tidak muncul lagi saat refresh
    unset($_SESSION['msg'], $_SESSION['msgClass']);
} else {
    $msg = "";
    $msgClass = "";
}
// Periksa apakah parameter 'edit' ada pada URL
$edit = isset($_GET['edit']) ? mysqli_real_escape_string($con, $_GET['edit']) : '';
if ($edit) {
    // Mode edit: ambil data dari tabel blog berdasarkan ID
    $resultt = mysqli_query($con, "SELECT * FROM blog WHERE id = '$edit'");
    $roww = mysqli_fetch_array($resultt);
} else {
    // Mode baru (insert): set default kosong
    $roww = ['title' => '', 'category' => '', 'descrip' => '', 'img' => '', 'url' => ''];
}
// Tangani pengiriman form
if (isset($_POST['publise'])) {
    // Sanitasi input
    $title    = mysqli_real_escape_string($con, $_POST['title']);
    $category = mysqli_real_escape_string($con, $_POST['category']);
    // Ambil konten dari Summernote
    $descrip  = $_POST['descrip'];
    // Hapus tag <p> tapi pertahankan tag HTML lainnya
    $descrip  = preg_replace('/<p[^>]*>(.*?)<\/p>/is', '$1', $descrip);
    // Sanitasi input untuk mencegah XSS
    $descrip  = mysqli_real_escape_string($con, $descrip);
    $url      = isset($_POST['url']) ? mysqli_real_escape_string($con, $_POST['url']) : '';
    // Tangani unggahan file
    $lis_img = isset($roww["img"]) ? $roww["img"] : '';
    if (!empty($_FILES['lis_img']['name'])) {
        // Validasi ukuran file (maksimum 500KB)
        $maxFileSize = 500 * 1024; // 500KB dalam byte
        if ($_FILES['lis_img']['size'] > $maxFileSize) {
            $_SESSION['msg'] = "Ukuran file harus kurang dari 500KB.";
            $_SESSION['msgClass'] = "alert-danger";
            header("Location: add-blog.php" . ($edit ? "?edit=" . $edit : ""));
            exit;
        }
        $lis_img  = rand() . '_' . $_FILES['lis_img']['name'];
        $tempname = $_FILES['lis_img']['tmp_name'];
        $folder   = "images/blog/" . $lis_img;
        // Validasi ekstensi (opsional)
        $valid_ext = array('png', 'jpeg', 'jpg');
        $file_extension = strtolower(pathinfo($folder, PATHINFO_EXTENSION));
        if (in_array($file_extension, $valid_ext)) {
            compressImage($tempname, $folder, 60);
        }
    }
    // Jika mode baru (insert) atau update (edit)
    if ($edit == '') {
        // Insert
        $insertdata = mysqli_query($con, 
            "INSERT INTO blog (title, category, descrip, img, url, date)
             VALUES ('$title', '$category', '$descrip', '$lis_img', '$url', '$today')"
        );
        if ($insertdata) {
            $_SESSION['msg'] = "Berita berhasil diposting.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat memposting berita.";
            $_SESSION['msgClass'] = "alert-danger";
        }
        header("Location: add-blog.php");
        exit;
    } else {
        // Update
        $insertdata = mysqli_query($con, 
            "UPDATE blog SET 
                title     = '$title', 
                category  = '$category', 
                descrip   = '$descrip', 
                img       = '$lis_img', 
                url       = '$url', 
                date      = '$today' 
             WHERE id = '$edit'"
        );
        if ($insertdata) {
            $_SESSION['msg'] = "Berita berhasil diperbarui.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat memperbarui berita.";
            $_SESSION['msgClass'] = "alert-danger";
        }
        header("Location: add-blog.php?edit=" . $edit);
        exit;
    }
}
// Fungsi untuk mengompres gambar
function compressImage($source, $destination, $quality)
{
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, $quality);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
        imagegif($image, $destination);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        // Kualitas PNG: 0 (tanpa kompresi) - 9 (kompresi maksimal)
        imagepng($image, $destination, 9);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?php include "title.php"; ?>
    <!-- AdminLTE & Bootstrap CSS -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
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
                        <h1><?php echo ($edit) ? 'Perbarui Berita' : 'Tambah Berita'; ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <a href="view-blog.php" class="btn btn-success">
                        <i class="fa fa-eye" aria-hidden="true"></i> Lihat Berita
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Konten Utama -->
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <!-- Tampilkan pesan jika ada -->
                    <?php if (!empty($msg)): ?>
                        <div style="max-width:600px; margin:0 auto;">
                            <div class="alert <?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
                                <?php echo $msg; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- Form Berita dengan validasi Bootstrap & Summernote -->
                    <form id="blogForm" action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="card card-outline card-info">
                            <!-- Judul -->
                            <div class="card-header">
                                <div class="form-group">
                                    <label>Masukkan Judul <span class="text-danger">*</span></label>
                                    <input 
                                        name="title" 
                                        value="<?php echo htmlspecialchars($roww["title"]); ?>" 
                                        type="text" 
                                        class="form-control" 
                                        placeholder="Masukkan ..." 
                                        required
                                    >
                                    <div class="invalid-feedback">
                                        Silahkan masukkan judul berita.
                                    </div>
                                </div>
                            </div>
                            <!-- Kategori -->
                            <div class="card-header">
                                <div class="form-group">
                                    <label>Pilih Kategori <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control" required>
                                        <option value="">Pilih...</option>
                                        <?php
                                        $location = mysqli_query($con, "SELECT * FROM category");
                                        while ($location_ft = mysqli_fetch_array($location)) {
                                            $selected = (isset($roww["category"]) && $roww["category"] == $location_ft["cat_name"]) 
                                                        ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($location_ft["cat_name"]) . '" ' 
                                                 . $selected . '>' 
                                                 . htmlspecialchars($location_ft["cat_name"]) 
                                                 . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Silahkan pilih kategori.
                                    </div>
                                </div>
                            </div>
                            <!-- Deskripsi (Summernote) -->
                            <div class="card-body pad">
                                <label>Masukkan Deskripsi <span class="text-danger">*</span></label>
                                <div class="mb-3">
                                    <textarea 
                                        name="descrip" 
                                        class="textarea" 
                                        placeholder="Masukkan deskripsi ..." 
                                        style="width: 100%; height: 200px; border: 1px solid #dddddd; padding: 10px;" 
                                        required
                                    ><?php echo htmlspecialchars($roww["descrip"]); ?></textarea>
                                    <div class="invalid-feedback">
                                        Silahkan masukkan deskripsi.
                                    </div>
                                </div>
                            </div>
                            <!-- Unggah Gambar -->
                            <div class="card-header">
                                <div class="form-group">
                                    <label for="exampleInputFile">
                                        Pilih Gambar
                                        <?php 
                                        // Wajib unggah jika data baru atau belum ada gambar
                                        if(empty($roww["img"])){ 
                                            echo '<span class="text-danger">*</span>'; 
                                        }
                                        ?>
                                        <p style="color:red;">Maksimal 500 KB</p>
                                    </label>  
                                    <input type="file" name="lis_img" class="form-control" <?php echo empty($roww["img"]) ? 'required' : ''; ?>>
                                    <div id="fileError" class="text-danger mt-1" style="display: none;">File maksimal 500 kb.</div>
                                    <div id="fileSuccess" class="text-success mt-1" style="display: none;">✔ Ukuran file sudah benar.</div>
                                </div>
                                <?php 
                                if (!empty($roww["img"])) {
                                    $imagePath = "images/blog/" . $roww["img"];
                                    if (file_exists($imagePath)) {
                                        echo '<br><img src="' . htmlspecialchars($imagePath) . '" alt="Gambar Saat Ini" style="width:150px; margin-top:10px;">';
                                    } else {
                                        echo '<br><p>File gambar tidak ditemukan</p>';
                                    }
                                }
                                ?>
                            </div>
                            <!-- Tombol Submit -->
                            <div class="card-header">
                                <div class="form-group">
                                    <button type="submit" name="publise" class="btn btn-primary btn-lg">
                                        <?php echo ($edit) ? 'Perbarui' : 'Tambahkan'; ?>
                                    </button>
                                    <a href="view-blog.php" class="btn btn-danger btn-lg">Kembali</a>
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
<!-- Validasi Bootstrap & Summernote -->
<script>
    $(document).ready(function() {
      $('.textarea').summernote({
        height: 200,
        paragraph: false,
        callbacks: {
          onChange: function(contents, $editable) {
            // Callback sesuai kebutuhan
          }
        }
      });
      $('input[name="lis_img"]').on('change', function() {
        var file = this.files[0]; // Ambil file
        var maxFileSize = 500 * 1024; // 500KB dalam bytes
        if (file) {
            if (file.size > maxFileSize) {
                $('#fileError').show().text('File maksimal 500 kb.');
                $(this).val(''); // Kosongkan input file
            } else {
                $('#fileError').hide(); // Sembunyikan pesan jika ukuran sesuai
            }
        }
      });
      // Validasi khusus untuk Summernote dan ukuran file
      $('#blogForm').on('submit', function(event) {
        var isValid = true;
        var summernoteContent = $('.textarea').summernote('code');
        if ($('.textarea').summernote('isEmpty') || 
            summernoteContent.trim() === "" || 
            summernoteContent.trim() === "<p><br></p>") {
          $('.note-editor').addClass('is-invalid');
          isValid = false;
        } else {
          $('.note-editor').removeClass('is-invalid');
        }
        // Validasi ukuran unggahan file (maksimum 500KB)
        var fileInput = $('input[name="lis_img"]')[0];
        if(fileInput && fileInput.files.length > 0) {
            var fileSize = fileInput.files[0].size;
            var maxFileSize = 500 * 1024; // 500KB
            if (fileSize > maxFileSize) {
                isValid = false;
                $('#fileError').show().text('File maksimal 500 kb.');
            }
        }
        if (!isValid) {
          event.preventDefault();
          event.stopPropagation();
        }
      });
    });
    // Validasi Bootstrap 4
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
</body>
</html>