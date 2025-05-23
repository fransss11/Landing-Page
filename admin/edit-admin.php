<?php 
include 'conn.php';
session_start();
// Ambil data admin berdasarkan GET id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT ad_id, ad_name, ad_email, ad_password, pict FROM admin WHERE ad_id = $id";
    $result = mysqli_query($con, $query) or die("Query Error: " . mysqli_error($con));
    $admin = mysqli_fetch_assoc($result);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['ad_id'];
    $ad_name = htmlspecialchars($_POST['ad_name']);
    $ad_email = htmlspecialchars($_POST['ad_email']);
    $ad_password = $admin['ad_password']; // Password default (jika tidak diubah)
    $ad_image = $admin['pict']; // Gambar default (jika tidak diubah)
    // Cek apakah field password diisi
    if (!empty($_POST['ad_password'])) {
        $ad_password_raw = $_POST['ad_password'];
        // Regex untuk validasi password:
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\-_]).{8,}$/', $ad_password_raw)) {
            $message = "Password tidak valid. Minimal 8 karakter, harus memiliki huruf besar, huruf kecil, angka, dan simbol (termasuk - atau _).";
        } else {
            $ad_password = password_hash($ad_password_raw, PASSWORD_DEFAULT);
        }
    }
    // Cek apakah gambar diupload
    if (!empty($_FILES['ad_image']['name'])) {
        // Proses upload gambar
        $target_dir = "images/admin/";
        $image_name = basename($_FILES["ad_image"]["name"]);
        $imageFileType = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        // Membuat nama file unik dengan ID admin dan timestamp
        $new_image_name = "admin_".$id."_".time().".".$imageFileType;
        // Cek apakah file gambar valid
        if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif", "webp"])) {
            if (move_uploaded_file($_FILES["ad_image"]["tmp_name"], $target_dir.$new_image_name)) {
                $ad_image = $new_image_name; // Update nama file gambar
            } else {
                $message = "Terjadi kesalahan saat mengupload gambar.";
            }
        } else {
            $message = "Format gambar tidak valid. Harus berupa JPG, JPEG, PNG, GIF, atau WEBP.";
        }
    }
    // Jika tidak ada error dari validasi password dan gambar, lanjutkan update data
    if (!isset($message)) {
        $query = "UPDATE admin SET ad_name = '$ad_name', ad_email = '$ad_email', ad_password = '$ad_password', pict = '$ad_image' WHERE ad_id = $id";
        if (mysqli_query($con, $query)) {
            // Update session jika admin yang sedang login sedang diubah
            if ($_SESSION['ad_id'] == $id) {
                $_SESSION['ad_name'] = $ad_name;
                $_SESSION['ad_email'] = $ad_email;
            }
            header("Location: data_admin.php");
            exit();
        } else {
            $message = "Error: " . $query . "<br>" . mysqli_error($con);
        }
    }
}
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
    <style>
      /* Styling untuk ikon mata agar pointer */
      .input-group-text .toggle-password {
          cursor: pointer;
      }
    </style>
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
                            <h1 class="m-0 text-dark text-center text-md-left">Edit Admin</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <?php if (!empty($message)) { ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php echo $message; ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php } ?>
                            <!-- Form dengan validasi Bootstrap -->
                            <form action="" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
                                <input type="hidden" name="ad_id" value="<?= $admin['ad_id']; ?>">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label for="validationAdName">Nama Admin</label>
                                            <input name="ad_name" type="text" class="form-control" id="validationAdName" value="<?= htmlspecialchars($admin['ad_name']); ?>" placeholder="Masukkan nama admin..." required>
                                            <div class="invalid-feedback">
                                                Harap masukkan nama admin.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label for="validationAdEmail">Email Admin</label>
                                            <input name="ad_email" type="email" class="form-control" id="validationAdEmail" value="<?= htmlspecialchars($admin['ad_email']); ?>" placeholder="Masukkan email admin..." required>
                                            <div class="invalid-feedback">
                                                Harap masukkan email yang valid.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label for="validationAdPassword">Password (Abaikan jika tidak ingin mengubah)</label>
                                            <!-- Input group dengan ikon mata untuk toggle password -->
                                            <div class="input-group">
                                                <input name="ad_password" type="password" class="form-control" id="validationAdPassword" placeholder="Masukkan password baru..."
                                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\-_]).{8,}$">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-eye toggle-password" data-toggle="#validationAdPassword"></span>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Password minimal 8 karakter dengan huruf besar, huruf kecil, angka, dan simbol.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <div class="form-group">
                                            <label for="validationAdImage">Gambar Profil (Maksimal 10MB)</label>
                                            <div class="custom-file">
                                                <input type="file" name="ad_image" class="custom-file-input" id="validationAdImage" accept="image/*">
                                                <label class="custom-file-label" for="validationAdImage">Pilih gambar...</label>
                                            </div>
                                            <small id="imageError" class="text-danger"></small> <!-- Pesan error akan muncul di sini -->
                                            <?php if (!empty($admin['pict'])): ?>
                                                <div class="mt-2">
                                                    <label>Gambar Profil Saat Ini:</label>
                                                    <img src="images/admin/<?= $admin['pict']; ?>" class="img-thumbnail" width="100">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg">Perbarui Admin</button>
                                            <a href="data_admin.php" class="btn btn-danger btn-lg">Kembali</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- Footer -->
        <?php include "footer.php"; ?>
    </div>
    <!-- /.wrapper -->
    <!-- Scripts -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.js"></script>
    <!-- Bootstrap Validation Script -->
    <script>
        (function () {
          'use strict';
          var forms = document.querySelectorAll('.needs-validation');
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
        // Toggle tampil/sembunyikan password
        $(document).on('click', '.toggle-password', function() {
            var input = $($(this).attr('data-toggle'));
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                $(this).removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                $(this).removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
        // Perbarui label input file dengan nama file yang dipilih
        $("#validationAdImage").change(function(e) {
            var fileName = e.target.files[0].name;
            $(this).next('.custom-file-label').html(fileName);
        });
    </script>
    <script>
        document.getElementById("validationAdImage").addEventListener("change", function() {
            var file = this.files[0]; // Ambil file yang diupload
            var errorText = document.getElementById("imageError"); // Elemen pesan error
            var fileInput = document.getElementById("validationAdImage"); // Input file
            if (file) {
                var fileSize = file.size; // Ukuran file dalam byte
                if (fileSize > 10485760) { // 10MB = 10485760 byte
                    errorText.textContent = "Ukuran gambar terlalu besar! Maksimal 10MB.";
                    fileInput.value = ""; // Kosongkan input agar tidak bisa diunggah
                } else {
                    errorText.textContent = ""; // Hapus pesan error jika ukuran sesuai
                }
            }
        });
        // Mencegah form dikirim jika ada error
        document.querySelector("form").addEventListener("submit", function(event) {
            var errorText = document.getElementById("imageError").textContent;
            if (errorText !== "") {
                event.preventDefault(); // Batalkan submit jika ada error
            }
        });
    </script>
</body>
</html>