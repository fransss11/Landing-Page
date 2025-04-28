<?php
session_start(); // Pastikan session dimulai di awal file

include 'conn.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["pict"]["name"]);
    move_uploaded_file($_FILES["pict"]["tmp_name"], $target_file);

    $sql = "INSERT INTO admin (ad_name, pict, ad_email, ad_password) VALUES ('$name', '" . basename($_FILES["pict"]["name"]) . "', '$email', '$password')";
    $conn->query($sql);
}

// Handle Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    if (!empty($_FILES['pict']['name'])) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["pict"]["name"]);
        move_uploaded_file($_FILES["pict"]["tmp_name"], $target_file);
        $pict = basename($_FILES["pict"]["name"]);
        $sql = "UPDATE admin SET ad_name='$name', pict='$pict', ad_email='$email' WHERE ad_id=$id";
    } else {
        $sql = "UPDATE admin SET ad_name='$name', ad_email='$email' WHERE ad_id=$id";
    }
    $conn->query($sql);
}

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    if ($id == $_SESSION['ad_id']) {
        $_SESSION['message'] = 'Anda tidak dapat menghapus akun yang sedang login!';
        $_SESSION['message_type'] = 'warning';
    } else {
        $sql = "DELETE FROM admin WHERE ad_id=$id";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = 'Data berhasil dihapus!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Data gagal dihapus!';
            $_SESSION['message_type'] = 'danger';
        }
    }
    header('Location: data_admin.php');
    exit;
}

$sql = "SELECT ad_id, ad_name, pict, ad_email FROM admin";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "title.php"; ?>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <?php include '../includes/logo.php'; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include "topbar.php"; ?>      
        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-12">
                            <h1 class="m-0 text-dark text-center text-md-left">Data Admin</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Alert Container -->
            <div id="alert-container" class="container-fluid">
            <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); endif; ?>
            </div>
            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Data Admin</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <!-- <th>ID</th> -->
                                                <th>Profil</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                            <tr data-id="<?= $row['ad_id']; ?>">
                                                <td>
                                                    <?php if (!empty($row['pict']) && file_exists('images/admin/' . $row['pict'])): ?>
                                                        <img src="images/admin/<?= $row['pict']; ?>" alt="Foto Profil" class="img-thumbnail" width="70px">
                                                    <?php else: ?>
                                                        <img src="images/admin/avatar3.png" alt="Foto Profil" class="img-thumbnail" width="70px">
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $row['ad_name']; ?></td>
                                                <td><?= $row['ad_email']; ?></td>
                                                <td>
                                                    <a href="edit-admin.php?id=<?= $row['ad_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <?php if ($row['ad_id'] != $_SESSION['ad_id']): ?>
                                                        <a href="?action=delete&id=<?= $row['ad_id']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                        </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->
        <!-- Footer -->
        <?php include "footer.php"; ?>
    </div>
    <!-- /.wrapper -->
    <!-- Scripts -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.js"></script>
    <script src="dist/js/demo.js"></script>
    <script>
$(document).ready(function () {
    $('.btn-danger').on('click', function (e) {
        if (!confirm('Are you sure?')) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>

<?php
$conn->close();
?>