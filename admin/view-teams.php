<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';
$a = 10;
// Proses hapus data (dijalankan sebelum output HTML)
if (isset($_GET['delete_id'])) {
    $del = intval($_GET['delete_id']);
    // Ambil nama file gambar untuk dihapus
    $stmt = mysqli_prepare($con, "SELECT img FROM teams WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $del);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $img);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);  
    $path = "images/team/";
    if (!empty($img) && file_exists($path . $img)) {
        unlink($path . $img);
    }    
    // Hapus record dari database
    $stmt = mysqli_prepare($con, "DELETE FROM teams WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $del);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = "Berhasil Dihapus";
        $_SESSION['msgClass'] = "success";
    } else {
        $_SESSION['msg'] = "Terjadi kesalahan saat menghapus data";
        $_SESSION['msgClass'] = "danger";
    }
    mysqli_stmt_close($stmt);    
    header("Location: view-teams.php");
    exit();
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
  <!-- Tema (AdminLTE) -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- AdminLTE untuk keperluan demo -->
  <link rel="stylesheet" href="dist/css/demo.css">
  <!-- Summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
  <style>
    .table-responsive {
        overflow-x: auto;
        white-space: nowrap;
        margin-top: 20px;
    }
    .table td, .table th {
        word-wrap: break-word;
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    td {
        vertical-align: middle;
    }
    table.dataTable thead>tr>th.dt-orderable-asc,
    table.dataTable thead>tr>th.dt-orderable-desc,
    table.dataTable thead>tr>td.dt-orderable-asc,
    table.dataTable thead>tr>td.dt-orderable-desc {
        cursor: pointer;
        text-align: center;
    }
    .table img {
        width: 100px;
        height: auto;
        object-fit: contain;
    }
    /* Pastikan semua kolom sejajar di tengah */
    .table th, .table td {
        padding: 15px;
        border: 1px solid #ddd;
        text-align: center;
        vertical-align: middle;
    }
    .btn-group {
        display: flex;
        justify-content: center;
        gap: 5px;
    }
    .btn-group .btn {
        padding: 6px 12px;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 45px;
        min-height: 35px;
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
              <h1>Semua Tim</h1>
           </div>
           <div class="col-sm-6 text-right">
              <a class="btn btn-primary" href="add-teams.php">
                <i class="fa fa-plus"></i> Tambah Baru
              </a>
           </div>
         </div>
      </div>
    </section>
    <section class="content">
      <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])): ?>
         <div style="max-width:600px; margin:0 auto 20px auto;">
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
         endif;
         ?>
      <div class="card card-info">
         <div class="card-header">
           <h3 class="card-title">Lihat</h3>
           <div class="card-tools">
             <button type="button" class="btn btn-tool" data-card-widget="collapse">
               <i class="fas fa-minus"></i>
             </button>
           </div>
         </div>
         <div class="card-body p-0">
           <div class="table-responsive">
             <!-- Tabel dengan ID untuk inisialisasi DataTables -->
             <table id="myTable" class="table">
               <thead>
                 <tr>
                   <th>Gambar</th>
                   <th>Nama</th>
                   <th>Jabatan</th>
                   <!-- <th>Deskripsi</th> -->
                   <th>Facebook</th>
                   <th>Twitter</th>
                   <th>Instagram</th>
                   <th>LinkedIn</th>
                   <th>WhatsApp</th>
                   <th>Aksi</th>
                 </tr>
               </thead>
               <tbody>
                 <!-- Data akan dimuat melalui AJAX -->
               </tbody>
             </table>
           </div>
         </div>
      </div>
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
<!-- AdminLTE untuk keperluan demo -->
<script src="dist/js/demo.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- DataTables JS -->
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    let table = new DataTable('#myTable', {
      language: {
        search: "Cari :"
      },
      serverSide: true,
      ajax: 'ajax.php?action=fetch_teams',
      lengthChange: false,
      order: [[0, 'desc']],
      columns: [
        { 
          data: 'img', 
          render: function(data, type, row) {
            return '<img src="images/team/' + data + '" alt="Gambar Tim">';
          }
        },
        { data: 'title' },
        { data: 'designation' },
        // { data: 'descrip' },
        { data: 'facebook' },
        { data: 'twitter' },
        { data: 'instagram' },
        { data: 'linkedin' },
        { data: 'whatsapp' },
        { data: 'aksi' }
      ]
    });
  });
</script>
</body>
</html>