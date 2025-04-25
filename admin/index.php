<?php
// Mulai sesi jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    // Pastikan session cookie hanya berlaku selama sesi (default lifetime = 0)
    ini_set('session.cookie_lifetime', 0);
    session_start();
}
// Sertakan koneksi database dan file otentikasi
include 'conn.php';
include 'auth.php';
// Periksa apakah sesi `ad_id` sudah di-set
if (!isset($_SESSION['ad_id']) || empty($_SESSION['ad_id'])) {
    header('Location: login.php');
    exit;
}
// Simpan ID admin dari sesi
$ad_id = $_SESSION['ad_id'];
// Gunakan Prepared Statement untuk keamanan
$query = "SELECT ad_id, ad_email, ad_name FROM admin WHERE ad_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $ad_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    $admin_name  = $admin['ad_name'];
} else {
    // Jika admin tidak ditemukan, redirect ke login
    header('Location: login.php');
    exit;
}
// Query untuk mendapatkan total pengunjung
$query_total = "SELECT COUNT(*) AS total FROM visitor";
$result_total = $con->query($query_total);
$total = ($result_total && $result_total->num_rows > 0) ? $result_total->fetch_assoc()['total'] : 0;
// Query untuk mendapatkan jumlah pengunjung hari ini
$query_today = "SELECT COUNT(*) AS today FROM visitor WHERE DATE(visit_date) = CURDATE()";
$result_today = $con->query($query_today);
$today = ($result_today && $result_today->num_rows > 0) ? $result_today->fetch_assoc()['today'] : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php include "title.php"; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <?php include '../includes/logo.php'; ?>
  <style>
  .content-wrapper {
    margin-left: 250px;
    transition: all 0.3s;
    min-height: 100vh;
  }
  @media (max-width: 992px) {
    .content-wrapper {
      margin-left: 0;
    }
  }
  .wrapper {
    min-height: 100vh;
  }
  .chart-container {
    width: 100%;
    max-width: 900px;
    height:0;
    padding-bottom:610px;
    position:relative;
    margin:auto;
  }
  @media (max-width:766.98px){
    .chart-container{
      width:100%;
      padding-bottom:455px;
    }
    #admin-text{font-size:32px;}
    .small-box h3{font-size:20px;}
    .small-box p{font-size:14px;margin-bottom:0;}
  }
  @media (max-width:575.98px){
    .chart-container{
      width:100%;
      padding-bottom:350px;
    }
    #admin-text{font-size:32px;}
    .small-box h3{font-size:20px;}
    .small-box p{font-size:14px;margin-bottom:0;}
  }
  #admin-text {
    white-space: pre-wrap;
    font-size: 48px;
    font-weight: bold;
    line-height: 0.7;
  }

  @media (max-width: 575.98px) {
    #admin-text {
      font-size: 32px;
      line-height: 1.2;
    }
    .card h1 {
      font-size: 24px;
    }
  }
  .btn-lg {
    width: 100%;
  }
  </style>
  <!-- Skrip untuk cek sessionStorage -->
  <script>
    // Jika flag 'isLoggedIn' tidak ada di sessionStorage, arahkan ke logout untuk menghapus sesi
    if (!sessionStorage.getItem("isLoggedIn")) {
      window.location.href = "logout.php";
    } else {
      // Jika flag ada, pastikan tetap terset
      sessionStorage.setItem("isLoggedIn", "true");
    }
  </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <?php include "topbar.php"; ?>
  <!-- Sidebar -->
  <?php include "sidebar.php"; ?>
  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        <!-- Baris untuk Welcome -->
        <div class="row mt-3">
          <div class="col-12 text-center">
            <h1 id="admin-text">Selamat Datang<br></br><?= htmlspecialchars($admin_name, ENT_QUOTES, 'UTF-8') ?></h1>
          </div>
        </div>
        <!-- Baris untuk Tombol -->
        <div class="row mt-4">
          <div class="col-md-6 col-sm-12">
            <a href="add-blog.php" class="small-box-footer">
              <div class="small-box bg-warning">
                <div class="inner text-center">
                  <h3>Tambahkan Berita</h3>
                  <p>Tambahkan</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
              </div>
            </a>
          </div>
          <div class="col-md-6 col-sm-12">
            <a href="view-blog.php" class="small-box-footer">
              <div class="small-box bg-danger">
                <div class="inner text-center">
                  <h3>Lihat Berita</h3>
                  <p>Lihat</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
              </div>
            </a>
          </div>
        </div>
        <!-- Baris untuk Diagram Pengunjung -->
        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card bg-info text-white shadow mb-4">
              <div class="card-body text-center">
                <h1 class="font-weight-bold">Statistik Pengunjung :</h1>
              </div>
            </div>
            <div class="chart-container">
              <canvas id="visitorChart"></canvas>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-md-6 col-sm-12 text-center">
            <a href="detail.php?type=today" class="btn btn-info btn-lg">Detail Pengunjung Hari Ini</a>
          </div>
          <div class="col-md-6 col-sm-12 text-center">
            <a href="detail.php?type=total" class="btn btn-primary btn-lg">Detail Total Pengunjung</a>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
  </div><!-- /.content-wrapper -->
</div><!-- /.wrapper -->
<!-- Footer -->
<?php include "footer.php"; ?>
<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.js"></script>
<!-- Inisialisasi Chart.js -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Animasi teks selamat datang
    let textElement = document.getElementById("admin-text");
    let text = textElement.innerText;
    textElement.innerText = "";
    let i = 0;
    let interval = setInterval(function () {
      if (i < text.length) {
        textElement.innerText += text[i];
        i++;
      } else {
        clearInterval(interval);
      }
    }, 100);
    // Inisialisasi diagram menggunakan Chart.js
    const ctx = document.getElementById('visitorChart').getContext('2d');
    const visitorChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Pengunjung Hari Ini', 'Total Pengunjung'],
        datasets: [{
          label: 'Jumlah Pengunjung',
          data: [<?= htmlspecialchars($today, ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($total, ENT_QUOTES, 'UTF-8') ?>],
          borderWidth: 1,
          backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)'],
          borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 1.5,
        scales: {
          y: {
            beginAtZero: true,
            precision: 0,
          }
        }
      }
    });
  });
</script>
</body>
</html>