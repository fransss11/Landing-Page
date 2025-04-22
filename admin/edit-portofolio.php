<?php
include 'conn.php';
include 'auth.php';

date_default_timezone_set('Asia/Jakarta'); // Set timezone to Indonesia (WIB)

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $query = "SELECT * FROM proposal WHERE id_pro = '$id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $file = '';
    $today = date("Y-m-d H:i:s", time()); // Get the current date and time

    if (!empty($_FILES['pdf']['name'])) {
        $file = rand() . '_' . $_FILES['pdf']['name'];
        $tempFile = $_FILES['pdf']['tmp_name'];
        $folder = "../pdf/" . $file;
        // Allow only pdf and image extensions
        $valid_ext = ['pdf', 'jpg', 'jpeg', 'png'];
        $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $file_size = $_FILES['pdf']['size'];

        if (in_array($file_ext, $valid_ext)) {
            if (in_array($file_ext, ['jpg', 'jpeg', 'png']) && $file_size > 512000) { // Check image size (500KB = 512000 bytes)
                $_SESSION['msg'] = "Gambar yang diunggah harus berukuran maksimal 500KB.";
                $_SESSION['msgClass'] = "alert-danger";
                header("Location: edit-portofolio.php?id=$id");
                exit;
            }
            move_uploaded_file($tempFile, $folder);
        } else {
            $_SESSION['msg'] = "File yang diunggah harus berupa PDF atau gambar.";
            $_SESSION['msgClass'] = "alert-danger";
            header("Location: edit-portofolio.php?id=$id");
            exit;
        }
    }

    $updateQuery = "UPDATE proposal SET name = '$name', date = '$today'"; // Update the date to the current time
    if ($file) {
        $updateQuery .= ", pdf = '$file'";
    }
    $updateQuery .= " WHERE id_pro = '$id'";

    if (mysqli_query($con, $updateQuery)) {
        $_SESSION['msg'] = "Portofolio berhasil diperbarui.";
        $_SESSION['msgClass'] = "alert-success";
    } else {
        $_SESSION['msg'] = "Terjadi kesalahan saat memperbarui portofolio: " . mysqli_error($con);
        $_SESSION['msgClass'] = "alert-danger";
    }

    header("Location: add-portofolio.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Bootstrap & AdminLTE CSS -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Proposal</title>
    <?php include '../includes/logo.php'; ?>
</head>
<body>
    <div class="container">
        <h1>Edit Proposal</h1>
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="message <?php echo $_SESSION['msgClass']; ?>">
                <?php echo $_SESSION['msg']; unset($_SESSION['msg'], $_SESSION['msgClass']); ?>
            </div>
        <?php endif; ?>
        <?php if ($row): ?>
            <?php if ($id && $row && !empty($row['pdf']) && file_exists("../pdf/" . $row['pdf'])): ?>
                <div class="existing-pdf mb-4">
                    <h4>PDF saat ini:</h4>
                    <iframe src="<?php echo "../pdf/" . $row['pdf']; ?>" style="width:100%; height:400px;" frameborder="0"></iframe>
                </div>
            <?php endif; ?>
            <form action="edit-portofolio.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <input type="hidden" name="id" value="<?php echo $row['id_pro']; ?>">
                <label for="name">Nama Proposal:</label>
                <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>" class="form-control" required>
                <div class="invalid-feedback">
                    Mohon masukkan nama proposal.
                </div>
                <label for="pdf">Tambahkan Proposal baru:</label>
                <input type="file" id="pdf" name="pdf" accept=".pdf,.jpg,.jpeg,.png" class="form-control">
                <!-- Area preview file -->
                <div id="preview" style="margin-top:20px;"></div>
                <button type="submit" name="update" class="btn btn-success">Perbarui</button>
                <a href="add-portofolio.php" class="btn btn-secondary">Batal</a>
            </form>
        <?php else: ?>
            <p>Proposal tidak ditemukan.</p>
        <?php endif; ?>
    </div>
    <script>
    // Enable Bootstrap validation styles
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
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
    <script>
    document.getElementById('pdf').addEventListener('change', function(e) {
        var file = e.target.files[0];
        var preview = document.getElementById('preview');
        preview.innerHTML = ''; // Kosongkan preview sebelumnya

        if (file) {
            var fileType = file.type;
            
            // Jika file berupa gambar
            if (fileType.startsWith("image/")) {
                var img = document.createElement("img");
                img.style.maxWidth = "300px";
                img.style.maxHeight = "300px";
                img.className = "img-fluid";
                var reader = new FileReader();
                reader.onload = function(event) {
                    img.src = event.target.result;
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
            // Jika file berupa PDF
            else if (fileType === "application/pdf") {
                var iframe = document.createElement("iframe");
                iframe.style.width = "100%";
                iframe.style.height = "500px";
                iframe.src = URL.createObjectURL(file);
                preview.appendChild(iframe);
            }
            // Untuk tipe file lain (jika diperlukan)
            else {
                preview.innerHTML = "<p>Preview tidak tersedia untuk tipe file ini.</p>";
            }
        }
    });
    </script>
</body>
</html>