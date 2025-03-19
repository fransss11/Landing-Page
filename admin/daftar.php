<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil input dari form dan lakukan escaping
    $ad_name = mysqli_real_escape_string($con, $_POST['ad_name']);
    $ad_email = mysqli_real_escape_string($con, $_POST['ad_email']);
    $ad_password = mysqli_real_escape_string($con, $_POST['ad_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Validasi agar password dan konfirmasi password sama
    if ($ad_password !== $confirm_password) {
        $_SESSION['error'] = "Password dan konfirmasi password tidak sama.";
    } else {
        // Validasi password dengan regex:
        // Minimal 8 karakter, minimal 1 huruf kecil, 1 huruf besar, 1 angka, dan 1 karakter khusus (@$!%*?&-_)
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\-_]).{8,}$/', $ad_password)) {
            $_SESSION['error'] = "Password tidak valid. Minimal 8 karakter, harus memiliki huruf besar, huruf kecil, angka, dan simbol (termasuk - atau _).";
        } else {
            // Periksa apakah email sudah terdaftar
            $sql_check = "SELECT * FROM admin WHERE ad_email='$ad_email' LIMIT 1";
            $result_check = mysqli_query($con, $sql_check);
            if ($result_check && mysqli_num_rows($result_check) > 0) {
                $_SESSION['error'] = "Email sudah terdaftar. Silahkan gunakan email lain.";
            } else {
                // Proses upload gambar
                $pict = "";
                if (isset($_FILES['ad_pict']) && $_FILES['ad_pict']['error'] == 0) {
                    // Atur ekstensi file yang diperbolehkan
                    $allowed = array("jpg", "jpeg", "png", "gif");
                    $fileName = $_FILES['ad_pict']['name'];
                    $fileTmp = $_FILES['ad_pict']['tmp_name'];
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    if (in_array($fileExt, $allowed)) {
                        // Buat nama file unik untuk mencegah bentrok
                        $newFileName = uniqid() . '.' . $fileExt;
                        $targetDir = "images/admin/";
                        if (move_uploaded_file($fileTmp, $targetDir . $newFileName)) {
                            $pict = $newFileName;
                        } else {
                            $_SESSION['error'] = "Gagal mengupload gambar.";
                        }
                    } else {
                        $_SESSION['error'] = "Format gambar tidak valid. Hanya diperbolehkan: " . implode(", ", $allowed);
                    }
                } else {
                    // Jika tidak ada gambar yang diupload, gunakan gambar default
                    $pict = "avatar3.png";
                }

                // Jika tidak ada error selama proses upload
                if (!isset($_SESSION['error'])) {
                    // Hash password untuk keamanan
                    $hashed_password = password_hash($ad_password, PASSWORD_DEFAULT);

                    // Masukkan data admin baru ke tabel, termasuk nama file gambar
                    $sql_insert = "INSERT INTO admin (ad_name, ad_email, ad_password, pict) VALUES ('$ad_name', '$ad_email', '$hashed_password', '$pict')";
                    if (mysqli_query($con, $sql_insert) === TRUE) {
                        $_SESSION['message'] = "Admin baru berhasil didaftarkan.";
                    } else {
                        $_SESSION['error'] = "Terjadi kesalahan: " . mysqli_error($con);
                    }
                }
            }
        }
    }
    // Redirect untuk menerapkan PRG dan mencegah pesan tetap tampil saat refresh
    header("Location: daftar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Admin Baru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
      /* Opsional: styling khusus untuk ikon mata */
      .input-group-text .toggle-password {
          cursor: pointer;
      }
    </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="/"><b>Admin </b>Register</a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Daftar Akun Baru</p>
      <?php
      if (isset($_SESSION['error'])) {
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
              . $_SESSION['error'] .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>';
          unset($_SESSION['error']);
      }
      if (isset($_SESSION['message'])) {
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'
              . $_SESSION['message'] .
              '<button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>';
          unset($_SESSION['message']);
      }
      ?>
      <!-- Pastikan form mendukung unggahan file -->
      <form action="" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        <div class="input-group mb-3">
          <input type="text" name="ad_name" id="ad_name" class="form-control" placeholder="Nama Admin" required>
          <div class="invalid-feedback">
            Harap masukkan nama admin.
          </div>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" name="ad_email" id="ad_email" class="form-control" placeholder="Email Admin" required>
          <div class="invalid-feedback">
            Harap masukkan email yang valid.
          </div>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <!-- Field Password dengan toggle ikon mata -->
        <div class="input-group mb-3">
          <input type="password" name="ad_password" id="ad_password" class="form-control" placeholder="Password" required 
          pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\-_]).{8,}$">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-eye toggle-password" data-toggle="#ad_password"></span>
            </div>
          </div>
          <div class="invalid-feedback">
            Password minimal 8 karakter dengan huruf besar, huruf kecil, angka, dan simbol.
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Konfirmasi Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-eye toggle-password" data-toggle="#confirm_password"></span>
            </div>
          </div>
          <div class="invalid-feedback">
            Harap konfirmasi password.
          </div>
        </div>
        <!-- Field untuk upload gambar -->
        <div class="input-group mb-3">
          <div class="custom-file">
              <input type="file" name="ad_pict" id="ad_pict" class="custom-file-input" accept="image/*">
              <label class="custom-file-label" for="ad_pict">Pilih Gambar</label>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <a href="login.php" class="btn btn-secondary btn-block">Masuk</a>
          </div>
          <div class="col-6">
            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
          </div>
        </div>
      </form>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<!-- Script validasi Bootstrap -->
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
  
// Perbarui label file ketika memilih gambar
$('.custom-file-input').on('change', function(){
    var fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').html(fileName);
});
</script>
</body>
</html>
