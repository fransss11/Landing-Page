<?php 
include 'conn.php';

session_start();

if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    if ($id == $_SESSION['ad_id']) {
        echo 'cannot_delete';
    } else {
        $query = "DELETE FROM admin WHERE ad_id = $id";
        if (mysqli_query($con, $query)) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
    exit;
}

$query = "SELECT * FROM admin";
$result = mysqli_query($con, $query) or die("Query Error: " . mysqli_error($con));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Admin</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
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
            <div id="alert-container" class="container-fluid"></div>

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
                                                    <!-- <td><?= $row['ad_id']; ?></td> -->
                                                    <td>
                                                        <?php if (!empty($row['pict']) && file_exists('images/admin/' . $row['pict'])): ?>
                                                            <img src="images/admin/<?= $row['pict']; ?>" alt="Profile Picture" class="img-thumbnail" width="70px">
                                                        <?php else: ?>
                                                            <img src="images/admin/avatar3.png" alt="Profile Picture" class="img-thumbnail" width="70px">
                                                        <?php endif; ?>
                                                    </td>

                                                    <td><?= $row['ad_name']; ?></td>
                                                    <td><?= $row['ad_email']; ?></td>
                                                    <td>
                                                        <a href="edit-admin.php?id=<?= $row['ad_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                        <?php if ($row['ad_id'] != $_SESSION['ad_id']) : ?>
                                                            <a href="#" class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['ad_id']; ?>">Hapus</a>
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
        $(document).ready(function() {
            $('.delete-btn').click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var row = $(this).closest('tr');

                if (confirm('Yakin ingin menghapus?')) {
                    $.ajax({
                        url: 'data_admin.php',
                        type: 'POST',
                        data: { delete_id: id },
                        success: function(response) {
                            if (response === 'success') {
                                // Tampilkan alert sukses dan hapus baris dari tabel
                                $('#alert-container').html(
                                    '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                    'Deleted Successfully' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                    '<span aria-hidden="true">&times;</span>' +
                                    '</button>' +
                                    '</div>'
                                );
                                row.remove();
                            } else if (response === 'cannot_delete') {
                                $('#alert-container').html(
                                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                                    'Anda tidak dapat menghapus admin yang sedang login.' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                    '<span aria-hidden="true">&times;</span>' +
                                    '</button>' +
                                    '</div>'
                                );
                            } else {
                                $('#alert-container').html(
                                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                    'Gagal menghapus data.' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                    '<span aria-hidden="true">&times;</span>' +
                                    '</button>' +
                                    '</div>'
                                );
                            }
                        },
                        error: function() {
                            $('#alert-container').html(
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                'Terjadi kesalahan.' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</div>'
                            );
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>