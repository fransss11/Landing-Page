<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';
$a = 9;
// Proses Hapus (dijalankan sebelum output HTML)
if (isset($_GET['delete_id'])) {
    $del = mysqli_real_escape_string($con, $_GET['delete_id']);
    // Ambil data kegiatan untuk menghapus gambar jika ada
    $selectdelete = mysqli_query($con, "SELECT * FROM kegiatan WHERE id=" . $del);
    $selectimg = mysqli_fetch_array($selectdelete);
    $path = 'images/kegiatan/';
    // Hapus file gambar jika ada
    if (!empty($selectimg['url']) && file_exists($path . $selectimg['url'])) {
        unlink($path . $selectimg['url']);
    }    
    // Hapus record dari database
    $query_delete = "DELETE FROM kegiatan WHERE id='" . $del . "'";
    $p = mysqli_query($con, $query_delete);
    if ($p) {
        $_SESSION['msg'] = "Berhasil Dihapus";
        $_SESSION['msgClass'] = "success";
    } else {
        $_SESSION['msg'] = "Terjadi kesalahan saat menghapus kegiatan.";
        $_SESSION['msgClass'] = "danger";
    }
    header("Location: view-kegiatan.php");
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
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
  <!-- Google Font: Source Sans Pro -->
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
    /* Pastikan semua kolom sejajar di tengah */
    .table th, .table td {
        padding: 15px;
        border: 1px solid #ddd;
        text-align: center;
        vertical-align: middle;
    }
    /* Pastikan teks dalam tabel tetap rapi */
    td {
        vertical-align: middle;
    }
    table.dataTable thead>tr>th.dt-orderable-asc,
    table.dataTable thead>tr>th.dt-orderable-desc,
    table.dataTable thead>tr>td.dt-orderable-asc,
    table.dataTable thead>tr>td.dt-orderable-desc {
        text-align: center;
    }
    /* Lebar gambar lebih kecil agar tidak mendominasi */
    .table img {
        width: 80px;
        height: auto;
        object-fit: contain;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <?php include "topbar.php"; ?>
  <!-- Sidebar Utama -->
  <?php include "sidebar.php"; ?>
  <!-- Content Wrapper. Berisi konten halaman -->
  <div class="content-wrapper">
    <!-- Header Konten -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Semua Kegiatan</h1>
          </div>
          <div class="col-sm-6" style="text-align:right;">
            <a class="btn btn-primary" href="add-kegiatan.php">
              <i class="fa fa-plus" aria-hidden="true"></i> Tambah Baru
            </a>
          </div>
        </div>
      </div>
    </section>
    <!-- Konten Utama -->
    <section class="content">
      <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])): ?>
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
            <h3 class="card-title">Lihat Kegiatan</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" 
                        data-toggle="tooltip" title="Sembunyikan">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <!-- Tabel dengan id untuk inisialisasi DataTables -->
            <table id="myTable" class="table">
              <thead>
                <tr>
                  <th>Nama Kegiatan</th>
                  <th>Deskripsi</th>
                  <th>URL</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <!-- Data akan di-load melalui AJAX -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
    <!-- /.konten -->
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
<!-- DataTables JS -->
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script>
  // Inisialisasi DataTables dengan server-side processing
  let table = new DataTable('#myTable', {
    language: {
      search: "Cari :"
    },
    serverSide: true,
    ajax: 'ajax.php?action=fetch_kegiatan',
    order: [], // Nonaktifkan ordering default sehingga server akan mengurutkan berdasarkan id DESC
    lengthChange: false,
    columns: [
      { data: 'nama_kegiatan' },
      { data: 'deskripsi' },
      { 
        data: 'url', 
        render: function(data, type, row) {
          return data ? '<a href="' + data + '" target="_blank">' + data + '</a>' : '-';
        }
      },
      { data: 'aksi' }
    ]
  });
</script>
</body>
</html>