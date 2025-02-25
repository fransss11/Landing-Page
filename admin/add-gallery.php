<?php
// error_reporting(0);
include 'conn.php';
include 'auth.php';

$a=10;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php include"title.php"; ?>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
 <!-- Navbar -->
   <?php include"topbar.php"; ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include"sidebar.php"; ?>

<?php



if(isset($_POST['submit'])){ 
    // Include the database configuration file 
    include_once 'dbConfig.php'; 
     
    // File upload configuration 
    $targetDir = "uploads/"; 
    $allowTypes = array('jpg','png','jpeg','gif'); 
     
    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = ''; 
    $fileNames = array_filter($_FILES['files']['name']); 
    if(!empty($fileNames)){ 
      foreach($_FILES['files']['name'] as $key=>$val){ 
          // File upload path 
          $fileName = basename($_FILES['files']['name'][$key]); 
          $targetFilePath = $targetDir . $fileName; 
           
          // Check whether file type is valid 
          $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
          if(in_array($fileType, $allowTypes)){ 
              // Upload file to server 
              if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){ 
                  // Image db insert sql 
                  // Ambil nilai caption dari formulir
                  $caption = $_POST['caption'];
                  $insertValuesSQL .= "('$fileName', '$caption', NOW()),"; 
              }else{ 
                  $errorUpload .= $_FILES['files']['name'][$key].' | '; 
              } 
          }else{ 
              $errorUploadType .= $_FILES['files']['name'][$key].' | '; 
          } 
      } 
  
         
        if(!empty($insertValuesSQL)){ 
            $insertValuesSQL = trim($insertValuesSQL, ','); 
            // Insert image file name into database 
            $insert = $db->query("INSERT INTO images (file_name, caption, uploaded_on) VALUES $insertValuesSQL"); 
            if($insert){ 
                $errorUpload = !empty($errorUpload)?'Upload Error: '.trim($errorUpload, ' | '):''; 
                $errorUploadType = !empty($errorUploadType)?'File Type Error: '.trim($errorUploadType, ' | '):''; 
                $errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType; 
                $statusMsg = "Files are uploaded successfully.".$errorMsg; 
            }else{ 
                $statusMsg = "Sorry, there was an error uploading your file."; 
            } 
        } 
    }else{ 
        $statusMsg = 'Please select a file to upload.'; 
    } 
     
    // Display status message 
    echo $statusMsg; 
} 
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Gallery</h1>
          </div>
           <div class="col-sm-6">
          <a href="view-gallery.php" class="btn btn-success"><i class="fa fa-mars" aria-hidden="true"></i>  View Gallery</a>
          </div>

        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-8">
        <form action="" method="post" enctype="multipart/form-data">
    <div class="card card-outline card-info">
    <div class="card-header">
        <div class="form-group">
            <label>Input Caption</label>
              <input name="caption" type="text" class="form-control" placeholder="Enter ...">
              </div>
        </div>
        <div class="card-header">
            <div class="form-group">
                <label>Select Images<span style="color:red;">(only compressed)</span></label>
                <p style="color:red;">Image size should be 500px x 500px</p>
                <input type="file" name="files[]" multiple accept="image/*">
            </div>
        </div>
        <div class="card-header">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- text input -->
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-block btn-warning btn-lg">Upload</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
  
        </div>
        <!-- /.col-->
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
   <?php include"footer.php"; ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script>
  $(function () {
    // Summernote
    $('.textarea').summernote()
  })
</script>
</body>
</html>
