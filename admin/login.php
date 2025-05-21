<?php
include('conn.php');
session_start();
$error = "";
if (isset($_POST['submit'])) {
    // Bersihkan input dari user
    $ad_email = mysqli_real_escape_string($con, $_POST['ad_email']);
    $ad_pass  = mysqli_real_escape_string($con, $_POST['ad_pass']);
    // Query untuk mengambil data admin berdasarkan email
    $query = "SELECT * FROM admin WHERE ad_email='$ad_email' LIMIT 1";
    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $check_fetch = mysqli_fetch_array($result);
        // Verifikasi password yang di-hash menggunakan password_verify()
        if (password_verify($ad_pass, $check_fetch['ad_password'])) {
            $_SESSION['ad_id'] = $check_fetch['ad_id'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="robots" content="noindex" />
  <title>Admin Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
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
  <!-- Additional fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    /* Enhanced styling for a more attractive login page */
    body.login-page {
      background: linear-gradient(135deg, rgba(76, 0, 158, 0.8), rgba(117, 51, 165, 0.7)), url('images/8845961_4004353.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      font-family: 'Poppins', 'Source Sans Pro', sans-serif;
    }
    
    .login-box {
      margin-top: 5% !important;
      max-width: 400px;
      transition: all 0.3s ease;
    }
    
    .login-logo {
      margin-bottom: 25px;
    }
    
    .login-logo a {
      color: #fff;
      font-size: 2.2rem;
      font-weight: 600;
      text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
      letter-spacing: 1px;
      transition: all 0.3s ease;
    }
    
    .login-logo a:hover {
      text-shadow: 2px 2px 12px rgba(0,0,0,0.7);
    }
    
    .card {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 15px 30px rgba(0,0,0,0.4);
      transition: all 0.3s ease;
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    }
    
    .card-body.login-card-body {
      background: rgba(255, 255, 255, 0.95);
      padding: 35px 30px;
      border-top: 5px solid #8e44ad;
    }
    
    .login-box-msg {
      color: #555;
      font-size: 1.1rem;
      font-weight: 500;
      margin-bottom: 25px;
      text-align: center;
    }
    
    .input-group {
      margin-bottom: 20px !important;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
    
    .form-control {
      border-radius: 25px;
      padding: 12px 15px;
      height: auto;
      border: 1px solid #ddd;
      transition: all 0.3s;
    }
    
    .form-control:focus {
      border-color: #8e44ad;
      box-shadow: 0 0 0 0.2rem rgba(142, 68, 173, 0.25);
    }
    
    .input-group-text {
      border-radius: 0 25px 25px 0 !important;
      background: #f8f9fa;
      border: 1px solid #ddd;
      border-left: 0;
      color: #8e44ad;
      transition: all 0.3s;
    }
    
    .btn-primary {
      border-radius: 25px;
      padding: 10px 20px;
      font-size: 16px;
      font-weight: 500;
      background: linear-gradient(135deg, #8e44ad, #9b59b6);
      border-color: transparent;
      box-shadow: 0 5px 15px rgba(142, 68, 173, 0.4);
      transition: all 0.3s;
    }
    
    .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
      background: linear-gradient(135deg, #9b59b6, #8e44ad);
      border-color: transparent;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(142, 68, 173, 0.6);
    }
    
    .alert-danger {
      border-radius: 10px;
      background-color: rgba(231, 76, 60, 0.1);
      border-left: 4px solid #e74c3c;
      color: #e74c3c;
      padding: 12px 15px;
      font-weight: 500;
      margin-bottom: 25px;
    }
    
    /* Animation for the login box */
    @keyframes fadeIn {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    
    .login-box {
      animation: fadeIn 0.8s ease forwards;
    }
  </style>
  <?php include '../includes/logo.php'; ?>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="/"><b>Admin </b>Portal</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Silahkan Masuk ke Dashboard Admin</p>
      <?php if (!empty($error)) { ?>
      <div class="alert alert-danger"><i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?></div>
      <?php } ?>
      <form method="post" action="">
        <div class="input-group">
          <input type="email" name="ad_email" class="form-control" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group">
          <input type="password" name="ad_pass" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <button type="submit" name="submit" class="btn btn-primary btn-block">
              <i class="fas fa-sign-in-alt mr-2"></i>Masuk
            </button>
          </div>
          <!-- <div class="col-6">
            <a href="daftar.php" class="btn btn-secondary btn-block">Daftar</a>
          </div> -->
        </div>
         <!-- <div class="row pt-1">
           <div class="col-12 text-center">
             <div class="icheck-primary">
               <a href="forgot-password.php">Forgot Password</a>
             </div>
           </div>
         </div> -->
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script>
  // Set flag "isLoggedIn" agar tab tersebut dianggap valid
  sessionStorage.setItem("isLoggedIn", "true");
  
  // Add subtle animation to form fields
  $(document).ready(function() {
    $('.form-control').focus(function() {
      $(this).parent().addClass('focused');
    }).blur(function() {
      $(this).parent().removeClass('focused');
    });
  });
</script>
</body>
</html>