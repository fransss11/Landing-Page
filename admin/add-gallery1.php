<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';

$a=7;
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
date_default_timezone_set('Asia/Kolkata');
$today = date("D d M Y");
$edit = $_GET['edit'];

$stmt = $con->prepare("SELECT * FROM media where id=?");
$stmt->bind_param("i", $edit);
$stmt->execute();
$resultt = $stmt->get_result();

$stmt->close();

$roww = mysqli_fetch_array($resultt);

if(isset($_POST['publise'])){
	
$nama = $_POST['nama'];

if($_FILES['gambar']['name']!=''){
$gambar = rand().$_FILES['gambar']['name'];
}
else{
	$gambar = $roww["logo"];
}

$tempname = $_FILES['gambar']['tmp_name'];
$folder = "images/partnership/".$gambar;
$valid_ext = array('png','jpeg','jpg');
// file extension
$file_extension = pathinfo($folder, PATHINFO_EXTENSION);
$file_extension = strtolower($file_extension);
// Check extension
if(in_array($file_extension,$valid_ext)){
// Compress Image
compressImage($tempname,$folder,60);
}
if($edit==''){
$insertdata = mysqli_query($con,"INSERT INTO partnership(nama,logo)VALUES('$nama','$gambar')");
echo "<script>alert('Posted Successfully');</script>
	<script>window.location.href = 'add-partner.php'</script>";
}
else{
$insertdata = mysqli_query($con,"UPDATE partnership SET nama='$nama',logo='$gambar' where id=".$edit."");
echo "<script>alert('Updated Successfully');</script>
	<script>window.location.href = 'add-partner.php'</script>";
}
}

// Compress image
function compressImage($source, $destination, $quality) {

  $info = getimagesize($source);

  if ($info['mime'] == 'image/jpeg') 
    $image = imagecreatefromjpeg($source);

  elseif ($info['mime'] == 'image/gif') 
    $image = imagecreatefromgif($source);

  elseif ($info['mime'] == 'image/png') 
    $image = imagecreatefrompng($source);

  imagejpeg($image, $destination, $quality);

}

?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            
                            <h1>Add Partnership</h1>
                        </div>
                        <div class="col-sm-6">
          <a href="view-partner.php" class="btn btn-success"><i class="fa fa-eye" aria-hidden="true"></i>  View Partnership</a>
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
                                        
                                        <label>Input Partnership Name</label>
                                        <input name="nama" value="<?php echo $roww["nama"]; ?>" type="text"
                                            class="form-control" placeholder="Enter ...">
                                    </div>
                                </div>
                                

                                <div class="card-header">
                                    <div class="form-group">
                                        <label for="exampleInputFile">Select Partnership Logo<span style="color:red;"> (only
                                                compresed)</span></label>
                                        <p style="color:red;">Logo size 800px x 800px</p>
                                        <input name="gambar" type="file">
                                        <?php echo $roww["logo"]; ?>
                                    </div>

                                </div>

                                <div class="card-header">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <button type="submit" name="publise"
                                                        class="btn btn-primary btn-lg">Publish Post</button>
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
    $(function() {
        // Summernote
        $('.textarea').summernote()
    })
    </script>
</body>

</html>