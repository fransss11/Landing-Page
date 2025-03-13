<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';

$a = 5;

// Ambil flash message dari session jika ada
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    $msgClass = $_SESSION['msgClass'];
    unset($_SESSION['msg'], $_SESSION['msgClass']);
} else {
    $msg = "";
    $msgClass = "";
}

date_default_timezone_set('Asia/Kolkata');
$today = date("D d M Y");

// Pastikan parameter 'edit' ada jika ingin mengedit
$edit = isset($_GET['edit']) ? mysqli_real_escape_string($con, $_GET['edit']) : '';

// Ambil data testimonial untuk mode edit
$roww = [];
if ($edit) {
    $resultt = mysqli_query($con, "SELECT * FROM testimonials WHERE id = '$edit'");
    $roww = mysqli_fetch_array($resultt);
}
if (!$roww) {
    // Jika tidak ada data, inisialisasi dengan nilai kosong
    $roww = [
        "title"       => "",
        "designation" => "",
        "descrip"     => "",
        "img"         => "",
    ];
}

// Handle form submission
if (isset($_POST['publise'])) {
    $title       = mysqli_real_escape_string($con, $_POST['title']);
    $designation = mysqli_real_escape_string($con, $_POST['designation']);
    $comments    = mysqli_real_escape_string($con, $_POST['comments']);

    // Gunakan gambar lama jika tidak ada file baru yang diupload
    $lis_img = $roww["img"];

    if (!empty($_FILES['lis_img']['name'])) {
        // Buat nama file baru dengan rand() dan nama file aslinya
        $lis_img = rand() . '_' . $_FILES['lis_img']['name'];
        $tempname = $_FILES['lis_img']['tmp_name'];
        $folder   = "images/testimonial/" . $lis_img;
        $valid_ext = array('png', 'jpeg', 'jpg', 'webp', 'gif');
        $file_extension = strtolower(pathinfo($lis_img, PATHINFO_EXTENSION));
        if (in_array($file_extension, $valid_ext)) {
            compressImage($tempname, $folder, 60);
        }
    }

    if ($edit == '') {
        // Insert data testimonial baru
        $insertdata = mysqli_query($con, "INSERT INTO testimonials (title, designation, descrip, img, date, status) 
            VALUES ('$title', '$designation', '$comments', '$lis_img', '$today', '0')");
        if ($insertdata) {
            $_SESSION['msg'] = "Posted Successfully";
            // Tambahkan class tambahan agar background hijau dan teks putih tampil jelas
            $_SESSION['msgClass'] = "alert-success bg-success text-white";
        } else {
            $_SESSION['msg'] = "Error while posting the testimonial.";
            $_SESSION['msgClass'] = "alert-danger bg-danger text-white";
        }
        // Redirect agar flash message hanya muncul sekali (dan form kosong)
        header("Location: add-testimonials.php");
        exit;
    } else {
        // Update data testimonial
        $insertdata = mysqli_query($con, "UPDATE testimonials 
            SET title = '$title', designation = '$designation', descrip = '$comments', img = '$lis_img', date = '$today'
            WHERE id = '$edit'");
        if ($insertdata) {
            $_SESSION['msg'] = "Updated Successfully";
            $_SESSION['msgClass'] = "alert-success bg-success text-white";
        } else {
            $_SESSION['msg'] = "Error while updating the testimonial.";
            $_SESSION['msgClass'] = "alert-danger bg-danger text-white";
        }
        // Redirect ke URL dengan parameter edit agar data update tetap muncul di form
        header("Location: add-testimonials.php?edit=$edit");
        exit;
    }
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
    } elseif ($info['mime'] == 'image/webp') {
        $image = imagecreatefromwebp($source);
        imagewebp($image, $destination, $quality);
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
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <?php include "topbar.php"; ?>
    <!-- Main Sidebar Container -->
    <?php include "sidebar.php"; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><?php echo ($edit) ? 'Edit Testimonials' : 'Add Testimonials'; ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <a href="view-testimonials.php" class="btn btn-success">
                            <i class="fa fa-eye" aria-hidden="true"></i> View Testimonials
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Tampilkan alert jika ada pesan -->
                <?php if (!empty($msg)): ?>
                    <div style="max-width: 250px;">
                        <div class="alert <?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
                            <?php echo $msg; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <!-- Form dengan validasi Bootstrap -->
                        <form class="needs-validation" novalidate action="" method="post" enctype="multipart/form-data">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <div class="form-group">
                                        <label for="validationTitle" class="form-label">Enter Name</label>
                                        <input name="title" type="text" class="form-control" id="validationTitle"
                                               value="<?php echo htmlspecialchars($roww["title"]); ?>" 
                                               placeholder="Enter ..." required>
                                        <div class="invalid-feedback">
                                            Please provide a valid name.
                                        </div>
                                    </div>
                                </div>

                                <div class="card-header">
                                    <div class="form-group">
                                        <label for="validationDesignation" class="form-label">Enter Designation</label>
                                        <input name="designation" type="text" class="form-control" id="validationDesignation"
                                               value="<?php echo htmlspecialchars($roww["designation"]); ?>" 
                                               placeholder="Enter ..." required>
                                        <div class="invalid-feedback">
                                            Please provide a valid designation.
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body pad">
                                    <label for="validationComments" class="form-label">Comments</label>
                                    <div class="mb-3">
                                        <textarea name="comments" class="form-control" id="validationComments" 
                                                  placeholder="Comments" rows="5" required><?php echo htmlspecialchars($roww["descrip"]); ?></textarea>
                                        <div class="invalid-feedback">
                                            Please provide some comments.
                                        </div>
                                    </div>
                                </div>

                                <div class="card-header">
                                    <div class="form-group">
                                        <label for="validationImage" class="form-label">Select Img <span style="color:red;">(only compressed)</span></label>
                                        <p style="color:red;">Image size 70px x 70px</p>
                                        <input name="lis_img" type="file" class="form-control" id="validationImage" accept="image/*" <?php echo empty($roww["img"]) ? 'required' : ''; ?>>
                                        <div class="invalid-feedback">
                                            Please upload an image (if no existing image).
                                        </div>
                                        <?php 
                                        if (!empty($roww["img"])) {
                                            $imagePath = "images/testimonial/" . $roww["img"];
                                            if (file_exists($imagePath)) {
                                                echo '<br><img src="' . htmlspecialchars($imagePath) . '?v=' . time() . '" alt="Current Image" style="width:70px; margin-top:10px;">';
                                            } else {
                                                echo '<br><p>Image file not found</p>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="card-header">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button type="submit" name="publise" class="btn btn-primary btn-lg">
                                                    <?php echo ($edit) ? 'Update' : 'Publish'; ?>
                                                </button>
                                                <a href="view-testimonials.php" class="btn btn-danger">Kembali</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- /.card -->
                        </form>
                    </div>
                    <!-- /.col-->
                </div>
                <!-- ./row -->
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include "footer.php"; ?>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>

<!-- BOOTSTRAP VALIDATION SCRIPT -->
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

</body>
</html>
