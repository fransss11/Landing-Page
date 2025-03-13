<?php
include 'conn.php';
include 'auth.php';

date_default_timezone_set('Asia/Kolkata');
$today = date("D d M Y");

// Proses delete (tetap sama)
if (isset($_GET['delete_id'])) {
    $del = mysqli_real_escape_string($con, $_GET['delete_id']);
    $selectdelete = mysqli_query($con, "SELECT * FROM klien WHERE id = $del");
    $selectimg = mysqli_fetch_array($selectdelete);
    $path = 'images/partnership/' . $selectimg['gambar'];
    
    if (file_exists($path)) {
        $now_delete = unlink($path);
        if ($now_delete) {
            $query_delete = "DELETE FROM klien WHERE id = $del";
            $p = mysqli_query($con, $query_delete);
            $_SESSION['msg'] = "Deleted Successfully";
            $_SESSION['msgClass'] = "success";
        } else {
            $_SESSION['msg'] = "Error deleting file.";
            $_SESSION['msgClass'] = "danger";
        }
    } else {
        $query_delete = "DELETE FROM klien WHERE id = $del";
        $p = mysqli_query($con, $query_delete);
        $_SESSION['msg'] = "File not found. Record deleted.";
        $_SESSION['msgClass'] = "warning";
    }
    header("Location: view-partner.php");
    exit;
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
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- AdminLTE for demo purposes -->
  <link rel="stylesheet" href="dist/css/demo.css">
  <!-- Summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
  <style>
    /* Pastikan semua kolom sejajar di tengah */
    .table th, .table td {
        padding: 15px;
        border: 1px solid #ddd;
        text-align: center;
        vertical-align: middle;
    }
    
        table.dataTable thead>tr>th.dt-orderable-asc,table.dataTable thead>tr>th.dt-orderable-desc,table.dataTable thead>tr>td.dt-orderable-asc,table.dataTable thead>tr>td.dt-orderable-desc {
            cursor: pointer;
            text-align: center;
        }
        table.dataTable>tbody>tr>th,table.dataTable>tbody>tr>td {
            padding: 8px 10px;
            text-align: center;
        }
        table.dataTable th.dt-type-numeric,table.dataTable th.dt-type-date,table.dataTable td.dt-type-numeric,table.dataTable td.dt-type-date {
            text-align: center;
        }
  </style>
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
            <h1>All Partnerships</h1>
          </div>
          <div class="col-sm-6" style="text-align:right;">
            <a class="btn btn-primary" href="add-partner.php">
              <i class="fa fa-plus" aria-hidden="true"></i> Add Partnership
            </a>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])): ?>
            <div style="max-width:600px; margin:0 auto 20px auto;">
              <div class="alert alert-<?php echo $_SESSION['msgClass']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['msg']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
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
              <h3 class="card-title">View Partnership</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" 
                        data-toggle="tooltip" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <!-- Tabel dengan ID untuk inisialisasi DataTables -->
              <table id="myTable" class="table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Logo</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data akan diisi melalui AJAX (fetch-partner.php) -->
                </tbody>
              </table>
            </div>
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
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- DataTables JS -->
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script>
  // Inisialisasi DataTables dengan server-side processing
  let table = new DataTable('#myTable', {
    serverSide: true,
    ajax: 'ajax.php?action=fetch_partner' ,  // file PHP yang menangani pengambilan data
    lengthChange: false,
    order: [[0, 'desc']], // Default urutan berdasarkan kolom ID secara DESC
    columns: [
      { data: 'no' },
      { data: 'klien' },
      { data: 'gambar', render: function(data, type, row) {
            return '<img style="width:150px;" src="images/partnership/' + data + '">';
        } 
      },
      { data: 'aksi' }
    ]
  });
</script>
</body>
</html>