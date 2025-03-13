<?php
include 'conn.php';
include 'auth.php';

// Ambil data admin dari database
$query = "SELECT ad_name, ad_email FROM admin WHERE ad_id = 1";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Custom Styles -->
    <style>
        @media (max-width: 768px) {
            .small-box h3 {
                font-size: 1.2rem;
            }

            .small-box p {
                font-size: 0.9rem;
            }

            .navbar-nav .nav-item img {
                width: 30px;
                height: 30px;
            }

            .nav-link span {
                display: none;
            }
        }

        .user-dropdown .dropdown-menu {
            width: 200px;
            text-align: center;
        }

        .user-dropdown img {
            width: 50px;
            height: 50px;
        }

        .navbar-nav .nav-item .nav-link {
            display: flex;
            align-items: center;
        }

        .small-box-footer {
            text-decoration: none;
        }

        .content-wrapper {
            transition: all 0.3s ease-in-out;
            margin-left: 250px; /* Sidebar default terbuka */
            min-height: 100vh; /* Supaya tidak terpotong */
        }

        @media (max-width: 992px) {
            .content-wrapper {
                margin-left: 0; /* Sidebar tertutup otomatis */
            }
        }

        .sidebar-collapsed .content-wrapper {
            margin-left: 0 !important; /* Jika sidebar ditutup */
        }


        .sidebar-hidden .content-wrapper {
            margin-left: 0 !important; /* Jika sidebar disembunyikan */
        }

        .wrapper {
            min-height: 100vh; /* Supaya konten tidak terpotong */
        }


    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include"topbar.php"; ?>
        
        <!-- /.navbar -->

        <!-- Sidebar -->
        <?php include "sidebar.php"; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-12">
                            <h1 class="m-0 text-dark text-center text-md-left">Dashboard</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Tambahkan Berita -->
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

                        <!-- Lihat Berita -->
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
                </div>
            </section>
        </div>

        <!-- Footer -->
        <?php include "footer.php"; ?>

    </div>

    <!-- Scripts -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.js"></script>
    <script>
    $(document).ready(function () {
            $("#sidebarToggle").click(function () {
                $("body").toggleClass("sidebar-collapsed");
            });
        });
    </script>

</body>

</html>