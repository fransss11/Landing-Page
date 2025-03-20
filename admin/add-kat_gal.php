<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';
$a = 6;
// Ambil pesan flash jika ada
if(isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    $msgClass = $_SESSION['msgClass'];
    unset($_SESSION['msg'], $_SESSION['msgClass']);
} else {
    $msg = "";
    $msgClass = "";
}
// Proses hapus kategori jika parameter delete_id ada
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($con, $_GET['delete_id']);
    $query_delete = "DELETE FROM kategori_gal WHERE id = '$delete_id'";
    if (mysqli_query($con, $query_delete)) {
        $_SESSION['msg'] = "Berhasil Dihapus";
        $_SESSION['msgClass'] = "alert-success";
    } else {
        $_SESSION['msg'] = "Terjadi kesalahan saat menghapus kategori";
        $_SESSION['msgClass'] = "alert-danger";
    }
    header("Location: add-kat_gal.php");
    exit;
}
// Ambil data untuk mode edit
$edit = isset($_GET['edit']) ? mysqli_real_escape_string($con, $_GET['edit']) : '';
$roww = [];
if ($edit != '') {
    $resultt = mysqli_query($con, "SELECT * FROM kategori_gal WHERE id = '$edit'");
    $roww = mysqli_fetch_array($resultt);
}
// Tangani pengiriman form untuk menambah atau memperbarui kategori
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($con, $_POST['kat_gal']);   
    // Cek apakah kategori sudah ada (hanya untuk mode insert)
    if ($edit == '') {
        $checkQuery = mysqli_query($con, "SELECT * FROM kategori_gal WHERE kat_gal = '$name'");
        if (mysqli_num_rows($checkQuery) > 0) {
            $_SESSION['msg'] = "Kategori sudah ada!";
            $_SESSION['msgClass'] = "alert-danger";
            header("Location: add-kat_gal.php");
            exit;
        }
    }
    if ($edit == '') {
        // Insert kategori baru
        if (mysqli_query($con, "INSERT INTO kategori_gal (kat_gal) VALUES ('$name')")) {
            $_SESSION['msg'] = "Berhasil Ditambahkan";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat menambahkan kategori";
            $_SESSION['msgClass'] = "alert-danger";
        }
        header("Location: add-kat_gal.php");
        exit;
    } else {
        // Update kategori
        if (mysqli_query($con, "UPDATE kategori_gal SET kat_gal = '$name' WHERE id = '$edit'")) {
            $_SESSION['msg'] = "Berhasil Diperbarui";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat memperbarui kategori";
            $_SESSION['msgClass'] = "alert-danger";
        }
        header("Location: add-kat_gal.php?edit=" . $edit);
        exit;
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
  <!-- Tema style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    /* Bungkus alert agar tidak melebar penuh dan terpusat */
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
            <h1>Tambah Kategori Baru</h1>
          </div>
        </div>
      </div>
    </section>
    <!-- Konten Utama -->
    <section class="content">
      <div class="row">
        <!-- Bagian Form -->
        <div class="col-md-5">
          <!-- Tampilkan alert jika ada pesan -->
          <?php if (!empty($msg)): ?>
            <div class="alert-container">
              <div class="alert <?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
                <?php echo $msg; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
          <?php endif; ?>
          <!-- Form dengan validasi Bootstrap -->
          <form action="" method="post" class="needs-validation" novalidate>
            <div class="card card-outline card-info">
              <div class="card-header">
                <div class="form-group">
                  <label for="validationCategoryName" class="form-label">Masukkan Nama Kategori <span class="text-danger">*</span></label>
                  <input 
                    type="text" 
                    name="kat_gal" 
                    id="validationCategoryName"
                    value="<?php echo isset($roww['kat_gal']) ? htmlspecialchars($roww['kat_gal']) : ''; ?>" 
                    class="form-control" 
                    placeholder="Masukkan ..." 
                    required
                  >
                  <div class="invalid-feedback">
                    Silahkan masukkan nama kategori
                  </div>
                </div>
              </div>
              <button type="submit" name="add" class="btn btn-block btn-primary btn-lg">Tambahkan</button>
              <a href="add-kat_gal.php" class="btn btn-danger">Kembali</a>
            </div>
          </form>
        </div>
        <!-- Tabel Kategori -->
        <div class="col-md-7">
          <div class="card card-outline card-info">
            <div class="card-header">
              <label>Semua Kategori</label>
            </div>
            <div class="card-header">
              <table class="table">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th style="width:80px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $location = mysqli_query($con, "SELECT * FROM kategori_gal");
                  while ($location_ft = mysqli_fetch_array($location)) { ?>
                    <tr>
                      <td><?php echo htmlspecialchars($location_ft["kat_gal"]); ?></td>
                      <td class="text-right py-0 align-middle">
                        <div class="btn-group btn-group-sm">
                          <a 
                            href="add-kat_gal.php?edit=<?php echo $location_ft["id"]; ?>" 
                            onclick="return confirm('Anda yakin?')" 
                            class="btn btn-info"
                          >
                            <i class="fas fa-edit"></i>
                          </a>
                          <a 
                            href="add-kat_gal.php?delete_id=<?php echo $location_ft["id"]; ?>" 
                            onclick="return confirm('Anda yakin?')" 
                            class="btn btn-danger"
                          >
                            <i class="fas fa-trash"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div> <!-- /.card-header -->
          </div> <!-- /.card -->
        </div> <!-- /.col-md-7 -->
      </div> <!-- /.row -->
    </section>
  </div>
  <?php include "footer.php"; ?>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- SCRIPT VALIDASI BOOTSTRAP ala dokumentasi -->
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
</body>
</html>