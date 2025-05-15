<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';
date_default_timezone_set('Asia/Jakarta');
$today = date("Y-m-d H:i:s");

// Fetch the existing data from the `jam_kerja` table
$query  = "SELECT * FROM jam_kerja LIMIT 1";
$result = mysqli_query($con, $query);
$row    = mysqli_fetch_assoc($result);

// Flag to check if data exists
$dataExists = ($row) ? true : false;

// Handle form submission
if (isset($_POST['save'])) {
    $deskripsi = mysqli_real_escape_string($con, $_POST['deskripsi']);
    $waktu     = mysqli_real_escape_string($con, $_POST['waktu']);

    // If no data exists, insert new data; otherwise, update the existing data
    if (!$dataExists) {
        $sql = "INSERT INTO jam_kerja (deskripsi, waktu) 
                VALUES ('$deskripsi', '$waktu')";
        $exec = mysqli_query($con, $sql);
        if ($exec) {
            $_SESSION['msg'] = "Data berhasil ditambahkan.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat menambahkan data.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    } else {
        $sql = "UPDATE jam_kerja SET 
                    deskripsi = '$deskripsi',
                    waktu     = '$waktu'
                WHERE id_jam = '".$row['id_jam']."'";
        $exec = mysqli_query($con, $sql);
        if ($exec) {
            $_SESSION['msg'] = "Data berhasil diperbarui.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat memperbarui data.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    }
    // Redirect to avoid form resubmission
    header("Location: jam_kerja.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include '../includes/logo.php'; ?>
    <!-- Summernote CSS telah dihapus karena tidak digunakan lagi -->
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include "topbar.php"; ?>
    <?php include "sidebar.php"; ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Edit Jam Operasional</h1>
        </section>
        <section class="content">
            <div class="container">
                <!-- Display session alert -->
                <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])): ?>
                    <div class="alert <?php echo $_SESSION['msgClass']; ?> alert-dismissible fade show" role="alert">
                        <?php 
                            echo $_SESSION['msg'];
                            unset($_SESSION['msg']); 
                            unset($_SESSION['msgClass']);
                        ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <!-- Form to edit/add Jam Kerja -->
                <form action="" method="post" class="row g-3 needs-validation" novalidate style="margin: 0;">
                    <!-- Deskripsi -->
                    <div class="col-md-12">
                        <label for="validationDeskripsi" class="form-label">Deskripsi</label>
                        <!-- Menggunakan textarea biasa tanpa inisialisasi Summernote -->
                        <textarea name="deskripsi" class="form-control" id="validationDeskripsi" rows="4"><?php 
                            echo ($dataExists) ? htmlspecialchars($row['deskripsi']) : ''; 
                        ?></textarea>
                        <div class="invalid-feedback">
                            Mohon isi deskripsi.
                        </div>
                    </div>
                    <!-- Waktu -->
                    <div class="col-md-12">
                        <label for="validationWaktu" class="form-label">Waktu</label>
                        <input type="text" name="waktu" class="form-control" id="validationWaktu"
                               value="<?php echo ($dataExists) ? htmlspecialchars($row['waktu']) : ''; ?>"
                               placeholder="Contoh: Senin - Jumat: 08:00 - 17:00" required>
                        <div class="invalid-feedback">
                            Mohon isi waktu.
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div style="padding-top: 3%;" class="col-12">
                        <button type="submit" name="save" class="btn btn-primary btn-lg">Simpan</button>
                        <button type="reset" class="btn btn-danger btn-lg">Reset</button> 
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
<script>
  // Starter JavaScript for Bootstrap validation
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
</body>
</html>
