<?php
ob_start();
include 'conn.php';
include 'auth.php';
date_default_timezone_set('Asia/Kolkata');
$today = date("D d M Y");
// Proses hapus data jika ada parameter delete_id
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);  
    // Ambil nama file foto untuk dihapus
    $stmtSelect = $con->prepare("SELECT foto FROM media WHERE id = ?");
    $stmtSelect->bind_param("i", $delete_id);
    $stmtSelect->execute();
    $resultSelect = $stmtSelect->get_result();
    $rowSelect = $resultSelect->fetch_assoc();
    $stmtSelect->close();    
    // Jika ada file, hapus file fisik
    if ($rowSelect && !empty($rowSelect['foto'])) {
        $filePath = "uploads/" . $rowSelect['foto'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }    
    // Hapus record dari database
    $stmtDelete = $con->prepare("DELETE FROM media WHERE id = ?");
    $stmtDelete->bind_param("i", $delete_id);
    if ($stmtDelete->execute()) {
        $_SESSION['msg'] = "Berhasil Dihapus";
        $_SESSION['msgClass'] = "success";
    } else {
        $_SESSION['msg'] = "Terjadi kesalahan saat menghapus data.";
        $_SESSION['msgClass'] = "danger";
    }
    $stmtDelete->close();
    // Redirect agar mencegah reload mengulangi proses hapus
    header("Location: view-gallery.php");
    exit();
}
ob_end_flush();
// Query untuk mengambil data galeri
$query = "
    SELECT 
        media.id, 
        media.foto, 
        media.galery, 
        media.uploaded_on, 
        kategori_gal.kat_gal 
    FROM media 
    LEFT JOIN kategori_gal ON media.kategori = kategori_gal.kat_gal
    ORDER BY media.id DESC
";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <?php include "title.php"; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <!-- CSS -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
  <style>
    .table-responsive {
        overflow-x: auto;
        white-space: nowrap;
    }
    .table td, .table th {
        word-wrap: break-word;
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: center;
        vertical-align: middle !important;
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
    .btn-group {
        display: flex;
        justify-content: center;
        gap: 5px;
    }
    .btn-group .btn {
        padding: 6px 12px;
        font-size: 14px;
        min-width: 45px;
        min-height: 35px;
    }
  </style>
  <?php include '../includes/logo.php'; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- TOPBAR & SIDEBAR -->
  <?php include "topbar.php"; ?>
  <?php include "sidebar.php"; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Semua Galeri</h1>
              </div>
              <div class="col-sm-6" style="text-align:right;">
                  <a class="btn btn-primary" href="add-gallery.php">
                      <i class="fa fa-plus" aria-hidden="true"></i> Tambah Baru
                  </a>
              </div>
          </div>
      </div>
    </section>
    <section class="content">
      <!-- Pesan notifikasi jika ada -->
      <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])): ?>
         <div style="max-width:600px; margin:20px auto;">
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
           <h3 class="card-title">Lihat Galeri</h3>
         </div>
         <div class="card-body p-0">
           <div class="table-responsive">
             <table id="myTable" class="table table-bordered">
               <thead>
                 <tr>
                   <th>Gambar</th>
                   <th>Nama Galeri</th>
                   <th>Kategori</th>
                   <th>Tanggal Unggah</th>
                   <th>Aksi</th>
                 </tr>
               </thead>
               <tbody>
                 <?php
                 while ($row = mysqli_fetch_assoc($result)) {
                     $id = $row['id'];
                     echo "<tr>";
                     echo "<td><img src='uploads/" . $row['foto'] . "' style='width:100px;'></td>";
                     echo "<td>" . htmlspecialchars($row['galery']) . "</td>";
                     echo "<td>" . htmlspecialchars($row['kat_gal']) . "</td>";
                     echo "<td>" . htmlspecialchars($row['uploaded_on']) . "</td>";
                     echo "<td>
                             <div class='btn-group'>
                               <a href='add-gallery.php?edit=" . $id . "' class='btn btn-info' onclick='return confirm(\"Anda yakin?\")'>
                                 <i class='fas fa-edit'></i>
                               </a>
                               <a href='view-gallery.php?delete_id=" . $id . "' onclick='return confirm(\"Anda yakin?\")' class='btn btn-danger'>
                                 <i class='fas fa-trash'></i>
                               </a>
                             </div>
                           </td>";
                     echo "</tr>";
                 }
                 ?>
               </tbody>
             </table>
           </div>
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
<script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      language: {
        search: "Cari :"
      }
    });
  });
</script>
</body>
</html>