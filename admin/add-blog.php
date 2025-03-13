<?php
include 'conn.php';
include 'auth.php';

date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d H:i:s");

// Ambil flash message dari session (jika ada)
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    $msgClass = $_SESSION['msgClass'];
    // Setelah diambil, unset agar tidak muncul lagi saat refresh
    unset($_SESSION['msg'], $_SESSION['msgClass']);
} else {
    $msg = "";
    $msgClass = "";
}

// Check if 'edit' parameter is set in the URL
$edit = isset($_GET['edit']) ? mysqli_real_escape_string($con, $_GET['edit']) : '';

if ($edit) {
    // Mode edit: ambil data dari tabel blog berdasarkan ID
    $resultt = mysqli_query($con, "SELECT * FROM blog WHERE id = '$edit'");
    $roww = mysqli_fetch_array($resultt);
} else {
    // Mode baru (insert): set default kosong
    $roww = ['title' => '', 'category' => '', 'descrip' => '', 'img' => '', 'url' => ''];
}

// Handle form submission
if (isset($_POST['publise'])) {
    // Sanitasi input
    $title   = mysqli_real_escape_string($con, $_POST['title']);
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $descrip = mysqli_real_escape_string($con, $_POST['descrip']);
    $url     = isset($_POST['url']) ? mysqli_real_escape_string($con, $_POST['url']) : '';

    // Handle file upload
    $lis_img = isset($roww["img"]) ? $roww["img"] : '';
    if (!empty($_FILES['lis_img']['name'])) {
        $lis_img   = rand() . '_' . $_FILES['lis_img']['name'];
        $tempname  = $_FILES['lis_img']['tmp_name'];
        $folder    = "images/blog/" . $lis_img;

        // Validasi ekstensi (opsional)
        $valid_ext = array('png', 'jpeg', 'jpg');
        $file_extension = strtolower(pathinfo($folder, PATHINFO_EXTENSION));

        if (in_array($file_extension, $valid_ext)) {
            compressImage($tempname, $folder, 60);
        }
    }

    // Insert (mode baru) atau Update (mode edit)
    if ($edit == '') {
        // Insert
        $insertdata = mysqli_query($con, 
            "INSERT INTO blog (title, category, descrip, img, url, date)
             VALUES ('$title', '$category', '$descrip', '$lis_img', '$url', '$today')"
        );
        if ($insertdata) {
            // Simpan flash message ke session
            $_SESSION['msg'] = "Posted Successfully";
            // Gunakan alert-success untuk warna hijau AdminLTE (bisa ditambah bg-success text-white)
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Error while posting the blog.";
            $_SESSION['msgClass'] = "alert-danger";
        }
        // Redirect agar flash message hanya muncul sekali
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
            $_SESSION['msg'] = "Updated Successfully";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Error while updating the blog.";
            $_SESSION['msgClass'] = "alert-danger";
        }
        // Redirect agar data yang sudah diupdate tetap muncul di form
        header("Location: add-blog.php?edit=" . $edit);
        exit;
    }
}

// Fungsi kompres gambar
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
        // PNG quality: 0 (no compression) - 9 (max compression)
        imagepng($image, $destination, 9);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Blog</title>
    <!-- AdminLTE & Bootstrap CSS -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
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
                        <h1><?php echo ($edit) ? 'Edit Blog' : 'Add Blog'; ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <a href="view-blog.php" class="btn btn-success">
                        <i class="fa fa-eye" aria-hidden="true"></i> View Blog
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <!-- Tampilkan alert jika ada pesan -->
                    <?php if (!empty($msg)): ?>
                        <div style="max-width:600px; margin:0 auto;">
                            <!-- Pastikan class "alert" dan "alert-success" (atau "alert-danger") -->
                            <div class="alert <?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
                                <?php echo $msg; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Form Blog dengan validasi Bootstrap & Summernote -->
                    <form id="blogForm" action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="card card-outline card-info">
                            
                            <!-- Title -->
                            <div class="card-header">
                                <div class="form-group">
                                    <label>Enter Title <span class="text-danger">*</span></label>
                                    <input 
                                        name="title" 
                                        value="<?php echo htmlspecialchars($roww["title"]); ?>" 
                                        type="text" 
                                        class="form-control" 
                                        placeholder="Enter ..." 
                                        required
                                    >
                                    <div class="invalid-feedback">
                                        Please enter a title.
                                    </div>
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="card-header">
                                <div class="form-group">
                                    <label>Select Category <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control" required>
                                        <option value="">Select...</option>
                                        <?php
                                        // Contoh mengambil data kategori dari tabel 'category'
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
                                        Please select a category.
                                    </div>
                                </div>
                            </div>

                            <!-- Description (Summernote) -->
                            <div class="card-body pad">
                                <label>Enter Description <span class="text-danger">*</span></label>
                                <div class="mb-3">
                                    <textarea 
                                        name="descrip" 
                                        class="textarea" 
                                        placeholder="Place some text here" 
                                        style="width: 100%; height: 200px; border: 1px solid #dddddd; padding: 10px;" 
                                        required
                                    ><?php echo htmlspecialchars($roww["descrip"]); ?></textarea>
                                    <div class="invalid-feedback">
                                        Please enter the description.
                                    </div>
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="card-header">
                                <div class="form-group">
                                    <label>
                                        Select Img <span style="color:red;">(only compressed)</span>
                                        <?php if(empty($roww["img"])): ?>
                                            <span class="text-danger">*</span>
                                        <?php endif; ?>
                                    </label>
                                    <p style="color:red;">img size 800px x 500px</p>
                                    <input 
                                        name="lis_img" 
                                        type="file" 
                                        class="form-control"
                                        accept="image/*"
                                        <?php echo empty($roww["img"]) ? 'required' : ''; ?>
                                    >
                                    <div class="invalid-feedback">
                                        Please upload an image.
                                    </div>
                                    <?php 
                                    if (!empty($roww["img"])) {
                                        $imagePath = "images/blog/" . $roww["img"];
                                        if (file_exists($imagePath)) {
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
                                    <a href="view-blog.php" class="btn btn-danger">Kembali</a>
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
  $(function() {
    // Inisialisasi Summernote
    $('.textarea').summernote({
      height: 200
    });

    // Validasi khusus Summernote
    $('#blogForm').on('submit', function() {
      var summernoteContent = $('.textarea').summernote('code');
      if ($('.textarea').summernote('isEmpty') || 
          summernoteContent.trim() === "" || 
          summernoteContent.trim() === "<p><br></p>") {
        $('.note-editor').addClass('is-invalid');
      } else {
        $('.note-editor').removeClass('is-invalid');
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