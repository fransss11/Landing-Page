<?php
include 'conn.php';

// Ambil halaman saat ini dari parameter GET, default ke 1 jika tidak ada atau tidak valid
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int) $_GET['page'] : 1;

// Tentukan limit data per halaman
$limit = 5;

// Hitung offset (data mulai dari baris ke-berapa)
$offset = ($page - 1) * $limit;

// Query total data pengunjung hari ini (untuk menghitung total halaman)
// Menggunakan DATE(visit_date) agar hanya bagian tanggal yang dibandingkan
$queryCount = "SELECT COUNT(*) AS total FROM visitor WHERE DATE(visit_date) = CURDATE()";
$resultCount = $con->query($queryCount);
$rowCount   = $resultCount->fetch_assoc();
$totalData  = $rowCount['total'];

// Hitung total halaman (dibulatkan ke atas)
$totalPages = ceil($totalData / $limit);

// Query untuk mengambil data pengunjung hari ini dengan limit
$queryToday = "SELECT *
    -- id_visitor, ip_address, visit_date, user_agent, browser, device 
  FROM visitor 
  WHERE DATE(visit_date) = CURDATE()
  ORDER BY id_visitor DESC
  LIMIT $offset, $limit";
$resultToday = $con->query($queryToday);

// Jika parameter ajax diset, keluarkan markup tabel dan pagination saja, lalu hentikan eksekusi.
if (isset($_GET['ajax'])) {
  if ($resultToday && $resultToday->num_rows > 0) {
      echo '<div class="table-responsive">';
      echo '<table class="table table-bordered">';
      echo '<thead><tr>
              <th>No</th>
              <th>ID</th>
              <th>IP Address</th>
              <th>Visit Date</th>
              <th>User Agent</th>
              <th>Browser</th>
              <th>Device</th>
            </tr></thead><tbody>';
      // Nomor baris dimulai dari offset + 1
      $i = $offset + 1;
      while($row = $resultToday->fetch_assoc()){
           echo '<tr>';
           echo '<td>' . $i++ . '</td>';
           echo '<td>' . htmlspecialchars($row['id_visitor'], ENT_QUOTES, 'UTF-8') . '</td>';
           echo '<td>' . htmlspecialchars($row['ip_address'], ENT_QUOTES, 'UTF-8') . '</td>';
           echo '<td>' . htmlspecialchars($row['visit_date'], ENT_QUOTES, 'UTF-8') . '</td>';
           echo '<td>' . htmlspecialchars($row['user_agent'], ENT_QUOTES, 'UTF-8') . '</td>';
           echo '<td>' . htmlspecialchars($row['browser'], ENT_QUOTES, 'UTF-8') . '</td>';
           echo '<td>' . htmlspecialchars($row['device'], ENT_QUOTES, 'UTF-8') . '</td>';
           echo '</tr>';
      }
      echo '</tbody></table>';
      echo '</div>';
  } else {
      echo '<p>Tidak ada data pengunjung hari ini.</p>';
  }

  // Tampilkan pagination jika total data lebih dari limit
  if ($totalPages > 1) {
    echo '<div class="pagination">';
      // Tombol Previous
      if ($page > 1) {
         echo '<a href="?page='.($page - 1).'&ajax=1">&laquo; Prev</a>';
      }
      // Link nomor halaman
      for ($i = 1; $i <= $totalPages; $i++) {
         if ($i == $page) {
            echo '<a href="?page='.$i.'&ajax=1" class="active">'.$i.'</a>';
         } else {
            echo '<a href="?page='.$i.'&ajax=1">'.$i.'</a>';
         }
      }
      // Tombol Next
      if ($page < $totalPages) {
         echo '<a href="?page='.($page + 1).'&ajax=1">Next &raquo;</a>';
      }
    echo '</div>';
  }
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <?php include "title.php"; ?>
  <!-- Menggunakan Bootstrap untuk styling -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <!-- Tambahkan meta viewport untuk responsif -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { padding: 20px; }
    h2 { margin-top: 40px; }
    .pagination a {
      margin: 0 5px;
      padding: 6px 12px;
      border: 1px solid #dee2e6;
      color: #007bff;
      text-decoration: none;
    }
    .pagination a.active {
      background-color: #007bff;
      color: white;
      pointer-events: none;
    }
  </style>
</head>
<body>
  <h2>Pengunjung Hari Ini</h2>
  <!-- Container untuk memuat data visitor secara AJAX -->
  <div id="visitorContainer"></div>

  <!-- Tombol untuk kembali ke index.php -->
  <!-- <a href="index.php" class="btn btn-primary">Kembali ke Index</a> -->
  
  <!-- jQuery dan Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  
  <script>
    // Fungsi untuk memuat data visitor dengan AJAX
    function loadVisitorData(page = 1) {
      $.ajax({
        url: '<?= basename(__FILE__) ?>',
        data: { page: page, ajax: 1 },
        method: 'GET',
        success: function(data) {
          $('#visitorContainer').html(data);
        },
        error: function() {
          $('#visitorContainer').html('<p>Terjadi kesalahan saat mengambil data.</p>');
        }
      });
    }
    
    // Muat data saat halaman dimuat
    $(document).ready(function() {
      loadVisitorData();
      
      // Intersepsi klik pada pagination
      $('#visitorContainer').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var page = href.split('page=')[1].split('&')[0];
        loadVisitorData(page);
      });
    });
  </script>
</body>
</html>
