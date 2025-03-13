<?php
session_start();
include 'conn.php';

$error = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil input dari form dan lakukan escaping
    $ad_name = mysqli_real_escape_string($con, $_POST['ad_name']);
    $ad_email = mysqli_real_escape_string($con, $_POST['ad_email']);
    $ad_password = mysqli_real_escape_string($con, $_POST['ad_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Validasi agar password dan konfirmasi password sama
    if ($ad_password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak sama.";
    } else {
        // Validasi password dengan regex
        // Minimal 8 karakter, minimal 1 huruf kecil, 1 huruf besar, 1 angka, dan 1 karakter khusus (@$!%*?&-_)
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\-_]).{8,}$/', $ad_password)) {
            $error = "Password tidak valid. Minimal 8 karakter, harus memiliki huruf besar, huruf kecil, angka, dan simbol (termasuk - atau _).";
        } else {
            // Periksa apakah email sudah terdaftar
            $sql_check = "SELECT * FROM admin WHERE ad_email='$ad_email' LIMIT 1";
            $result_check = mysqli_query($con, $sql_check);
            if ($result_check && mysqli_num_rows($result_check) > 0) {
                $error = "Email sudah terdaftar. Silahkan gunakan email lain.";
            } else {
                // Hash password untuk keamanan
                $hashed_password = password_hash($ad_password, PASSWORD_DEFAULT);
                // Insert data admin baru ke tabel admin
                $sql_insert = "INSERT INTO admin (ad_name, ad_email, ad_password) VALUES ('$ad_name', '$ad_email', '$hashed_password')";
                if (mysqli_query($con, $sql_insert) === TRUE) {
                    $message = "Admin baru berhasil didaftarkan.";
                } else {
                    $error = "Terjadi kesalahan: " . mysqli_error($con);
                }
            }
        }
    }
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
      /* Optional: styling khusus untuk ikon mata */
      .input-group-text .toggle-password {
          cursor: pointer;
      }
    </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="/"><b>Admin</b>Register</a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new membership</p>
      <?php if (!empty($error)) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?php echo $error; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php } ?>
      <?php if (!empty($message)) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?php echo $message; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php } ?>
      <!-- Form dengan validasi Bootstrap -->
      <form action="" method="post" class="needs-validation" novalidate>
        <div class="input-group mb-3">
          <input type="text" name="ad_name" id="ad_name" class="form-control" placeholder="Nama Admin" required>
          <div class="invalid-feedback">
            Please enter admin name.
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
            Please enter a valid email.
          </div>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <!-- Field Password dengan toggle eye icon -->
        <div class="input-group mb-3">
          <input type="password" name="ad_password" id="ad_password" class="form-control" placeholder="Password" required 
          pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\-_]).{8,}$">
          <div class="input-group-append">
            <div class="input-group-text">
              <!-- Ikon mata untuk toggle password -->
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
              <!-- Ikon mata untuk toggle password -->
              <span class="fas fa-eye toggle-password" data-toggle="#confirm_password"></span>
            </div>
          </div>
          <div class="invalid-feedback">
            Please confirm your password.
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <a href="login.php" class="btn btn-secondary btn-block">Login</a>
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

// Toggle show/hide password
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
</script>
</body>
</html>