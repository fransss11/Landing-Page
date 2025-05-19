<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

include 'conn.php';
include 'auth.php';
date_default_timezone_set('Asia/Jakarta');
$today = date("Y-m-d H:i:s");

// Mode edit?
$edit = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

// Ambil data lama jika edit
if ($edit > 0) {
    $stmt = $con->prepare("SELECT title, descrip, deskripsi, icon, img FROM services WHERE id = ?");
    $stmt->bind_param("i", $edit);
    $stmt->execute();
    $stmt->bind_result($oldTitle, $oldDescrip, $oldDeskripsi, $oldIcon, $oldImg);
    $stmt->fetch();
    $stmt->close();
    $roww = [
        'title'     => $oldTitle,
        'descrip'   => $oldDescrip,
        'deskripsi' => $oldDeskripsi,
        // sekarang 'icon' berisi filename gambar icon
        'icon'      => $oldIcon,
        'img'       => $oldImg,
    ];
} else {
    $roww = [
        'title'     => '',
        'descrip'   => '',
        'deskripsi' => '',
        'icon'      => '',
        'img'       => '',
    ];
}

if (isset($_POST['publise'])) {
    // Ambil input
    $title     = trim($_POST['title']);
    $descrip   = $_POST['descrip'];            // Full description
    $descrip   = preg_replace('/<p[^>]*>(.*?)<\/p>/is', '$1', $descrip);
    $deskripsi = $_POST['deskripsi'];          // Short description
    $deskripsi = preg_replace('/<p[^>]*>(.*?)<\/p>/is', '$1', $deskripsi);

    // Handle upload gambar 'img'
    if (!empty($_FILES['lis_img']['name'])) {
        if ($_FILES['lis_img']['size'] > 5*1024*1024) {
            $_SESSION['msg'] = "Ukuran gambar layanan harus < 5MB.";
            $_SESSION['msgClass'] = "alert-danger";
            header("Location: add-services.php?edit={$edit}");
            exit;
        }
        $newFile = time().'_'.basename($_FILES['lis_img']['name']);
        $target  = "images/services/{$newFile}";
        if (move_uploaded_file($_FILES['lis_img']['tmp_name'], $target)) {
            if ($edit>0 && !empty($roww['img']) && file_exists("images/services/{$roww['img']}")) {
                unlink("images/services/{$roww['img']}");
            }
            $lis_img = $newFile;
        } else {
            $lis_img = $roww['img'];
        }
    } else {
        $lis_img = $roww['img'];
    }

    // Handle upload gambar 'icon'
    if (!empty($_FILES['icon_img']['name'])) {
        if ($_FILES['icon_img']['size'] > 5*1024*1024) {
            $_SESSION['msg'] = "Ukuran icon harus < 5MB.";
            $_SESSION['msgClass'] = "alert-danger";
            header("Location: add-services.php?edit={$edit}");
            exit;
        }
        $iconFile = time().'_'.basename($_FILES['icon_img']['name']);
        $iconPath = "images/services/{$iconFile}";
        if (move_uploaded_file($_FILES['icon_img']['tmp_name'], $iconPath)) {
            if ($edit>0 && !empty($roww['icon']) && file_exists("images/services/{$roww['icon']}")) {
                unlink("images/services/{$roww['icon']}");
            }
            $icon = $iconFile;
        } else {
            $icon = $roww['icon'];
        }
    } else {
        $icon = $roww['icon'];
    }

    if ($edit === 0) {
        $stmt = $con->prepare("
            INSERT INTO services
              (title, descrip, deskripsi, icon, img, date)
            VALUES (?,?,?,?,?,?)
        ");
        $stmt->bind_param("ssssss",
            $title, $descrip, $deskripsi, $icon, $lis_img, $today
        );
    } else {
        $stmt = $con->prepare("
            UPDATE services SET
              title     = ?,
              descrip   = ?,
              deskripsi = ?,
              icon      = ?,
              img       = ?,
              date      = ?
            WHERE id = ?
        ");
        $stmt->bind_param("ssssssi",
            $title, $descrip, $deskripsi, $icon, $lis_img, $today, $edit
        );
    }

    if ($stmt->execute()) {
        $_SESSION['msg']      = $edit===0 ? "Layanan ditambahkan." : "Layanan diperbarui.";
        $_SESSION['msgClass'] = "alert-success";
    } else {
        $_SESSION['msg']      = "Error: ".$stmt->error;
        $_SESSION['msgClass'] = "alert-danger";
    }
    $stmt->close();

    $loc = $edit===0 ? "add-services.php" : "add-services.php?edit={$edit}";
    header("Location: $loc");
    exit;
}

// Hapus gambar layanan
if (isset($_POST['delete_img']) && $edit>0) {
    if (!empty($roww['img']) && file_exists("images/services/{$roww['img']}")) {
        unlink("images/services/{$roww['img']}");
    }
    $u = $con->prepare("UPDATE services SET img = '' WHERE id = ?");
    $u->bind_param("i", $edit); $u->execute(); $u->close();
    $_SESSION['msg']="Gambar layanan dihapus."; $_SESSION['msgClass']="alert-success";
    header("Location: add-services.php?edit={$edit}"); exit;
}

// Hapus icon
if (isset($_POST['delete_icon']) && $edit>0) {
    if (!empty($roww['icon']) && file_exists("images/services/{$roww['icon']}")) {
        unlink("images/services/{$roww['icon']}");
    }
    $u = $con->prepare("UPDATE services SET icon = '' WHERE id = ?");
    $u->bind_param("i", $edit); $u->execute(); $u->close();
    $_SESSION['msg']="Icon dihapus."; $_SESSION['msgClass']="alert-success";
    header("Location: add-services.php?edit={$edit}"); exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <?php include "title.php"; ?>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <?php include '../includes/logo.php'; ?>
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
                    <h1><?php echo $edit>0?'Perbarui Layanan':'Tambah Layanan'; ?></h1>
                </div>
                <div class="col-sm-6">
                    <a href="view-services.php" class="btn btn-success">
                    <i class="fa fa-eye" aria-hidden="true"></i> Lihat Berita
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
      <div class="row">
          <div class="col-md-8">
            <?php if(!empty($_SESSION['msg'])): ?>
              <div class="alert <?php echo $_SESSION['msgClass']; ?> alert-dismissible">
                <?php echo $_SESSION['msg']; unset($_SESSION['msg'],$_SESSION['msgClass']); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
            <?php endif; ?>

            <form id="serviceForm" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
              <div class="card card-info">
                <div class="card-header"><h3 class="card-title">Form Layanan</h3></div>
                <div class="card-body">
                  <!-- Judul -->
                  <div class="form-group">
                    <label>Judul <span class="text-danger">*</span></label>
                    <input name="title" type="text" class="form-control" required
                      value="<?php echo htmlspecialchars($roww['title']); ?>">
                    <div class="invalid-feedback">Wajib diisi.</div>
                  </div>
                  <!-- Deskripsi Lengkap -->
                  <div class="form-group">
                    <label>Deskripsi Lengkap <span class="text-danger">*</span></label>
                    <textarea name="descrip" class="form-control textarea" required><?php
                      echo htmlspecialchars($roww['descrip']);
                    ?></textarea>
                    <div class="invalid-feedback">Wajib diisi.</div>
                  </div>
                  <!-- Deskripsi Singkat -->
                  <div class="form-group">
                    <label>Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control textarea-short"><?php
                      echo htmlspecialchars($roww['deskripsi']);
                    ?></textarea>
                  </div>
                  <!-- Icon Image -->
                  <div class="form-group">
                    <label>Icon Gambar <?php echo empty($roww['icon'])?'<span class="text-danger">*</span>':''; ?></label>
                    <input name="icon_img" type="file" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Maks 5MB.</small>
                    <?php if(!empty($roww['icon']) && file_exists("images/services/{$roww['icon']}")): ?>
                      <div class="mt-2">
                        <img src="images/services/<?php echo htmlspecialchars($roww['icon']); ?>"
                          style="width:100px" alt="Icon sekarang">
                        <button type="submit" name="delete_icon" class="btn btn-danger btn-sm"
                          onclick="return confirm('Hapus icon?')">Hapus Icon</button>
                      </div>
                    <?php endif; ?>
                  </div>
                  <!-- Gambar Layanan -->
                  <div class="form-group">
                    <label>Gambar Layanan <?php echo empty($roww['img'])?'<span class="text-danger">*</span>':''; ?></label>
                    <input name="lis_img" type="file" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Maks 5MB.</small>
                    <?php if(!empty($roww['img']) && file_exists("images/services/{$roww['img']}")): ?>
                      <div class="mt-2">
                        <img src="images/services/<?php echo htmlspecialchars($roww['img']); ?>"
                          style="width:150px" alt="Gambar sekarang">
                        <button type="submit" name="delete_img" class="btn btn-danger btn-sm"
                          onclick="return confirm('Hapus gambar layanan?')">Hapus Gambar</button>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="card-footer">
                  <button name="publise" type="submit" class="btn btn-primary">
                    <?php echo $edit>0?'Perbarui':'Tambahkan'; ?>
                  </button>
                  <a href="view-services.php" class="btn btn-default">Batal</a>
                </div>
              </div>
            </form>
          </div>
      </div>
    </section>
  </div>
  <?php include "footer.php"; ?>
</div>

<!-- JS -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script>
$(function() {
  // Inisialisasi Summernote untuk dua field
  $('.textarea, .textarea-short').summernote({
    height: 300,
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'italic', 'underline', 'clear', 'fontname']],
      ['fontsize', ['fontsize']], // Menambahkan dropdown ukuran font
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['height', ['height']],
      ['view', ['fullscreen', 'codeview', 'help']]
    ]
  });
  // Validasi Bootstrap
  $('#serviceForm').on('submit', function(e) {
    if (!this.checkValidity()) {
      e.preventDefault();
      e.stopPropagation();
    }
    $(this).addClass('was-validated');
  });
});
</script>
</body>
</html>
