<?php
include 'conn.php';
include 'auth.php';
// Set timezone
date_default_timezone_set('Asia/Jakarta');
$today = date("Y-m-d H:i:s");

// Ambil data 'about' (hanya 1 record)
$query      = "SELECT * FROM about LIMIT 1";
$result     = mysqli_query($con, $query);
$roww       = mysqli_fetch_assoc($result);
$dataExists = ($roww) ? true : false;

// Inisialisasi nilai kolom baru
$deskripsi_pendek_db = $dataExists ? $roww['deskripsi_pendek'] : '';
$sejarah_pendek_db  = $dataExists ? $roww['sejarah_pendek']  : '';

if (isset($_POST['save'])) {
    // Escape untuk titel & sub‐titel
    $title             = mysqli_real_escape_string($con, $_POST['title']);
    $history_title     = mysqli_real_escape_string($con, $_POST['history_title']);

    // Decode Base64 konten rich text
    $deskripsi_pendek_raw = base64_decode($_POST['deskripsi_pendek']);
    $deskripsi_pendek     = mysqli_real_escape_string($con, $deskripsi_pendek_raw);

    $sejarah_pendek_raw = base64_decode($_POST['sejarah_pendek']);
    $sejarah_pendek     = mysqli_real_escape_string($con, $sejarah_pendek_raw);

    $descrip_raw  = base64_decode($_POST['descrip']);
    $history_raw  = base64_decode($_POST['history']);
    $descrip      = mysqli_real_escape_string($con, $descrip_raw);
    $history      = mysqli_real_escape_string($con, $history_raw);

    // Handle upload gambar
    $lis_img = $dataExists ? $roww['img'] : '';
    if (!empty($_FILES['lis_img']['name'])) {
        $newFileName = rand() . '_' . $_FILES['lis_img']['name'];
        $tempFile    = $_FILES['lis_img']['tmp_name'];
        $folder      = "images/about/" . $newFileName;
        $valid_ext   = ['jpg','jpeg','png'];
        $file_ext    = strtolower(pathinfo($newFileName, PATHINFO_EXTENSION));
        if (in_array($file_ext, $valid_ext)) {
            if (move_uploaded_file($tempFile, $folder)) {
                $lis_img = $newFileName;
            } else {
                $_SESSION['msg'] = "Gagal mengunggah gambar.";
                $_SESSION['msgClass'] = "alert-danger";
            }
        } else {
            $_SESSION['msg'] = "Format gambar tidak valid. Hanya jpg, jpeg, dan png yang diperbolehkan.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    }

    // Siapkan SQL
    if (!$dataExists) {
        $sql = "INSERT INTO about
                (title, deskripsi_pendek, descrip, img, history_title, sejarah_pendek, history, date, status)
                VALUES
                ('$title','$deskripsi_pendek','$descrip','$lis_img','$history_title','$sejarah_pendek','$history','$today','0')";
    } else {
        $sql = "UPDATE about SET
                    title            = '$title',
                    deskripsi_pendek = '$deskripsi_pendek',
                    descrip          = '$descrip',
                    img              = '$lis_img',
                    history_title    = '$history_title',
                    sejarah_pendek   = '$sejarah_pendek',
                    history          = '$history',
                    date             = '$today'
                WHERE id = '".$roww['id']."'";
    }
    $exec = mysqli_query($con, $sql);
    if ($exec) {
        $_SESSION['msg']      = $dataExists ? "Data berhasil diperbarui." : "Data berhasil ditambahkan.";
        $_SESSION['msgClass'] = "alert-success";
    } else {
        $_SESSION['msg']      = "Terjadi kesalahan: " . mysqli_error($con);
        $_SESSION['msgClass'] = "alert-danger";
    }
    header("Location: add-about.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "title.php"; ?>
    <!-- CSS & JS Dependencies -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include '../includes/logo.php'; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include "topbar.php"; ?>
    <?php include "sidebar.php"; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Edit Tentang Kami</h1>
        </section>
        <section class="content">
            <div class="container">
                <!-- Alert -->
                <?php if (!empty($_SESSION['msg'])): ?>
                    <div class="alert <?php echo $_SESSION['msgClass']; ?> alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['msg']; unset($_SESSION['msg'], $_SESSION['msgClass']); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <form action="" method="post" enctype="multipart/form-data"
                      class="row g-3 needs-validation" novalidate>
                    <!-- Judul -->
                    <div class="col-12">
                        <label class="form-label">Judul</label>
                        <input type="text" name="title" class="form-control" required
                               value="<?php echo $dataExists ? htmlspecialchars($roww['title']) : ''; ?>">
                        <div class="invalid-feedback">Mohon isi judul.</div>
                    </div>

                    <!-- Deskripsi Pendek -->
                    <div class="col-12">
                        <label class="form-label">Deskripsi Singkat Untuk Halaman Beranda</label>
                        <input type="hidden" name="deskripsi_pendek" value="">
                        <textarea style="height: 200px;" id="validationDescripPendek" class="form-control textarea-pendek"><?php if ($dataExists) echo $roww['deskripsi_pendek']; ?></textarea>
                        <div class="invalid-feedback">Mohon isi deskripsi pendek.</div>
                    </div>
                    <!-- Deskripsi Tentang Kami -->
                    <div class="col-12">
                        <label class="form-label">Deskripsi dan Visi Misi</label>
                        <input type="hidden" name="descrip" value="">
                        <textarea id="validationDescrip" class="form-control textarea"><?php if ($dataExists) echo $roww['descrip']; ?></textarea>
                        <div class="invalid-feedback">Mohon isi deskripsi.</div>
                    </div>

                    <!-- Gambar -->
                    <div class="col-md-12">
                        <label for="validationImage" class="form-label">Gambar</label><br>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="validationImage" name="lis_img" accept="image/*">
                            <label class="custom-file-label" for="validationImage">Pilih Gambar</label>
                            <div class="invalid-feedback">Mohon unggah gambar (jpg/jpeg/png).</div>
                        </div>
                        <?php if ($dataExists && !empty($roww['img'])):
                            $imagePath = "images/about/" . $roww['img']; ?>
                            <br>
                            <img src="<?php echo htmlspecialchars($imagePath); ?>?v=<?php echo time(); ?>" alt="Current Image" style="width:200px; margin-top:10px;">
                            <br>
                            <a href="add-about.php?delete_image=1" class="btn btn-danger btn-sm" style="margin-top:10px;">Hapus Gambar</a>
                        <?php endif; ?>
                    </div>

                    <!-- Judul Sejarah -->
                    <div class="col-12">
                        <label class="form-label">Judul Sejarah</label>
                        <input type="text" name="history_title" class="form-control" required
                               value="<?php echo $dataExists ? htmlspecialchars($roww['history_title']) : ''; ?>">
                        <div class="invalid-feedback">Mohon isi judul sejarah.</div>
                    </div>

                    <!-- Sejarah Pendek -->
                    <div class="col-12">
                        <label class="form-label">Sejarah Singkat Untuk Halaman Beranda</label>
                        <input type="hidden" name="sejarah_pendek" value="">
                        <textarea style="height: 200px;" id="validationSejarahPendek" class="form-control textarea-pendek"><?php if ($dataExists) echo $roww['sejarah_pendek']; ?></textarea>
                        <div class="invalid-feedback">Mohon isi sejarah pendek.</div>
                    </div>
                    <!-- Isi Sejarah -->
                    <div class="col-12">
                        <label class="form-label">Isi Sejarah</label>
                        <input type="hidden" name="history" value="">
                        <textarea id="validationHistory" class="form-control textarea"><?php if ($dataExists) echo $roww['history']; ?></textarea>
                        <div class="invalid-feedback">Mohon isi sejarah.</div>
                    </div>

                    <!-- Tombol -->
                    <div class="col-12">
                        <button type="submit" name="save" class="btn btn-primary btn-lg">Perbarui</button>
                        <a href="add-about.php" class="btn btn-danger btn-lg">Batal</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <?php include "footer.php"; ?>
</div>

<!-- JS -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script>
$(function () {
    // Inisialisasi Summernote
    // $('#validationDescripPendek, #validationSejarahPendek').summernote({
    //     height: 100
    // });
    // Inisialisasi Summernote
    $('.textarea').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear', 'fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
    });

    // Encode konten sebelum submit
    $('form.needs-validation').on('submit', function (e) {
        var encDes = btoa(unescape(encodeURIComponent($('#validationDescripPendek').summernote('code'))));
        $('input[name="deskripsi_pendek"]').val(encDes);

        var encSej = btoa(unescape(encodeURIComponent($('#validationSejarahPendek').summernote('code'))));
        $('input[name="sejarah_pendek"]').val(encSej);

        var encDescrip   = btoa(unescape(encodeURIComponent($('#validationDescrip').summernote('code'))));
        $('input[name="descrip"]').val(encDescrip);

        var encHistory   = btoa(unescape(encodeURIComponent($('#validationHistory').summernote('code'))));
        $('input[name="history"]').val(encHistory);

        if (!this.checkValidity()) {
            e.preventDefault(); e.stopPropagation();
        }
        this.classList.add('was-validated');
    });
});
</script>
<script>
document.getElementById("validationImage").addEventListener("change", function () {
    var fileName = this.files[0] ? this.files[0].name : "Tidak ada gambar yang dipilih";
    this.nextElementSibling.innerText = fileName;
});
</script>
</body>
</html>

<?php
// Hapus gambar jika diminta
if (isset($_GET['delete_image']) && $dataExists && !empty($roww['img'])) {
    $path = "images/about/" . $roww['img'];
    if (file_exists($path)) unlink($path);
    mysqli_query($con, "UPDATE about SET img = '' WHERE id = '".$roww['id']."'");
    $_SESSION['msg'] = "Gambar berhasil dihapus.";
    $_SESSION['msgClass'] = "alert-success";
    header("Location: add-about.php");
    exit;
}
?>
