<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';

// Inisialisasi flash message dari session jika ada
if(isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    $msgClass = $_SESSION['msgClass'];
    unset($_SESSION['msg'], $_SESSION['msgClass']);
} else {
    $msg = "";
    $msgClass = "";
}

// Hapus kategori jika parameter delete_id ada
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($con, $_GET['delete_id']);
    $query_delete = "DELETE FROM category WHERE id = '$delete_id'";
    if (mysqli_query($con, $query_delete)) {
        $_SESSION['msg'] = "Deleted Successfully";
        $_SESSION['msgClass'] = "alert-success";
    } else {
        $_SESSION['msg'] = "Error deleting category";
        $_SESSION['msgClass'] = "alert-danger";
    }
    header("Location: add-category.php");
    exit;
}

// Fetch data jika dalam mode edit
$edit = isset($_GET['edit']) ? mysqli_real_escape_string($con, $_GET['edit']) : '';
$roww = [];
if ($edit != '') {
    $resultt = mysqli_query($con, "SELECT * FROM category WHERE id = '$edit'");
    $roww = mysqli_fetch_array($resultt);
}

// Handle form submission untuk menambah atau memperbarui kategori
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($con, $_POST['cat_name']);
    
    if ($edit == '') {
        // Insert new category
        if (mysqli_query($con, "INSERT INTO category (cat_name) VALUES ('$name')")) {
            $_SESSION['msg'] = "Added Successfully";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Error adding category";
            $_SESSION['msgClass'] = "alert-danger";
        }
        header("Location: add-category.php");
        exit;
    } else {
        // Update category
        if (mysqli_query($con, "UPDATE category SET cat_name = '$name' WHERE id = '$edit'")) {
            $_SESSION['msg'] = "Updated Successfully";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Error updating category";
            $_SESSION['msgClass'] = "alert-danger";
        }
        header("Location: add-category.php?edit=" . $edit);
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php include "title.php"; ?>
  <!-- Responsive viewport -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
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
            <h1>Add New Category</h1>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- Form Section -->
        <div class="col-md-5">
          <!-- Tampilkan alert jika ada pesan -->
          <?php if (!empty($msg)): ?>
            <div style="max-width:600px; margin:0 auto;">
              <div class="alert <?php echo $msgClass; ?> alert-dismissible fade show" role="alert">
                <?php echo $msg; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
          <?php endif; ?>

          <!-- Form dengan validasi Bootstrap -->
          <form action="" method="post" class="needs-validation" novalidate>
            <div class="card card-outline card-info">
              <div class="card-header">
                <div class="form-group">
                  <label for="validationCategoryName" class="form-label">Enter Category Name <span class="text-danger">*</span></label>
                  <input 
                    type="text" 
                    name="cat_name" 
                    value="<?php echo isset($roww['cat_name']) ? htmlspecialchars($roww['cat_name']) : ''; ?>" 
                    class="form-control" 
                    id="validationCategoryName"
                    placeholder="Enter ..." 
                    required
                  >
                  <div class="invalid-feedback">
                    Please enter a category name.
                  </div>
                </div>
              </div>
              <button type="submit" name="add" class="btn btn-primary btn-lg">Add</button>
              <a href="add-category.php" class="btn btn-danger">Kembali</a>
            </div>
          </form>
        </div>

        <!-- Tabel Category -->
        <div class="col-md-7">
          <div class="card card-outline card-info">
            <div class="card-header">
              <label>All Category</label>
            </div>
            <div class="card-header">
              <table class="table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th style="width:80px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $location = mysqli_query($con, "SELECT * FROM category");
                  while ($location_ft = mysqli_fetch_array($location)) { ?>
                    <tr>
                      <td><?php echo htmlspecialchars($location_ft["cat_name"]); ?></td>
                      <td class="text-right py-0 align-middle">
                        <div class="btn-group btn-group-sm">
                          <a 
                            href="add-category.php?edit=<?php echo $location_ft["id"]; ?>" 
                            onclick="return confirm('Are you sure?')" 
                            class="btn btn-info"
                          >
                            <i class="fas fa-edit"></i>
                          </a>
                          <a 
                            href="add-category.php?delete_id=<?php echo $location_ft["id"]; ?>" 
                            onclick="return confirm('Are you sure?')" 
                            class="btn btn-danger"
                          >
                            <i class="fas fa-trash"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div> <!-- /.card-header -->
          </div> <!-- /.card -->
        </div> <!-- /.col-md-7 -->
      </div> <!-- /.row -->
    </section>
  </div>

  <?php include "footer.php"; ?>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<!-- SCRIPT VALIDASI BOOTSTRAP ala dokumentasi -->
<script>
(function () {
  'use strict';
  // Ambil semua form yang ingin divalidasi
  var forms = document.querySelectorAll('.needs-validation');

  // Loop ke tiap form dan cegah submit jika invalid
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
})();
</script>
</body>
</html>