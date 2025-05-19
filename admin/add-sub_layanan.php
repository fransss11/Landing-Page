<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
error_reporting(0);

include 'conn.php';
include 'auth.php';
date_default_timezone_set('Asia/Jakarta');
$today = date("Y-m-d H:i:s");

// Mode edit?
$edit = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

// Ambil daftar layanan untuk select box
$serviceTitles = [];
$res = mysqli_query($con, "SELECT id, title FROM services");
while ($row = mysqli_fetch_assoc($res)) {
    $serviceTitles[] = $row;
}

// Jika edit, ambil data kategori
$categoryData = [];
if ($edit > 0) {
    $q = "SELECT * FROM service_categories WHERE id = $edit";
    $r = mysqli_query($con, $q);
    $categoryData = mysqli_fetch_assoc($r);
}

// Handle form submit
if (isset($_POST['add_category'])) {
    $serviceId     = intval($_POST['service_id']);
    $name          = mysqli_real_escape_string($con, $_POST['name']);
    $description   = mysqli_real_escape_string($con, $_POST['description']);
    $offline_price = ($_POST['offline_price'] !== '') ? floatval($_POST['offline_price']) : null;
    $online_price  = ($_POST['online_price']  !== '') ? floatval($_POST['online_price'])  : null;

    if ($edit > 0) {
        // Update
        $upd = sprintf(
            "UPDATE service_categories 
             SET name='%s', description='%s',
                 offline_price=%s, online_price=%s,
                 core='%d'
             WHERE id=%d",
            $name,
            $description,
            ($offline_price  !== null ? $offline_price  : "NULL"),
            ($online_price   !== null ? $online_price   : "NULL"),
            $serviceId,
            $edit
        );
        $ok = mysqli_query($con, $upd);
        $_SESSION['msg']      = $ok ? "Kategori berhasil diperbarui." : "Error saat memperbarui.";
        $_SESSION['msgClass'] = $ok ? "alert-success" : "alert-danger";
        header("Location: add-sub_layanan.php?edit=$edit");
        exit;
    } else {
        // Insert baru
        $ins = sprintf(
            "INSERT INTO service_categories 
             (name, description, offline_price, online_price, core, created_at)
             VALUES ('%s','%s',%s,%s,'%d',NOW())",
            $name,
            $description,
            ($offline_price  !== null ? $offline_price  : "NULL"),
            ($online_price   !== null ? $online_price   : "NULL"),
            $serviceId
        );
        $ok = mysqli_query($con, $ins);
        $_SESSION['msg']      = $ok ? "Kategori berhasil ditambahkan." : "Error saat menambahkan.";
        $_SESSION['msgClass'] = $ok ? "alert-success" : "alert-danger";
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
                <!-- Harga Offline -->
                <div class="form-group">
                  <label>Harga Offline <span class="text-danger">*</span></label>
                  <input name="offline_price" type="number" step="0.01" class="form-control"
                          value="<?= htmlspecialchars($categoryData['offline_price'] ?? ''); ?>"
                          placeholder="Harga Offline ...">
                  <div class="invalid-feedback">Silakan masukkan harga offline.</div>
                </div>
                <!-- Harga Online -->
                <div class="form-group">
                  <label>Harga Online <span class="text-danger">*</span></label>
                  <input name="online_price" type="number" step="0.01" class="form-control"
                          value="<?= htmlspecialchars($categoryData['online_price'] ?? ''); ?>"
                          placeholder="Harga Online ...">
                  <div class="invalid-feedback">Silakan masukkan harga online.</div>
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