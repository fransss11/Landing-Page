<?php
include 'conn.php';
include 'auth.php';

$a = 10;

// Hapus gambar jika delete_id tersedia dan valid
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Gunakan prepared statement
    $stmt = $con->prepare("DELETE FROM images WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Deleted Successfully');</script>";
    }
    echo "<script>window.location.href = 'view-gallery.php'</script>";
    exit();
}

// Pagination
$limit = 10;
$page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? (int)$_GET["page"] : 1;
$serial = ($page - 1) * $limit;

// Ambil data gambar dari database dengan pagination
$query = $con->prepare("SELECT * FROM images ORDER BY id DESC LIMIT ?, ?");
$query->bind_param("ii", $serial, $limit);
$query->execute();
$result = $query->get_result();

// Hitung total halaman
$totalQuery = $con->query("SELECT COUNT(*) as total FROM images");
$totalRow = $totalQuery->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php include "title.php"; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSS -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php include "topbar.php"; ?>
  <?php include "sidebar.php"; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Gallery</h1>
          </div>
          <div class="col-sm-6 text-right">
            <a class="btn btn-primary" href="add-gallery.php">
              <i class="fa fa-plus"></i> Add New
            </a>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">View</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $imageURL = 'uploads/' . $row["file_name"];
                        $iid = $row["id"];
                        ?>
                        <div class="col-md-3 pb-3" style="border:1px solid;">
                            <img style="width: 100%;" src="<?= $imageURL; ?>" alt=""><br>
                            <center>
                                <input type="text" value="<?= $imageURL; ?>" id="copyInput<?= $iid; ?>" readonly>
                                <button class="btn btn-success copyBtn" data-target="copyInput<?= $iid; ?>">Copy</button>
                                <a class="btn btn-danger" href="view-gallery.php?delete_id=<?= $iid; ?>" onclick="return confirm('Are you sure?');">
                                  <i class="fas fa-trash"></i> Delete
                                </a>
                            </center>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-center'>No image(s) found...</p>";
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div class="row">
        <div class="col-md-12">
          <nav>
            <ul class="pagination justify-content-center">
              <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a></li>
              <?php endif; ?>

              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                  <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                </li>
              <?php endfor; ?>

              <?php if ($page < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page + 1; ?>">Next</a></li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>
      </div>

    </section>
  </div>

  <?php include "footer.php"; ?>
</div>

<!-- JavaScript -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/demo.js"></script>
<script src="plugins/summernote/summernote-bs4.min.js"></script>

<script>
  $(function () {
    $('.textarea').summernote();
    
    // Copy URL function (universal untuk semua tombol)
    $(".copyBtn").click(function () {
      var inputId = $(this).data("target");
      var copyText = document.getElementById(inputId);
      copyText.select();
      document.execCommand("copy");
      alert("Copied: " + copyText.value);
    });
  });
</script>

</body>
</html>
