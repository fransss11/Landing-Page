<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include koneksi dan autentikasi
include 'conn.php';
include 'auth.php';
$a = 2;
date_default_timezone_set('Asia/Kolkata');
$today = date("D d M Y");
// Cek koneksi ke database
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
// Ambil data dari tabel info dan social
$info_result = mysqli_query($con, "SELECT * FROM info WHERE id_info='1'");
if (!$info_result) {
    die("Error fetching info: " . mysqli_error($con));
}
$info_row = mysqli_fetch_array($info_result);
$social_result = mysqli_query($con, "SELECT * FROM social WHERE id='1'");
if (!$social_result) {
    die("Error fetching social data: " . mysqli_error($con));
}
$social_row = mysqli_fetch_array($social_result);
// Proses form update jika tombol "update" ditekan
if (isset($_POST['update'])) {
    extract($_POST);
    // Update data ke tabel info, termasuk sapaan
    $update_info = mysqli_query($con, "UPDATE info SET lokasi='$address', gmail='$email', maps_url='$map', nama_maps='$nama_map', profile='$profile', sapaan='$sapaan' WHERE id_info='1'");
    // Hapus logo lama jika diminta
    if (!empty($_POST['delete_logo']) && $info_row['logo']) {
        $old = "images/logo/" . $info_row['logo'];
        if (file_exists($old)) unlink($old);
        mysqli_query($con, "UPDATE info SET logo = NULL WHERE id_info = '1'");
    }
    // Hapus gambar info lama jika diminta
    if (!empty($_POST['delete_gambar']) && !empty($info_row['gambar'])) {
        $old2 = "images/info/" . $info_row['gambar'];
        if (file_exists($old2)) unlink($old2);
        mysqli_query($con, "UPDATE info SET gambar = NULL WHERE id_info = '1'");
    }
    if (!$update_info) {
        die("Error updating info: " . mysqli_error($con));
    }
    // Update data ke tabel social
    $update_social = mysqli_query($con, "UPDATE social SET facebook='$facebook', twitter='$twitter', instagram='$instagram', linkedin='$linkedin', whatsapp='$whatsapp', phone='$phone', nama_maps='$nama_map' WHERE id='1'");
    if (!$update_social) {
        die("Error updating social data: " . mysqli_error($con));
    }
    // Upload logo baru jika ada
    if (!empty($_FILES['logo']['name'])) {
        $logoName   = rand() . '_' . basename($_FILES['logo']['name']);
        $logoTmp    = $_FILES['logo']['tmp_name'];
        $logoFolder = "images/logo/" . $logoName;
        if (move_uploaded_file($logoTmp, $logoFolder)) {
            mysqli_query($con, "UPDATE info SET logo = '$logoName' WHERE id_info = '1'");
        } else {
            $_SESSION['message'] = "Failed to upload logo";
            header("Location: settings.php");
            exit;
        }
    }
    // Upload gambar info baru jika ada
    if (!empty($_FILES['gambar']['name'])) {
        $imgName   = rand() . '_' . basename($_FILES['gambar']['name']);
        $imgTmp    = $_FILES['gambar']['tmp_name'];
        $imgFolder = "images/info/" . $imgName;
        if (move_uploaded_file($imgTmp, $imgFolder)) {
            mysqli_query($con, "UPDATE info SET gambar = '$imgName' WHERE id_info = '1'");
        } else {
            $_SESSION['message'] = "Failed to upload gambar";
            header("Location: settings.php");
            exit;
        }
    }
    $_SESSION['message'] = "Updated Successfully";
    header("Location: settings.php");
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
    <!-- Sertakan CSS Bootstrap & Summernote -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <?php include '../includes/logo.php'; ?>
    <style>
        img.logo { width: 100%; height: auto; }
    </style>
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
                        <div class="col-sm-6"><h1>Pengaturan</h1></div>
                    </div>
                </div>
            </section>
            <section class="content">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-outline card-info">
                                <!-- Logo, Email, Map, Nama Lokasi, Sapaan -->
                                <div class="card-header">
                                    <div class="form-group">
                                        <label>Logo</label>
                                        <?php if ($info_row['logo']): ?>
                                            <img src="images/logo/<?= $info_row['logo']; ?>" alt="Logo" class="logo"><br><br>
                                        <?php endif; ?>
                                        <input name="logo" type="file" class="form-control" accept="image/png, image/jpeg, image/jpg">
                                    </div>
                                </div>
                                <div class="card-header">
                                    <div class="form-group">
                                        <label>Email Perusahaan</label>
                                        <input name="email" value="<?= $info_row['gmail']; ?>" type="text" class="form-control" placeholder="Masukkan email">
                                    </div>
                                </div>
                                <div class="card-header">
                                    <div class="form-group">
                                        <label>Map (Iframe Code)</label>
                                        <textarea rows="5" name="map" class="form-control" placeholder="Masukkan iframe code"><?= $info_row['maps_url']; ?></textarea>
                                    </div>
                                </div>
                                <div class="card-header">
                                    <div class="form-group">
                                        <label>Nama Lokasi</label>
                                        <input name="nama_map" value="<?= $social_row['nama_maps']; ?>" type="text" class="form-control" placeholder="Masukkan Lokasi">
                                    </div>
                                </div>
                                <div class="card-header">
                                    <div class="form-group">
                                        <label>Lokasi</label>
                                        <textarea rows="5" name="address" class="form-control" placeholder="Masukkan address"><?= $info_row['lokasi']; ?></textarea>
                                    </div>
                                </div>
                                <div class="card-header">
                                    <div class="form-group">
                                        <label>Sapaan Pada Beranda</label>
                                        <textarea name="sapaan" class="textarea form-control" rows="5" placeholder="Masukkan sapaan"><?= htmlspecialchars($info_row['sapaan']); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-outline card-info">
                                <!-- Social Links & YouTube -->
                                <div class="card-header"><div class="form-group">
                                    <label>Link Facebook</label>
                                    <input name="facebook" value="<?= $social_row['facebook']; ?>" type="text" class="form-control" placeholder="Masukkan Link Facebook">
                                </div></div>
                                <div class="card-header"><div class="form-group">
                                    <label>Link Twitter</label>
                                    <input name="twitter" value="<?= $social_row['twitter']; ?>" type="text" class="form-control" placeholder="Masukkan Link Twitter">
                                </div></div>
                                <div class="card-header"><div class="form-group">
                                    <label>Link Instagram</label>
                                    <input name="instagram" value="<?= $social_row['instagram']; ?>" type="text" class="form-control" placeholder="Masukkan Link Instagram">
                                </div></div>
                                <div class="card-header"><div class="form-group">
                                    <label>Link LinkedIn</label>
                                    <input name="linkedin" value="<?= $social_row['linkedin']; ?>" type="text" class="form-control" placeholder="Masukkan Link LinkedIn">
                                </div></div>
                                <div class="card-header"><div class="form-group">
                                    <label>Whatsapp</label>
                                    <input name="whatsapp" value="<?= $social_row['whatsapp']; ?>" type="text" class="form-control" placeholder="Masukkan Nomor Whatsapp">
                                </div></div>
                                <div class="card-header"><div class="form-group">
                                    <label>Telepon</label>
                                    <input name="phone" value="<?= $social_row['phone']; ?>" type="text" class="form-control" placeholder="Masukkan Nomor Telepon">
                                </div></div>
                                <div class="card-header"><div class="form-group">
                                    <label>Link Video YouTube</label>
                                    <input name="profile" type="text" class="form-control" placeholder="Masukkan link YouTube" value="<?= htmlspecialchars($info_row['profile']); ?>">
                                </div></div>
                                <!-- Gambar Info -->
                                <div class="card-header">
                                    <label>Gambar Sapaan</label><br>
                                    <?php if (!empty($info_row['gambar'])): ?>
                                        <img src="images/info/<?= $info_row['gambar']; ?>" class="logo mb-2"><br>
                                        <label><input type="checkbox" name="delete_gambar" value="1"> Hapus Gambar Sapaan</label>
                                    <?php endif; ?>
                                    <input type="file" name="gambar" class="form-control-file mt-2" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card-header text-center">
                                <button type="submit" name="update" class="btn btn-warning btn-lg">Update</button>
                                <button type="reset" class="btn btn-danger btn-lg">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </div>
        <?php include "footer.php"; ?>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
    <!-- JS Bootstrap & Summernote -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(function() {
            $('.textarea').summernote({
                height: 550,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear', 'fontname']],
                    ['fontsize', ['fontsize']], // Menambahkan dropdown ukuran font
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                fontSizes: [
                    '8', '9', '10', '11', '12', '14',
                    '16', '18', '20', '22', '24', '26',
                    '28', '30', '32', '36','40', '48', '64'
                ]
            });
        });
    </script>
</body>
</html>
