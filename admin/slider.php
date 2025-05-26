<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include koneksi dan autentikasi
include 'conn.php';
include 'auth.php';
date_default_timezone_set('Asia/Jakarta');
$today = date("D d M Y");

// Cek koneksi ke database
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Cek apakah tabel slider sudah ada, jika belum buat tabelnya
$check_table = mysqli_query($con, "SHOW TABLES LIKE 'slider'");
if(mysqli_num_rows($check_table) == 0) {
    // Tabel belum ada, buat tabel dengan kolom urutan
    $create_table = "CREATE TABLE `slider` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `gambar_beranda` VARCHAR(255) NOT NULL,
        `urutan` INT NOT NULL DEFAULT 999,
        `upload_on` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if(!mysqli_query($con, $create_table)) {
        die("Error creating table: " . mysqli_error($con));
    } else {
        $_SESSION['message'] = "Tabel slider berhasil dibuat";
    }
} else {
    // Cek apakah kolom urutan sudah ada
    $check_column = mysqli_query($con, "SHOW COLUMNS FROM `slider` LIKE 'urutan'");
    if(mysqli_num_rows($check_column) == 0) {
        // Tambahkan kolom urutan jika belum ada
        mysqli_query($con, "ALTER TABLE `slider` ADD `urutan` INT NOT NULL DEFAULT 999");
    }
}

// Handle naik/turun urutan
if(isset($_GET['naik']) && is_numeric($_GET['naik'])) {
    $id = intval($_GET['naik']);
    // Dapatkan data slider saat ini
    $res = mysqli_query($con, "SELECT * FROM slider WHERE id=$id");
    if($row = mysqli_fetch_assoc($res)) {
        $urutan_saat_ini = $row['urutan'];
        // Dapatkan slider di atasnya (urutan lebih kecil)
        $res_atas = mysqli_query($con, "SELECT * FROM slider WHERE urutan < $urutan_saat_ini ORDER BY urutan DESC LIMIT 1");
        if($row_atas = mysqli_fetch_assoc($res_atas)) {
            // Tukar urutan
            mysqli_query($con, "UPDATE slider SET urutan = {$row_atas['urutan']} WHERE id = $id");
            mysqli_query($con, "UPDATE slider SET urutan = $urutan_saat_ini WHERE id = {$row_atas['id']}");
            $_SESSION['message'] = "Urutan berhasil diubah";
        }
    }
    header("Location: slider.php");
    exit;
}

if(isset($_GET['turun']) && is_numeric($_GET['turun'])) {
    $id = intval($_GET['turun']);
    // Dapatkan data slider saat ini
    $res = mysqli_query($con, "SELECT * FROM slider WHERE id=$id");
    if($row = mysqli_fetch_assoc($res)) {
        $urutan_saat_ini = $row['urutan'];
        // Dapatkan slider di bawahnya (urutan lebih besar)
        $res_bawah = mysqli_query($con, "SELECT * FROM slider WHERE urutan > $urutan_saat_ini ORDER BY urutan ASC LIMIT 1");
        if($row_bawah = mysqli_fetch_assoc($res_bawah)) {
            // Tukar urutan
            mysqli_query($con, "UPDATE slider SET urutan = {$row_bawah['urutan']} WHERE id = $id");
            mysqli_query($con, "UPDATE slider SET urutan = $urutan_saat_ini WHERE id = {$row_bawah['id']}");
            $_SESSION['message'] = "Urutan berhasil diubah";
        }
    }
    header("Location: slider.php");
    exit;
}

// Handle tambah gambar
if (isset($_POST['tambah'])) {
    $file = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = "gambar/kegiatan/";

    // Generate random filename to prevent duplicates
    $filename = rand() . '_' . $file;
    
    if ($file) {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        
        if (move_uploaded_file($tmp, $folder.$filename)) {
            // Cari urutan terkecil yang tersedia
            $result = mysqli_query($con, "SELECT MAX(urutan) as max_urutan FROM slider");
            $row = mysqli_fetch_assoc($result);
            $urutan = 1; // default jika belum ada data
            
            if($row['max_urutan']) {
                $urutan = $row['max_urutan'] + 1;
            }
            
            $stmt = mysqli_prepare($con, "INSERT INTO slider (gambar_beranda, urutan) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "si", $filename, $urutan);
            mysqli_stmt_execute($stmt);
            
            $_SESSION['message'] = "Gambar berhasil ditambahkan";
        } else {
            $_SESSION['message'] = "Gagal mengupload gambar";
        }
    }
    header("Location: slider.php");
    exit;
}

// Handle hapus gambar
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    // Hapus file fisik
    $res = mysqli_query($con, "SELECT gambar_beranda FROM slider WHERE id=$id");
    if ($row = mysqli_fetch_assoc($res)) {
        $file = $row['gambar_beranda'];
        $path = "gambar/kegiatan/".$file;
        if (file_exists($path)) unlink($path);
    }
    mysqli_query($con, "DELETE FROM slider WHERE id=$id");
    $_SESSION['message'] = "Gambar berhasil dihapus";
    header("Location: slider.php");
    exit;
}

