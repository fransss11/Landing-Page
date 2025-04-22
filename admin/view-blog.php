<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';
// Tangani penghapusan data sebelum output
if (isset($_GET['delete_id'])) {
  $del = mysqli_real_escape_string($con, $_GET['delete_id']);
  $selectdelete = mysqli_query($con, "SELECT * FROM blog WHERE id=" . $del);
  $selectimg = mysqli_fetch_array($selectdelete);
  $path = 'images/blog/';
  // Cek apakah file gambar ada, jika ada hapus
  if (!empty($selectimg['img']) && file_exists($path . $selectimg['img'])) {
      unlink($path . $selectimg['img']);
  }
  $query_delete = "DELETE FROM blog WHERE id='" . $del . "'";
  $p = mysqli_query($con, $query_delete);
  if ($p) {
      $_SESSION['msg'] = "Berhasil Dihapus";
      $_SESSION['msgClass'] = "success";
  } else {
      $_SESSION['msg'] = "Terjadi kesalahan saat menghapus blog.";
      $_SESSION['msgClass'] = "danger";
  }
  header("Location: view-blog.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php include "title.php"; ?>
  <!-- Meta Responsif -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tema -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <?php include '../includes/logo.php'; ?>
  <style>
    /* Agar tabel tidak melewati layar */
    .table-responsive {
        overflow-x: auto;
        white-space: nowrap;
    }
    /* Styling tambahan untuk tabel agar lebih rapi */
    .table {
        border-collapse: collapse;
        width: 100%;
    }
    /* Pastikan kolom tidak terlalu lebar */
    .table td, .table th {
        word-wrap: break-word;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    /* Pastikan teks dalam tabel tetap rapi */
    td {
        vertical-align: middle;
    }
    /* Pastikan semua kolom sejajar di tengah */
    .table th, .table td {
      padding: 15px;
      border: 1px solid #ddd;
      vertical-align: middle !important;
      text-align: center;
    }
    table.dataTable thead>tr>th.dt-orderable-asc,
    table.dataTable thead>tr>th.dt-orderable-desc,
    table.dataTable thead>tr>td.dt-orderable-asc,
    table.dataTable thead>tr>td.dt-orderable-desc {
        text-align: center;
    }
    /* Lebar gambar lebih kecil agar tidak mendominasi */
    .table img {
        width: 100px;
        height: auto;
        object-fit: contain;
    }
    /* Grup tombol agar tetap sejajar */
    .btn-group {
        display: flex;
        justify-content: center;
        gap: 5px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <?php include "topbar.php"; ?>
  <!-- Sidebar Utama -->
  <?php include "sidebar.php"; ?>
  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Header Konten -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Semua Berita</h1>
          </div>
          <div class="col-sm-6" style="text-align:right;">
              <a class="btn btn-primary" href="add-blog.php">
                  <i class="fa fa-plus" aria-hidden="true"></i> Tambah Baru
              </a>
          </div>
        </div>
      </div>
    </section>
    <!-- Konten Utama -->
    <section class="content">
      <?php if (!empty($_SESSION['msg'])): ?>
        <div style="max-width:600px; margin:0 auto;">
          <div class="alert alert-<?php echo $_SESSION['msgClass']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['msg']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
        <?php 
          unset($_SESSION['msg']); 
          unset($_SESSION['msgClass']);
        ?>
      <?php endif; ?>
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">Lihat</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <!-- Tabel dengan id untuk inisialisasi DataTables -->
            <table id="myTable" class="table">
              <thead>
                <tr>
                  <th>Gambar</th>
                  <th>Judul</th>
                  <th>Kategori</th>
                  <th>Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <!-- Data akan di-load via AJAX -->
              </tbody>
            </table>
          </div>
        </div>
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
<!-- AdminLTE untuk demo -->
<script src="dist/js/demo.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- DataTables JS -->
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script>
  // Inisialisasi DataTables dengan server-side processing
  let table = new DataTable('#myTable', {
    language: {
      search: "Cari :"
    },
    serverSide: true,
    ajax: 'ajax.php?action=fetch_blog',
    order: [], // Nonaktifkan ordering default sehingga server akan mengurutkan berdasarkan id DESC
    lengthChange: false,
    columns: [
      { 
        data: 'img', 
        render: function(data, type, row) {
          return '<img src="images/blog/' + data + '" alt="Gambar">';
        }
      },
      { data: 'title' },
      { data: 'category' },
      { 
        data: 'descrip', 
        render: function(data, type, row) {
          let stripped = data.replace(/(<([^>]+)>)/gi, "");
          return (stripped.length > 100) ? stripped.substr(0, 100) + '...' : stripped;
        }
      },
      { data: 'aksi' }
    ]
  });
</script>
</body>
</html>