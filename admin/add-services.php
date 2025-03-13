<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
error_reporting(0);
include 'conn.php';
include 'auth.php';

date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d H:i:s");

// Check if 'edit' parameter exists in URL dan valid
$edit = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

// Ambil data jika mode edit
if ($edit > 0) {
    $resultt = mysqli_query($con, "SELECT * FROM services WHERE id = '$edit'");
    $roww = mysqli_fetch_array($resultt);
} else {
    $roww = []; // mode insert, inisialisasi agar tidak error
}

if (isset($_POST['publise'])) {
    // Sanitasi input menggunakan mysqli_real_escape_string
    $title   = mysqli_real_escape_string($con, $_POST['title']);
    $short   = mysqli_real_escape_string($con, $_POST['short']);
    $descrip = mysqli_real_escape_string($con, $_POST['descrip']);

    // Handle file upload
    if (!empty($_FILES['lis_img']['name'])) {
        // Buat nama file unik (mirip dengan add-about.php)
        $newFileName = rand() . '_' . $_FILES['lis_img']['name'];
        $tempFile    = $_FILES['lis_img']['tmp_name'];
        $folder      = "images/services/" . $newFileName;

        // Jika file ada, pindahkan ke folder tujuan
        if (!empty($tempFile)) {
            move_uploaded_file($tempFile, $folder);
        }
        $lis_img = $newFileName;
    } else {
        // Gunakan gambar lama jika ada
        $lis_img = isset($roww["img"]) ? $roww["img"] : '';
    }

    // INSERT (tambah data baru)
    if ($edit == 0) {
        $insertdata = mysqli_query($con, 
            "INSERT INTO services(title, short, descrip, img, date) 
             VALUES('$title', '$short', '$descrip', '$lis_img', '$today')");

        if ($insertdata) {
            $_SESSION['msg'] = "Posted Successfully";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Error while posting the service.";
            $_SESSION['msgClass'] = "alert-danger";
        }
        // Redirect ke halaman add-services.php tanpa parameter edit
        header("Location: add-services.php");
        exit;
    }
    // UPDATE (perbarui data)
    else {
        $insertdata = mysqli_query($con, 
            "UPDATE services SET 
                title='$title', 
                short='$short', 
                descrip='$descrip', 
                img='$lis_img', 
                date='$today' 
             WHERE id=" . $edit);

        if ($insertdata) {
            $_SESSION['msg'] = "Updated Successfully";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Error while updating the service.";
            $_SESSION['msgClass'] = "alert-danger";
        }
        // Redirect ke halaman edit dengan parameter edit sehingga data tetap muncul
        header("Location: add-services.php?edit=" . $edit);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <?php include "title.php"; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style (AdminLTE) -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <?php include "topbar.php"; ?>
  <!-- Main Sidebar Container -->
  <?php include "sidebar.php"; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?php echo ($edit > 0) ? 'Edit Services' : 'Add Services'; ?></h1>
          </div>
          <div class="col-sm-6">
            <a href="view-services.php" class="btn btn-success">
              <i class="fa fa-eye" aria-hidden="true"></i> View Services
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <section class="content">
      <div class="row">
        <div class="col-md-8">
          
          <!-- Tampilkan alert jika ada pesan (menggunakan session) -->
          <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])): ?>
            <div style="max-width: 600px; margin: 0 auto;">
              <div class="alert <?php echo $_SESSION['msgClass']; ?> alert-dismissible fade show" role="alert">
                <?php 
                  echo $_SESSION['msg'];
                  // Hapus session agar alert tidak muncul lagi setelah refresh
                  unset($_SESSION['msg']); 
                  unset($_SESSION['msgClass']);
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
          <?php endif; ?>

          <!-- Form dengan validasi -->
          <form id="serviceForm" action="" method="post" enctype="multipart/form-data" 
                class="needs-validation" novalidate>
            <div class="card card-outline card-info">
              
              <!-- Title -->
              <div class="card-header">
                <div class="form-group">
                  <label>Enter Title <span class="text-danger">*</span></label>
                  <input 
                    name="title" 
                    value="<?php echo isset($roww["title"]) ? htmlspecialchars($roww["title"]) : ''; ?>" 
                    type="text" 
                    class="form-control" 
                    placeholder="Enter ..." 
                    maxlength="100" 
                    required
                  >
                  <div class="invalid-feedback">
                    Please enter a title.
                  </div>
                </div>
              </div>

              <!-- Short Description -->
              <div class="card-body pad">
                <div class="form-group">
                  <label>Short Description <span class="text-danger">*</span></label>
                  <textarea 
                    name="short" 
                    class="form-control" 
                    placeholder="Short Description" 
                    rows="3" 
                    maxlength="200" 
                    required
                  ><?php echo isset($roww["short"]) ? htmlspecialchars($roww["short"]) : ''; ?></textarea>
                  <div class="invalid-feedback">
                    Please enter a short description.
                  </div>
                </div>
              </div>

              <!-- Full Description (Summernote) -->
              <div class="card-body pad">
                <div class="form-group">
                  <label>Full Description <span class="text-danger">*</span></label>
                  <textarea 
                    name="descrip" 
                    class="form-control textarea" 
                    placeholder="Place some text here" 
                    rows="8" 
                    maxlength="10000" 
                    required
                  ><?php echo isset($roww["descrip"]) ? htmlspecialchars($roww["descrip"]) : ''; ?></textarea>
                  <div class="invalid-feedback">
                    Please enter the full description.
                  </div>
                </div>
              </div>

              <!-- Image Upload -->
              <div class="card-header">
                <div class="form-group">
                  <label for="exampleInputFile">
                    Select Image
                    <?php 
                      // Wajib upload jika data baru atau belum ada gambar
                      if(empty($roww["img"])){ 
                        echo '<span class="text-danger">*</span>'; 
                      }
                    ?>
                  </label>                  
                  <input 
                    name="lis_img" 
                    type="file" 
                    id="fileUpload"
                    class="form-control"
                    accept="image/*"
                    <?php echo empty($roww["img"]) ? 'required' : ''; ?>
                  >
                  <div class="invalid-feedback">
                    Please upload an image.
                  </div>

                  <?php 
                  if (!empty($roww["img"])) {
                    $imagePath = "images/services/" . $roww["img"];
                    if(file_exists($imagePath)) {
                      echo '<br><img src="' . htmlspecialchars($imagePath) . '" alt="Current Image" style="width:150px; margin-top:10px;">';
                    } else {
                      echo '<br><p>Image file not found</p>';
                    }
                  }
                  ?>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="card-header">
                <div class="form-group">
                  <button type="submit" name="publise" class="btn btn-primary btn-lg">
                    <?php echo ($edit) ? 'Update' : 'Publish Post'; ?>
                  </button>
                  <a href="view-services.php" class="btn btn-danger">Kembali</a>
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
  $(function () {
    // Inisialisasi Summernote
    $('.textarea').summernote({
      height: 200
    });

    // Validasi manual saat form disubmit
    $('#serviceForm').on('submit', function(event) {
      var form = this;
      var isValid = true; // Flag untuk validasi
      
      // Sinkronkan isi Summernote ke textarea sebelum validasi
      var summernoteContent = $('.textarea').summernote('code');
      $('textarea[name="descrip"]').val(summernoteContent);

      // Cek jika Summernote kosong (termasuk HTML kosong seperti <p><br></p>)
      if ($('.textarea').summernote('isEmpty') || 
          summernoteContent.trim() === "" || 
          summernoteContent === "<p><br></p>") {
        isValid = false;
        $('.note-editor').addClass('is-invalid'); // Tambahkan class error
      } else {
        $('.note-editor').removeClass('is-invalid'); // Hapus class error jika valid
      }

      // Jalankan validasi Bootstrap (untuk input lainnya)
      if (!form.checkValidity()) {
        isValid = false;
      }

      // Jika ada yang tidak valid, cegah submit
      if (!isValid) {
        event.preventDefault();
        event.stopPropagation();
      }

      // Tambahkan class Bootstrap agar field ditandai sebagai error
      form.classList.add('was-validated');
    });
  });
</script>
</body>
</html>