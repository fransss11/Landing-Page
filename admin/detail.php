<?php
// Mulai sesi jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Jakarta'); // Set timezone ke Waktu Indonesia Barat (WIB)
include 'conn.php';
include 'auth.php';

// Periksa apakah sesi `ad_id` sudah di-set
if (!isset($_SESSION['ad_id']) || empty($_SESSION['ad_id'])) {
    header('Location: login.php');
    exit;
}

// Tangkap parameter tipe detail (today atau total)
$type  = $_GET['type'] ?? '';

// Tangkap parameter halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

if ($type === 'today') {
    $query = "SELECT * FROM visitor WHERE DATE(visit_date) = CURDATE() ORDER BY id_visitor DESC LIMIT $limit OFFSET $offset";
    $title = "Detail Pengunjung Hari Ini";
} elseif ($type === 'total') {
    $query = "SELECT * FROM visitor ORDER BY id_visitor DESC LIMIT $limit OFFSET $offset";
    $title = "Detail Total Pengunjung";
} else {
    echo "<div class='alert alert-danger m-5'>Tipe detail tidak valid.</div>";
    exit;
}

// Hitung total data untuk pagination
$totalQuery = $type === 'today' ? "SELECT COUNT(*) as total FROM visitor WHERE DATE(visit_date) = CURDATE()" : "SELECT COUNT(*) as total FROM visitor";
$totalResult = $con->query($totalQuery);
$totalData = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalData / $limit);

$result = $con->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php include "title.php"; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <?php include '../includes/logo.php'; ?>
  <style>
    body {
        background-color: #f4f6f9;
        font-family: Arial, sans-serif;
    }

    .container-fluid {
        padding: 15px;
    }

    .card {
        border: 2px solid #007bff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #007bff;
        color: #fff;
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
    }

    .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        overflow-x: auto;
    }

    .table th,
    .table td {
        border: 1px solid #dee2e6 !important;
        text-align: center;
        vertical-align: middle;
    }

    .btn {
        font-size: 1.25rem;
        padding: 10px 30px;
        border-radius: 8px;
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        .card-header {
            font-size: 1.25rem;
        }

        .table th,
        .table td {
            font-size: 0.875rem;
            padding: 0.5rem;
        }

        .btn {
            font-size: 1rem;
            padding: 8px 20px;
        }

        h2 {
            font-size: 1.5rem;
            text-align: center;
        }

        .container-fluid {
            padding: 10px;
        }
    }

    @media (max-width: 576px) {
        .card-header {
            font-size: 1rem;
        }

        .btn {
            font-size: 0.875rem;
            padding: 6px 15px;
        }

        h2 {
            font-size: 1.25rem;
        }
    }

    .pagination {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        padding: 0.5rem;
    }

    .pagination .page-item {
        margin: 0 5px;
    }

    .pagination .page-link {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        padding: 0;
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }

    @media (max-width: 576px) {
        .pagination .page-link {
            width: 30px;
            height: 30px;
            font-size: 0.875rem;
        }
    }
  </style>
</head>
<body>
  <div class="container-fluid py-4">
    <!-- Judul Halaman -->
    <div class="row mb-3">
      <div class="col">
        <h2 class="font-weight-bold text-primary">
          <i class="fas fa-users mr-2"></i><?= htmlspecialchars($title) ?>
        </h2>
        <hr>
      </div>
    </div>

    <!-- Card dengan Tabel -->
    <div class="row">
      <div class="col">
        <div class="card shadow-sm">
          <div class="card-header">
            <p class="mb-0" style="font-size: 2.25rem; font-weight: bold; color:rgb(8, 8, 8); text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);">
              <i class="fas fa-list mr-1"></i>Daftar Pengunjung
            </p>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped table-hover mb-0">
                <thead class="thead-dark">
                  <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:8%;">ID</th>
                    <th>IP Address</th>
                    <th style="width:15%;">Visit Date</th>
                    <th>User Agent</th>
                    <th style="width:12%;">Browser</th>
                    <th style="width:10%;">Device</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($result && $result->num_rows): ?>
                    <?php $no = $offset + 1; while ($row = $result->fetch_assoc()): ?>
                      <tr>
                        <td class="text-center align-middle"><?= $no++ ?></td>
                        <td class="text-center align-middle"><?= htmlspecialchars($row['id_visitor']) ?></td>
                        <td class="align-middle"><?= htmlspecialchars($row['ip_address']) ?></td>
                        <td class="align-middle"><?= htmlspecialchars($row['visit_date']) ?></td>
                        <td class="align-middle"><?= htmlspecialchars($row['user_agent']) ?></td>
                        <td class="align-middle"><?= htmlspecialchars($row['browser']) ?></td>
                        <td class="align-middle"><?= htmlspecialchars($row['device']) ?></td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle fa-lg mr-2"></i>Tidak ada data pengunjung.
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer text-center">
            <nav aria-label="Page navigation">
              <ul class="pagination justify-content-center mb-0">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                  <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="?type=<?= htmlspecialchars($type) ?>&page=<?= $i ?>"><?= $i ?></a>
                  </li>
                <?php endfor; ?>
              </ul>
            </nav>
          </div>
          <div style="padding-top: 3%; text-align: center;" class="col-12">
            <a href="index.php" class="btn btn-danger btn-lg" style="padding: 10px 30px; font-size: 1.25rem; font-weight: bold; border-radius: 8px;">
              Kembali
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Script -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>