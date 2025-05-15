<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
error_reporting(0);
include 'conn.php';
include 'auth.php';
date_default_timezone_set('Asia/Jakarta');
$today = date("Y-m-d H:i:s");

// Cek apakah parameter 'edit' ada di URL dan valid
$edit = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

// Fetch titles from the `services` table
$serviceTitles = [];
$result = mysqli_query($con, "SELECT id, title FROM services");
while ($row = mysqli_fetch_assoc($result)) {
    $serviceTitles[] = $row;
}

// Jika dalam mode edit, ambil data dari database
$categoryData = [];
if ($edit > 0) {
    $query = "SELECT * FROM service_categories WHERE id = $edit";
    $result = mysqli_query($con, $query);
    $categoryData = mysqli_fetch_assoc($result);
}

// Handle form submission for service categories
if (isset($_POST['add_category'])) {
    $serviceId = intval($_POST['service_id']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $price = !empty($_POST['price']) ? floatval($_POST['price']) : null;

    if ($edit > 0) {
        // Update data jika dalam mode edit
        $updateQuery = "UPDATE service_categories 
                        SET name = '$name', description = '$description', price = '$price', core = '$serviceId' 
                        WHERE id = $edit";
        $updateResult = mysqli_query($con, $updateQuery);
        if ($updateResult) {
            $_SESSION['msg'] = "Kategori layanan berhasil diperbarui.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat memperbarui kategori layanan.";
            $_SESSION['msgClass'] = "alert-danger";
        }
        header("Location: add-sub_layanan.php?edit=$edit");
        exit;
    } else {
        // Insert data baru jika tidak dalam mode edit
        $insertQuery = "INSERT INTO service_categories (name, description, price, core, created_at) 
                        VALUES ('$name', '$description', '$price', '$serviceId', NOW())";
        $insertResult = mysqli_query($con, $insertQuery);
        if ($insertResult) {
            $_SESSION['msg'] = "Kategori layanan berhasil ditambahkan.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat menambahkan kategori layanan.";
            $_SESSION['msgClass'] = "alert-danger";
        }
        header("Location: add-sub_layanan.php");
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
  <?php include '../includes/logo.php'; ?>
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
            <h1><?php echo ($edit > 0) ? 'Perbarui Sub-Layanan' : 'Tambah Sub-Layanan'; ?></h1>
          </div>
          <div class="col-sm-6">
            <a href="view-sub_layanan.php" class="btn btn-success">
              <i class="fa fa-eye" aria-hidden="true"></i> Lihat Sub-Layanan
            </a>
          </div>
        </div>
      </div>
    </section>
    <!-- Konten Utama -->
    <section class="content">
      <div class="row">
        <div class="col-md-8">
          <!-- Tampilkan pesan jika ada (menggunakan session) -->
          <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])): ?>
            <div style="max-width: 600px; margin: 0 auto;">
              <div class="alert <?php echo $_SESSION['msgClass']; ?> alert-dismissible fade show" role="alert">
                <?php 
                  echo $_SESSION['msg'];
                  // Hapus session agar pesan tidak muncul lagi setelah refresh
                  unset($_SESSION['msg']); 
                  unset($_SESSION['msgClass']);
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
          <?php endif; ?>          
          <!-- Form untuk menambahkan kategori layanan -->
          <form id="categoryForm" action="" method="post" novalidate>
            <div class="card card-outline card-info">
              <!-- Pilih Layanan -->
              <div class="card-header">
                <div class="form-group">
                  <label>Pilih Layanan <span class="text-danger">*</span></label>
                  <select name="service_id" class="form-control" required>
                    <option value="">-- Pilih Layanan --</option>
                    <?php foreach ($serviceTitles as $service): ?>
                      <option value="<?php echo $service['id']; ?>" 
                              <?php echo (isset($categoryData['core']) && $categoryData['core'] == $service['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($service['title']); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                    Silakan pilih layanan.
                  </div>
                </div>
              </div>
              <!-- Nama Kategori -->
              <div class="card-header">
                <div class="form-group">
                  <label>Nama Kategori <span class="text-danger">*</span></label>
                  <input 
                    name="name" 
                    type="text" 
                    class="form-control" 
                    placeholder="Nama kategori ..." 
                    maxlength="255" 
                    value="<?php echo isset($categoryData['name']) ? htmlspecialchars($categoryData['name']) : ''; ?>" 
                    required
                  >
                  <div class="invalid-feedback">
                    Silakan masukkan nama kategori.
                  </div>
                </div>
              </div>
              <!-- Deskripsi -->
              <div class="card-body pad">
                <div class="form-group">
                  <label>Deskripsi</label>
                  <textarea 
                    name="description" 
                    class="form-control textarea" 
                    placeholder="Masukkan deskripsi kategori ..." 
                    rows="4" 
                    maxlength="1000"
                  ><?php echo isset($categoryData['description']) ? htmlspecialchars($categoryData['description']) : ''; ?></textarea>
                  <div class="invalid-feedback">
                    Silakan masukkan deskripsi.
                  </div>
                </div>
              </div>
              <!-- Harga -->
              <div class="card-header">
                <div class="form-group">
                  <label>Harga</label>
                  <input 
                    name="price" 
                    type="number" 
                    step="0.01" 
                    class="form-control" 
                    placeholder="Harga ..." 
                    value="<?php echo isset($categoryData['price']) ? htmlspecialchars($categoryData['price']) : ''; ?>"
                    required
                  >
                  <div class="invalid-feedback">
                    Silakan masukkan harga.
                  </div>
                </div>
              </div>
              <!-- Tombol Kirim -->
              <div class="card-header">
                <div class="form-group">
                  <button type="submit" name="add_category" class="btn btn-primary btn-lg">
                    <?php echo ($edit > 0) ? 'Perbarui' : 'Tambahkan'; ?>
                  </button>
                  <a href="view-sub_layanan.php" class="btn btn-danger btn-lg">Kembali</a>
                </div>
              </div>
            </div>
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
    $(document).ready(function() {
      $('.textarea').summernote({
        height: 200,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear', 'fontname']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['height', ['height']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

      // Validasi form
      $('#categoryForm').on('submit', function(event) {
        var form = this;
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      });
    });
</script>
</body>
</html>