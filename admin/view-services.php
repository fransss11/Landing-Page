<?php
include 'conn.php';
include 'auth.php';

// Tangani penghapusan data sebelum output
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($con, $_GET['delete_id']);
    $query_delete = "DELETE FROM services WHERE id='$delete_id'";
    $p = mysqli_query($con, $query_delete);
    if ($p) {
        $_SESSION['msg'] = "Berhasil Dihapus";
        $_SESSION['msgClass'] = "success";
    } else {
        $_SESSION['msg'] = "Terjadi kesalahan saat menghapus layanan.";
        $_SESSION['msgClass'] = "danger";
    }
    header("Location: view-services.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include "title.php"; ?>
    <!-- Meta responsif -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tema -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        /* Buat tabel bisa di-scroll jika terlalu lebar */
        .table-responsive {
            overflow-x: auto;
            white-space: nowrap;
        }

        /* Styling tambahan untuk tabel agar lebih rapi */
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        /* Pastikan kolom tidak terlalu lebar */
        .table td, .table th {
            word-wrap: break-word;
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        /* Pastikan teks dalam tabel tetap rapi */
        td {
            vertical-align: middle;
        }

        table.dataTable thead>tr>th.dt-orderable-asc,
        table.dataTable thead>tr>th.dt-orderable-desc,
        table.dataTable thead>tr>td.dt-orderable-asc,
        table.dataTable thead>tr>td.dt-orderable-desc {
            text-align: center;
        }

        /* Pastikan semua kolom sejajar di tengah */
        .table th, .table td {
            padding: 15px;
            border: 1px solid #ddd;
            vertical-align: middle !important;
            text-align: center;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        /* Pastikan gambar tidak terlalu besar */
        .table img {
            width: 100px;
            height: auto;
            object-fit: contain;
        }

        /* Grup tombol agar tetap sejajar */
        .btn-group {
            display: flex;
            justify-content: center;
            gap: 5px;
        }
        /* Sesuaikan ukuran tombol agar lebih proporsional */
        .btn-group .btn {
            padding: 6px 12px;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 45px;
            min-height: 35px;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include "topbar.php"; ?>
        <!-- Sidebar Utama -->
        <?php include "sidebar.php"; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Header Konten -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Semua Layanan</h1>
                        </div>
                        <div class="col-sm-6" style="text-align:right;">
                            <a class="btn btn-primary" href="add-services.php">
                                <i class="fa fa-plus" aria-hidden="true"></i> Tambah Baru
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Konten Utama -->
            <section class="content">
                <?php if (!empty($_SESSION['msg'])): ?>
                    <div style="max-width: 600px; margin: 0 auto;">
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
                    ?>
                <?php endif; ?>

                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Lihat</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Sembunyikan">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <!-- Tabel dengan id untuk inisialisasi DataTables -->
                            <table id="myTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Judul</th>
                                        <th>Deskripsi Pendek</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan di-load secara otomatis melalui AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include "footer.php"; ?>
        <!-- Control Sidebar (jika diperlukan) -->
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE untuk demo -->
    <script src="dist/js/demo.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- DataTables JS -->
    <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script>
        // Inisialisasi DataTables dengan server-side processing
        let table = new DataTable('#myTable', {
            language: {
                search: "Cari :"
            },
            serverSide: true,
            ajax: 'ajax.php?action=fetch_services', // Mengambil data secara AJAX
            order: [], // Nonaktifkan ordering default sehingga menggunakan ordering server (id DESC)
            lengthChange: false,
            columns: [
                { 
                    data: 'img', 
                    render: function(data, type, row) {
                        return '<img src="images/services/' + data + '" alt="Gambar Layanan">';
                    }
                },
                { data: 'title' },
                { data: 'short' },
                { 
                    data: 'descrip', 
                    render: function(data, type, row) {
                        // Hapus tag HTML dan batasi jumlah karakter
                        let stripped = data.replace(/(<([^>]+)>)/gi, "");
                        return (stripped.length > 100) ? stripped.substr(0, 100) + '...' : stripped;
                    }
                },
                { data: 'date' },
                { data: 'aksi' }
            ]
        });
    </script>
</body>
</html>