// Handle edit gambar
if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $file = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = "gambar/kegiatan/";
    
    if ($file) {
        // Generate random filename
        $filename = rand() . '_' . $file;
        
        // Hapus file lama
        $res = mysqli_query($con, "SELECT gambar_beranda FROM slider WHERE id=$id");
        if ($row = mysqli_fetch_assoc($res)) {
            $old = $row['gambar_beranda'];
            $path = $folder.$old;
            if (file_exists($path)) unlink($path);
        }
        
        if (move_uploaded_file($tmp, $folder.$filename)) {
            $stmt = mysqli_prepare($con, "UPDATE slider SET gambar_beranda=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "si", $filename, $id);
            mysqli_stmt_execute($stmt);
            $_SESSION['message'] = "Gambar berhasil diperbarui";
        } else {
            $_SESSION['message'] = "Gagal mengupload gambar";
        }
    }
    header("Location: slider.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include "title.php"; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <?php include '../includes/logo.php'; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include "topbar.php"; ?>
        <?php include "sidebar.php"; ?>
        
        <div class="content-wrapper">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="container mt-3">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_SESSION['message']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Kelola Gambar Beranda</h1>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Daftar Gambar Beranda</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Gambar</th>
                                                    <th>Upload On</th>
                                                    <th width="10%">Urutan</th>
                                                    <th width="20%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                $res = mysqli_query($con, "SELECT * FROM slider ORDER BY urutan ASC");
                                                if(!$res) {
                                                    echo "<tr><td colspan='5' class='text-center text-danger'>Error: " . mysqli_error($con) . "</td></tr>";
                                                } else {
                                                    while ($row = mysqli_fetch_assoc($res)):
                                                    ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td>
                                                            <img src="gambar/kegiatan/<?= htmlspecialchars($row['gambar_beranda']) ?>" alt="Gambar Beranda" class="img-thumbnail" style="max-width: 200px">
                                                        </td>
                                                        <td><?= $row['upload_on'] ?></td>
                                                        <td><?= $row['urutan'] ?></td>
                                                        <td>
                                                            <a href="?naik=<?= $row['id'] ?>" class="btn btn-info btn-sm" title="Naikkan Urutan"><i class="fas fa-arrow-up"></i></a>
                                                            <a href="?turun=<?= $row['id'] ?>" class="btn btn-info btn-sm" title="Turunkan Urutan"><i class="fas fa-arrow-down"></i></a>
                                                            <a href="?edit=<?= $row['id'] ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                                            <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus gambar ini?')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</a>
                                                        </td>
                                                    </tr>
                                                    <?php endwhile;
                                                    if (mysqli_num_rows($res) == 0): ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">Belum ada data gambar beranda</td>
                                                    </tr>
                                                    <?php endif;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (isset($_GET['edit'])): 
                                $id = intval($_GET['edit']);
                                $res = mysqli_query($con, "SELECT * FROM slider WHERE id=$id");
                                $row = mysqli_fetch_assoc($res);
                            ?>
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Edit Gambar Beranda</h3>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <div class="form-group">
                                            <label>Gambar Saat Ini</label><br>
                                            <img src="gambar/kegiatan/<?= htmlspecialchars($row['gambar_beranda']) ?>" class="img-thumbnail" style="max-width: 300px">
                                        </div>
                                        <div class="form-group">
                                            <label>Upload Gambar Baru</label>
                                            <input type="file" name="gambar" class="form-control-file" required>
                                            <small class="text-muted">Format: JPG, PNG, GIF. Ukuran maksimal: 2MB</small>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="edit" class="btn btn-warning"><i class="fas fa-save"></i> Simpan Perubahan</button>
                                            <a href="slider.php" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Tambah Gambar Beranda</h3>
                                </div>
                                <div class="card-body">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Upload Gambar</label>
                                            <input type="file" name="gambar" class="form-control-file" required>
                                            <small class="text-muted">Format: JPG, PNG, GIF. Ukuran maksimal: 2MB</small>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="tambah" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Gambar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        <?php include "footer.php"; ?>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
    
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
</body>
</html>
