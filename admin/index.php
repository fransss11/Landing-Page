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

// Query untuk mendapatkan data pengunjung 7 hari terakhir
$query_weekly = "SELECT DATE(visit_date) as date, COUNT(*) as count 
                FROM visitor 
                WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(visit_date)
                ORDER BY date ASC";
$result_weekly = $con->query($query_weekly);
$weekly_labels = [];
$weekly_data = [];
if ($result_weekly) {
    while ($row = $result_weekly->fetch_assoc()) {
        $weekly_labels[] = date('d/m', strtotime($row['date']));
        $weekly_data[] = $row['count'];
    }
}
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
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <?php include '../includes/logo.php'; ?>
  <style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
  }
  
  .content-wrapper {
    margin-left: 250px;
    transition: all 0.3s;
    min-height: 100vh;
    background-color: #f8f9fa;
    padding: 20px;
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
    height: 400px;
    position: relative;
    margin: auto;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
  }
  
  #admin-text {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 30px;
    font-size: 2.5rem;
    position: relative;
    display: inline-block;
  }
  
  #admin-text:after {
    content: '';
    position: absolute;
    width: 70px;
    height: 4px;
    background: #3498db;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 2px;
  }
  
  .welcome-card {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    margin-bottom: 30px;
  }
  
  .welcome-card:hover {
    transform: translateY(-5px);
  }
  
  .stat-card {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    background: white;
  }
  
  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  }
  
  .stat-card .card-body {
    padding: 25px;
  }
  
  .stat-card .stat-icon {
    font-size: 48px;
    opacity: 0.8;
  }
  
  .stat-value {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 5px;
  }
  
  .stat-label {
    color: #6c757d;
    font-weight: 500;
  }
  
  .action-card {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    margin-bottom: 20px;
    background: white;
  }
  
  .action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  }
  
  .action-card .card-body {
    padding: 25px;
  }
  
  .action-card .btn {
    border-radius: 10px;
    padding: 12px 20px;
    font-weight: 500;
    letter-spacing: 0.5px;
  }
  
  .chart-card {
    border-radius: 15px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    margin-bottom: 30px;
  }
  
  .chart-card .card-header {
    background: white;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    font-weight: 600;
    padding: 20px;
  }
  
  @media (max-width: 767.98px) {
    #admin-text {
      font-size: 2rem;
    }
    
    .stat-value {
      font-size: 28px;
    }
    
    .chart-container {
      height: 300px;
    }
  }
  
  @media (max-width: 575.98px) {
    #admin-text {
      font-size: 1.8rem;
    }
    
    .welcome-card {
      padding: 15px;
    }
    
    .stat-value {
      font-size: 24px;
    }
    
    .chart-container {
      height: 250px;
    }
  }
  
  /* Enhanced Gradient Button Variations */
  .btn-gradient-primary {
    background: linear-gradient(45deg, #3498db, #1abc9c);
    border: none;
    color: white;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
    transition: all 0.3s ease;
  }
  
  .btn-gradient-primary:hover {
    background: linear-gradient(45deg, #2980b9, #16a085);
    box-shadow: 0 6px 20px rgba(52, 152, 219, 0.6);
    transform: translateY(-2px);
  }
  
  .btn-gradient-warning {
    background: linear-gradient(120deg, #f39c12, #ff7675);
    border: none;
    color: white;
    box-shadow: 0 4px 15px rgba(243, 156, 18, 0.4);
    transition: all 0.3s ease;
  }
  
  .btn-gradient-warning:hover {
    background: linear-gradient(120deg, #e67e22, #e74c3c);
    box-shadow: 0 6px 20px rgba(243, 156, 18, 0.6);
    transform: translateY(-2px);
  }
  
  .btn-gradient-success {
    background: linear-gradient(to right, #2ecc71, #26c6da);
    border: none;
    color: white;
    box-shadow: 0 4px 15px rgba(46, 204, 113, 0.4);
    transition: all 0.3s ease;
  }
  
  .btn-gradient-success:hover {
    background: linear-gradient(to right, #27ae60, #00acc1);
    box-shadow: 0 6px 20px rgba(46, 204, 113, 0.6);
    transform: translateY(-2px);
  }
  
  .btn-gradient-danger {
    background: linear-gradient(135deg, #e74c3c, #9b59b6);
    border: none;
    color: white;
    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
    transition: all 0.3s ease;
  }
  
  .btn-gradient-danger:hover {
    background: linear-gradient(135deg, #c0392b, #8e44ad);
    box-shadow: 0 6px 20px rgba(231, 76, 60, 0.6);
    transform: translateY(-2px);
  }
  
  .btn-gradient-purple {
    background: linear-gradient(to right, #9b59b6, #6a0dad);
    border: none;
    color: white;
    box-shadow: 0 4px 15px rgba(155, 89, 182, 0.4);
    transition: all 0.3s ease;
  }
  
  .btn-gradient-purple:hover {
    background: linear-gradient(to right, #8e44ad, #5c0490);
    box-shadow: 0 6px 20px rgba(155, 89, 182, 0.6);
    transform: translateY(-2px);
  }
  
  .btn-gradient-info {
    background: linear-gradient(60deg, #00c6ff, #0072ff);
    border: none;
    color: white;
    box-shadow: 0 4px 15px rgba(0, 198, 255, 0.4);
    transition: all 0.3s ease;
  }
  
  .btn-gradient-info:hover {
    background: linear-gradient(60deg, #0099cc, #005cbf);
    box-shadow: 0 6px 20px rgba(0, 198, 255, 0.6);
    transform: translateY(-2px);
  }
  
  /* Card styling variations */
  .card-glass {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.2);
  }
  
  .card-pattern {
    background-color: #ffffff;
    background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23f0f0f0' fill-opacity='0.4' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
  }
  
  .card-border-left {
    border-left: 4px solid #3498db;
    border-radius: 0 15px 15px 0;
  }
  
  .card-border-gradient {
    position: relative;
    border: none;
  }
  
  .card-border-gradient:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 15px;
    padding: 2px;
    background: linear-gradient(45deg, #3498db, #8e44ad);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: destination-out;
    mask-composite: exclude;
    z-index: -1;
  }
  
  /* Enhanced action links with varied animations */
  .action-link {
    text-decoration: none;
    color: inherit;
    display: block;
  }
  
  .action-link.zoom:hover .action-card {
    transform: scale(1.05);
  }
  
  .action-link.float:hover .action-card {
    transform: translateY(-10px);
  }
  
  .action-link.rotate:hover .action-card {
    transform: rotate(2deg);
  }
  
  /* Different button styles */
  .btn-3d {
    position: relative;
    border: none;
    background: #3498db;
    color: white;
    padding: 15px 24px;
    font-weight: 600;
    text-transform: uppercase;
    box-shadow: 0 6px 0 #2980b9;
    transition: all 0.1s ease;
  }
  
  .btn-3d:hover {
    box-shadow: 0 4px 0 #2980b9;
    transform: translateY(2px);
  }
  
  .btn-outline-glow {
    background: transparent;
    color: #3498db;
    border: 2px solid #3498db;
    box-shadow: 0 0 0 rgba(52, 152, 219, 0.1);
    transition: all 0.3s ease;
  }
  
  .btn-outline-glow:hover {
    box-shadow: 0 0 15px rgba(52, 152, 219, 0.5);
    background: rgba(52, 152, 219, 0.1);
  }
  
  /* Decorative elements */
  .pattern-bg {
    position: relative;
    overflow: hidden;
  }
  
  .pattern-bg:before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
    z-index: -1;
  }
  
  .action-link {
    text-decoration: none;
    color: inherit;
    display: block;
  }
  
  .action-link:hover {
    text-decoration: none;
    color: inherit;
  }
  
  .pulse {
    box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.7);
    animation: pulse 2s infinite;
  }
  
  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.7);
    }
    70% {
      box-shadow: 0 0 0 10px rgba(52, 152, 219, 0);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(52, 152, 219, 0);
    }
  }
  
  .fade-in {
    opacity: 0;
    animation: fadeIn 0.8s forwards;
  }
  
  @keyframes fadeIn {
    to {
      opacity: 1;
    }
  }

  /* Sequential animation styles */
  .slide-down {
    transform: translateY(-30px);
    animation: slideDown 0.8s forwards;
  }
  
  .slide-right {
    transform: translateX(-30px);
    animation: slideRight 0.8s forwards;
  }
  
  .slide-up {
    transform: translateY(30px);
    animation: slideUp 0.8s forwards;
  }
  
  @keyframes slideDown {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  @keyframes slideRight {
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  @keyframes slideUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
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
        <!-- Welcome Card -->
        <div class="row">
          <div class="col-12">
            <div class="welcome-card text-center" style="overflow: hidden;">
              <div class="fade-in slide-down" style="animation-delay: 0.3s">
                <h1 id="admin-text">Selamat Datang</h1>
              </div>
              <div class="fade-in slide-right" style="animation-delay: 0.8s">
                <h2 class="mb-0"><?= htmlspecialchars($admin_name, ENT_QUOTES, 'UTF-8') ?></h2>
              </div>
              <div class="fade-in slide-up" style="animation-delay: 1.3s">
                <p class="mt-2">Akses dashboard admin untuk mengelola konten website</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Action Cards -->
        <div class="row">
          <div class="col-md-6 fade-in" style="animation-delay: 0.4s">
            <a href="add-blog.php" class="action-link">
              <div class="action-card">
                <div class="card-body d-flex align-items-center">
                  <div class="text-warning mr-4">
                    <i class="fas fa-newspaper fa-3x"></i>
                  </div>
                  <div>
                    <h4 class="mb-1">Tambahkan Berita</h4>
                    <p class="mb-0 text-muted">Tambahkan berita ke website</p>
                    <button class="btn btn-gradient-warning mt-3">Tambah Berita</button>
                  </div>
                </div>
              </div>
            </a>
          </div>
          <div class="col-md-6 fade-in" style="animation-delay: 0.5s">
            <a href="view-blog.php" class="action-link">
              <div class="action-card">
                <div class="card-body d-flex align-items-center">
                  <div class="text-danger mr-4">
                    <i class="fas fa-list-alt fa-3x"></i>
                  </div>
                  <div>
                    <h4 class="mb-1">Lihat Berita</h4>
                    <p class="mb-0 text-muted">Kelola dan lihat semua berita yang sudah dipublikasi</p>
                    <button class="btn btn-gradient-danger mt-3">Lihat Berita</button>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="row">
          <div class="col-md-6 fade-in" style="animation-delay: 0.2s">
            <div class="stat-card mb-4">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <div class="stat-value"><?= htmlspecialchars($today, ENT_QUOTES, 'UTF-8') ?></div>
                  <div class="stat-label">Pengunjung Hari Ini</div>
                </div>
                <div class="stat-icon text-info">
                  <i class="fas fa-users"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 fade-in" style="animation-delay: 0.3s">
            <div class="stat-card mb-4">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <div class="stat-value"><?= htmlspecialchars($total, ENT_QUOTES, 'UTF-8') ?></div>
                  <div class="stat-label">Total Pengunjung</div>
                </div>
                <div class="stat-icon text-primary">
                  <i class="fas fa-chart-line"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Visitor Charts -->
        <div class="row mt-4">
          <div class="col-md-12 fade-in" style="animation-delay: 0.6s">
            <div class="chart-card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Statistik Pengunjung</h4>
              </div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="visitorChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Weekly Visitor Chart -->
        <div class="row mt-4">
          <div class="col-md-12 fade-in" style="animation-delay: 0.7s">
            <div class="chart-card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Pengunjung 7 Hari Terakhir</h4>
              </div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="weeklyChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
          <div class="col-md-6 mb-3 fade-in" style="animation-delay: 0.8s">
            <a href="detail.php?type=today" class="btn btn-gradient-primary btn-lg btn-block pulse">
              <i class="fas fa-calendar-day mr-2"></i> Detail Pengunjung Hari Ini
            </a>
          </div>
          <div class="col-md-6 mb-3 fade-in" style="animation-delay: 0.9s">
            <a href="detail.php?type=total" class="btn btn-gradient-success btn-lg btn-block">
              <i class="fas fa-chart-bar mr-2"></i> Detail Total Pengunjung
            </a>
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
    // Animasi elemen fade-in
    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach(element => {
      setTimeout(() => {
        element.style.opacity = 1;
      }, 100);
    });

    // Animasi teks selamat datang
    const textElement = document.getElementById("admin-text");
    textElement.style.opacity = 1;
    
    // Chart untuk bar chart visitor
    const ctx = document.getElementById('visitorChart').getContext('2d');
    const gradientFill1 = ctx.createLinearGradient(0, 0, 0, 400);
    gradientFill1.addColorStop(0, 'rgba(75, 192, 192, 0.7)');
    gradientFill1.addColorStop(1, 'rgba(75, 192, 192, 0.2)');
    
    const gradientFill2 = ctx.createLinearGradient(0, 0, 0, 400);
    gradientFill2.addColorStop(0, 'rgba(153, 102, 255, 0.7)');
    gradientFill2.addColorStop(1, 'rgba(153, 102, 255, 0.2)');
    
    const visitorChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Pengunjung Hari Ini', 'Total Pengunjung'],
        datasets: [{
          label: 'Jumlah Pengunjung',
          data: [<?= htmlspecialchars($today, ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($total, ENT_QUOTES, 'UTF-8') ?>],
          borderWidth: 2,
          backgroundColor: [gradientFill1, gradientFill2],
          borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 15,
            titleFont: {
              size: 16,
              weight: 'bold'
            },
            bodyFont: {
              size: 14
            },
            displayColors: false,
            callbacks: {
              label: function(context) {
                return 'Jumlah: ' + context.raw + ' pengunjung';
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            precision: 0,
            grid: {
              display: true,
              color: 'rgba(0, 0, 0, 0.05)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        },
        animation: {
          duration: 2000,
          easing: 'easeOutQuart'
        }
      }
    });
    
    // Chart untuk weekly visitor
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    const weeklyGradient = weeklyCtx.createLinearGradient(0, 0, 0, 400);
    weeklyGradient.addColorStop(0, 'rgba(54, 162, 235, 0.8)');
    weeklyGradient.addColorStop(1, 'rgba(54, 162, 235, 0.1)');
    
    const weeklyChart = new Chart(weeklyCtx, {
      type: 'line',
      data: {
        labels: <?= json_encode($weekly_labels) ?>,
        datasets: [{
          label: 'Pengunjung',
          data: <?= json_encode($weekly_data) ?>,
          fill: true,
          backgroundColor: weeklyGradient,
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 3,
          tension: 0.4,
          pointBackgroundColor: 'white',
          pointBorderColor: 'rgba(54, 162, 235, 1)',
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 15,
            titleFont: {
              size: 16,
              weight: 'bold'
            },
            bodyFont: {
              size: 14
            },
            displayColors: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            precision: 0,
            grid: {
              display: true,
              color: 'rgba(0, 0, 0, 0.05)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        },
        animation: {
          duration: 2000,
          easing: 'easeOutQuart'
        }
      }
    });
  });
</script>
</body>
</html>