<?php
include 'conn.php';
include 'auth.php';

date_default_timezone_set('Asia/Kolkata');
$today = date("D d M Y"); // Format tanggal untuk MySQL

// Inisialisasi variabel alert
$msg = "";
$msgClass = "";

// Check if 'edit' parameter is set in the URL
$edit = isset($_GET['edit']) ? mysqli_real_escape_string($con, $_GET['edit']) : null;

if ($edit) {
    $resultt = mysqli_query($con, "SELECT * FROM klien WHERE id = $edit");
    if ($resultt) {
        $roww = mysqli_fetch_array($resultt);
    } else {
        $msg = "Client not found.";
        $msgClass = "danger";
        // Jika client tidak ditemukan, hentikan eksekusi
        exit;
    }
} else {
    $roww = ['klien' => '', 'gambar' => ''];
}

if (isset($_POST['publise'])) {
    $klien = mysqli_real_escape_string($con, $_POST['klien']);

    // Validasi server-side (jika kosong, set message dan hentikan eksekusi)
    if (empty($klien)) {
        $msg = "Client name is required.";
        $msgClass = "danger";
    } else {
        if ($_FILES['gambar']['name'] != '') {
            $maxFileSize = 500 * 1024; // 500KB

            if ($_FILES['gambar']['size'] > $maxFileSize) {
                $msg = "File size must be less than 500KB.";
                $msgClass = "danger";
                echo "<div class='alert alert-danger'>".$msg."</div>";
                exit;
            }

            $gambar = rand() . $_FILES['gambar']['name'];
            $tempname = $_FILES['gambar']['tmp_name'];
            $folder = "images/partnership/" . $gambar;
            $valid_ext = array('png', 'jpeg', 'jpg', 'gif');
            $file_extension = strtolower(pathinfo($folder, PATHINFO_EXTENSION));

            if (in_array($file_extension, $valid_ext)) {
                compressImage($tempname, $folder, 60);
            } else {
                $msg = "Invalid file type.";
                $msgClass = "danger";
                // Hentikan eksekusi jika file tidak valid
                echo "<div class='alert alert-danger'>".$msg."</div>";
                exit;
            }
        } else {
            if (empty($roww["gambar"])) {
                $msg = "Client logo is required.";
                $msgClass = "danger";
            }
            $gambar = $roww["gambar"];
        }
    }

    if (empty($msg)) {
        if (!$edit) {
            $insertdata = mysqli_query($con, "INSERT INTO klien(klien, gambar) VALUES ('$klien', '$gambar')");
            if ($insertdata) {
                $_SESSION['msg'] = "Posted Successfully";
                $_SESSION['msgClass'] = "success";
            } else {
                $_SESSION['msg'] = "Error posting data";
                $_SESSION['msgClass'] = "danger";
            }
        } else {
            $insertdata = mysqli_query($con, "UPDATE klien SET klien='$klien', gambar='$gambar' WHERE id=$edit");
            if ($insertdata) {
                $_SESSION['msg'] = "Updated Successfully";
                $_SESSION['msgClass'] = "success";
            } else {
                $_SESSION['msg'] = "Error updating data";
                $_SESSION['msgClass'] = "danger";
            }
        }
        // Redirect to view-partner.php to show the message
        header("Location: view-partner.php");
        exit;
    }
}

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
        imagepng($image, $destination, 9);
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
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style (AdminLTE) -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
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
            <h1>Add Client</h1>
          </div>
          <div class="col-sm-6">
            <a href="view-partner.php" class="btn btn-success">
              <i class="fa fa-eye" aria-hidden="true"></i> View Clients
            </a>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-8">
          <!-- Tampilkan alert jika ada pesan -->
          <?php if (!empty($msg)): ?>
            <div style="max-width:600px; margin:0 auto 20px auto;">
              <div class="alert alert-<?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
                <?php echo $msg; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
          <?php endif; ?>

          <!-- Form dengan validasi Bootstrap -->
          <!-- Tambahkan class "needs-validation" dan "novalidate" -->
          <form id="clientForm" action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="card card-outline card-info">
              <div class="card-header">
                <div class="form-group">
                  <label for="validationClientName">Input Client Name <span class="text-danger">*</span></label>
                  <input name="klien" value="<?php echo htmlspecialchars($roww['klien']); ?>" type="text" class="form-control" id="validationClientName" placeholder="Enter name..." required>
                  <div class="invalid-feedback">
                    Client name is required.
                  </div>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                </div>
              </div>

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
                        <p style="color:red;">Maksimal 500 KB</p>
                    </label>  
                    <input name="gambar" type="file" id="validationLogo" class="form-control" accept="image/*" <?php echo empty($roww['gambar']) ? 'required' : ''; ?>>
                    <div id="fileErrorBox" style="color: red; display: none;">File size must be less than 500KB.</div>
                    <!-- <div id="fileSuccess" class="text-success mt-1" style="display: none;">✔ File size is valid.</div> -->
                </div>
                  <?php 
                  if (!empty($roww['gambar'])) {
                      $imagePath = "images/partnership/" . $roww['gambar'];
                      if (file_exists($imagePath)) {
                          echo '<br><img src="' . htmlspecialchars($imagePath) . '" alt="Client Logo" style="width:150px; margin-top:10px;">';
                      } else {
                          echo '<br><p>Image file not found</p>';
                      }
                  }
                  ?>
                </div>
              </div>

              <div class="card-header">
                <div class="form-group">
                  <button type="submit" name="publise" class="btn btn-primary btn-lg">
                    <?php echo ($edit) ? 'Update' : 'Publish Post'; ?>
                  </button>
                  <a href="view-partner.php" class="btn btn-danger">Kembali</a>
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
        // Jika form tidak valid, cegah submit
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
document.getElementById('validationLogo').addEventListener('change', function () {
    var file = this.files[0]; // Ambil file yang dipilih
    var errorBox = document.getElementById('fileErrorBox'); // Ambil elemen pesan error
    errorBox.style.display = 'none'; // Sembunyikan pesan error secara default

    if (file) {
        var maxSize = 500 * 1024; // 500KB dalam bytes
        if (file.size > maxSize) {
            errorBox.style.display = 'block'; // Tampilkan pesan error
            this.value = ""; // Kosongkan input file agar pengguna harus memilih ulang
        }
    }
});
</script>

</body>
</html>