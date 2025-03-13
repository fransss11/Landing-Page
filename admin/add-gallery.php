<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';

date_default_timezone_set('Asia/Kolkata');
$today = date("D d M Y");

// Inisialisasi variabel alert
$msg = "";
$msgClass = "";

// Check if 'edit' parameter is set in the URL
$edit = isset($_GET['edit']) ? mysqli_real_escape_string($con, $_GET['edit']) : null;

if ($edit) {
    $stmt = $con->prepare("SELECT * FROM media WHERE id = ?");
    $stmt->bind_param("i", $edit);
    $stmt->execute();
    $resultt = $stmt->get_result();
    $roww = $resultt->fetch_assoc();
    $stmt->close();
} else {
    // Default values for a new entry
    $roww = ['galery' => '', 'foto' => '', 'kategori' => ''];
}

if (isset($_POST['publise'])) {
    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    $kategori_id = mysqli_real_escape_string($con, $_POST['kategori_gal']);

    // Ambil nama kategori dari id
    $stmt2 = $con->prepare("SELECT kat_gal FROM kategori_gal WHERE id = ?");
    $stmt2->bind_param("i", $kategori_id);
    $stmt2->execute();
    $kategori_result = $stmt2->get_result();
    $kategori_row = $kategori_result->fetch_assoc();
    $kategori = $kategori_row['kat_gal'];
    $stmt2->close();

    if ($edit) {
        // Mode edit: jika file baru diupload, gunakan file baru; jika tidak, gunakan gambar lama.
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
            $_SESSION['msg'] = "Updated Successfully";
            $_SESSION['msgClass'] = "success";
        } else {
            $_SESSION['msg'] = "Error while updating the gallery.";
            $_SESSION['msgClass'] = "danger";
        }
        
        echo "<script>window.location.href = 'view-gallery.php';</script>";
        exit;
    } else {
        // Mode tambah: multiple file upload
        if (isset($_FILES['gambar'])) {
            $total_files = count($_FILES['gambar']['name']);
            $inserted = false;
            for ($i = 0; $i < $total_files; $i++) {
                if (!empty($_FILES['gambar']['name'][$i])) {
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
            if ($inserted) {
                $_SESSION['msg'] = "Posted Successfully";
                $_SESSION['msgClass'] = "success";
            } else {
                $_SESSION['msg'] = "Error while updating the gallery.";
                $_SESSION['msgClass'] = "danger";
            }
        } else {
            $msg = "No images selected.";
            $msgClass = "danger";
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
    <!-- Theme style (AdminLTE) -->
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
        <!-- Content Header -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1><?php echo ($edit) ? 'Edit Gallery' : 'Add Gallery'; ?></h1>
              </div>
              <div class="col-sm-6">
                <a href="view-gallery.php" class="btn btn-success"><i class="fa fa-eye"></i> View Gallery</a>
              </div>
            </div>
          </div>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-8">
              <?php if (!empty($msg)): ?>
                <div class="alert-container">
                  <div class="alert alert-<?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
                    <?php echo $msg; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Form Gallery dengan validasi Bootstrap -->
              <form id="galleryForm" action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="card card-outline card-info">
                  <div class="card-header">
                    <div class="form-group">
                      <label for="validationGalleryName">Input Gallery Name <span class="text-danger">*</span></label>
                      <input type="text" name="nama" value="<?php echo isset($roww["galery"]) ? htmlspecialchars($roww["galery"]) : ''; ?>" class="form-control" id="validationGalleryName" placeholder="Enter ...">
                      <!-- <div class="invalid-feedback">
                        Please enter a gallery name.
                      </div>
                      <div class="valid-feedback">
                        Looks good!
                      </div> -->
                    </div>
                  </div>

                  <div class="card-header">
                    <div class="form-group">
                      <label for="validationKategori">Select Kategori <span class="text-danger">*</span></label>
                      <select name="kategori_gal" class="form-control" id="validationKategori" required>
                        <option value="">Select...</option>
                        <?php
                        $location = mysqli_query($con, "SELECT * FROM kategori_gal");
                        while ($location_ft = mysqli_fetch_array($location)) {
                            $selected = (isset($roww["kategori"]) && $roww["kategori"] == $location_ft["kat_gal"]) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($location_ft["id"]) . '" ' . $selected . '>' . htmlspecialchars($location_ft["kat_gal"]) . '</option>';
                        }
                        ?>
                      </select>
                      <div class="invalid-feedback">
                        Please select a kategori.
                      </div>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                  </div>

                  <div class="card-header">
                    <div class="form-group">
                      <label for="validationImages">Select Gallery Images <span style="color:red;">(only compressed)</span></label>
                      <p style="color:red;">Image size 800px x 800px</p>
                      <?php 
                      if ($edit) {
                          // Mode edit: single file upload
                          ?>
                          <input type="file" name="gambar" id="validationImages" class="form-control" accept="image/*" <?php echo empty($roww["foto"]) ? 'required' : ''; ?>>
                          <?php
                      } else {
                          // Mode tambah: multiple file upload
                          ?>
                          <input type="file" name="gambar[]" id="validationImages" class="form-control" accept="image/*" required multiple>
                          <?php
                      }
                      ?>
                      <div class="invalid-feedback">
                        Please upload image(s).
                      </div>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                      <?php
                      if ($edit && isset($roww["foto"]) && !empty($roww["foto"])) {
                          $imagePath = "uploads/" . $roww["foto"];
                          if (file_exists($imagePath)) {
                              echo '<br><img src="' . htmlspecialchars($imagePath) . '" alt="Gallery Image" style="width:200px; margin-top:10px;">';
                          } else {
                              echo '<br><p>Image file not found</p>';
                          }
                      }
                      ?>
                    </div>
                  </div>

                  <div class="card-header">
                    <div class="form-group">
                      <button type="submit" name="publise" class="btn btn-primary btn-lg"><?php echo ($edit) ? 'Update' : 'Publish'; ?></button>
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
          // Validasi Summernote (jika perlu)
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
</body>
</html>