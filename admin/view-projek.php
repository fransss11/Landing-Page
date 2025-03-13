<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';

// Tangani penghapusan data sebelum output
if (isset($_GET['delete_id'])) {
  $del = mysqli_real_escape_string($con, $_GET['delete_id']);
  $selectdelete = mysqli_query($con, "SELECT * FROM projek WHERE id=" . $del);
  $selectimg = mysqli_fetch_array($selectdelete);
//   $path = 'images/projek/';
  
//   // Cek apakah file gambar ada, jika ada hapus
//   if (!empty($selectimg['img']) && file_exists($path . $selectimg['img'])) {
//       unlink($path . $selectimg['img']);
//   }
  
  $query_delete = "DELETE FROM projek WHERE id='" . $del . "'";
  $p = mysqli_query($con, $query_delete);
  if ($p) {
      $_SESSION['msg'] = "Deleted Successfully";
      $_SESSION['msgClass'] = "success";
  } else {
      $_SESSION['msg'] = "Error while deleting the projek.";
      $_SESSION['msgClass'] = "danger";
  }
  header("Location: view-projek.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php include "title.php"; ?>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- AdminLTE Style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
        /* Agar tabel tidak melewati layar */
        .table-responsive {
            overflow-x: auto;
            white-space: nowrap;
            margin-top: 20px;
        }

        /* Styling tambahan untuk tabel agar lebih rapi */
        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th, .table td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        /* Mengatur kolom agar lebih fleksibel */
        .table td, .table th {
            word-wrap: break-word;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        table.dataTable thead>tr>th.dt-orderable-asc,table.dataTable thead>tr>th.dt-orderable-desc,table.dataTable thead>tr>td.dt-orderable-asc,table.dataTable thead>tr>td.dt-orderable-desc {
            text-align: center;
        }

        table.dataTable th.dt-type-numeric,table.dataTable th.dt-type-date,table.dataTable td.dt-type-numeric,table.dataTable td.dt-type-date {
            text-align: center;
        }

        /* Gambar yang ditampilkan di dalam tabel */
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

        /* Styling untuk pesan alert */
        .alert {
            max-width: 600px;
            margin: 0 auto;
        }

        /* Responsif pada layar kecil */
        @media (max-width: 768px) {
            .table th, .table td {
                font-size: 12px;
                padding: 10px;
            }
        }

  </style>
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
            <h1>All Projek</h1>
          </div>
          <div class="col-sm-6" style="text-align:right;">
              <a class="btn btn-primary" href="add-projek.php">
                  <i class="fa fa-plus" aria-hidden="true"></i> Add New
              </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <section class="content">
      <?php if (!empty($_SESSION['msg'])): ?>
        <div class="alert alert-<?php echo $_SESSION['msgClass']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['msg']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php 
          unset($_SESSION['msg']); 
          unset($_SESSION['msgClass']);
        ?>
      <?php endif; ?>

      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">View Projek</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="myTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Judul</th>
                  <th>Tahun</th>
                  <th>Deskripsi</th>
                  <th>Upload</th>
                  <th>Action</th>
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
  </div>

  <?php include "footer.php"; ?>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- DataTables JS -->
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script>
  let table = new DataTable('#myTable', {
    serverSide: true,
    ajax: 'ajax.php?action=fetch_projek',
    lengthChange: true,
    pageLength: 10,
    searching: true,
    order: [],
    columns: [
      { data: 'judul' },
      { data: 'tahun' },
      { 
        data: 'deskrip', 
        render: function(data, type, row) {
          let stripped = data.replace(/(<([^>]+)>)/gi, "");
          return (stripped.length > 100) ? stripped.substr(0, 100) + '...' : stripped;
        }
      },
      { data: 'upload'},
      { data: 'aksi' }
    ]
  });
</script>
</body>
</html>
