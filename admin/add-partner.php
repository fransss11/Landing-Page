<?php
// Prevent multiple session_start calls
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'conn.php';
include 'auth.php';

$a = 7;

date_default_timezone_set('Asia/Kolkata');
$today = date("D d M Y");

// Check if 'edit' parameter is set in the URL
$edit = isset($_GET['edit']) ? mysqli_real_escape_string($con, $_GET['edit']) : null;

if ($edit) {
    $resultt = mysqli_query($con, "SELECT * FROM partnership WHERE id = $edit");
    if ($resultt) {
        $roww = mysqli_fetch_array($resultt);
    } else {
        // Handle error: no such entry exists
        echo "Partnership not found.";
    }
} else {
    // Default values for a new entry
    $roww = ['nama' => '', 'logo' => ''];
}

if (isset($_POST['publise'])) {
    $nama = $_POST['nama'];

    if ($_FILES['gambar']['name'] != '') {
        $gambar = rand() . $_FILES['gambar']['name'];
    } else {
        $gambar = $roww["logo"];
    }

    $tempname = $_FILES['gambar']['tmp_name'];
    $folder = "images/partnership/" . $gambar;
    $valid_ext = array('png', 'jpeg', 'jpg');
    $file_extension = strtolower(pathinfo($folder, PATHINFO_EXTENSION));

    // Validate the image extension
    if (in_array($file_extension, $valid_ext)) {
        compressImage($tempname, $folder, 60);
    }

    if ($edit == '') {
        $insertdata = mysqli_query($con, "INSERT INTO partnership(nama,logo) VALUES ('$nama','$gambar')");
        echo "<script>alert('Posted Successfully');</script>
            <script>window.location.href = 'add-partner.php'</script>";
    } else {
        $insertdata = mysqli_query($con, "UPDATE partnership SET nama='$nama', logo='$gambar' WHERE id=$edit");
        echo "<script>alert('Updated Successfully');</script>
            <script>window.location.href = 'add-partner.php'</script>";
    }
}

// Compress image function
function compressImage($source, $destination, $quality)
{
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    }

    imagejpeg($image, $destination, $quality);
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
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include "topbar.php"; ?>
        <?php include "sidebar.php"; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add Partnership</h1>
                        </div>
                        <div class="col-sm-6">
                            <a href="view-partner.php" class="btn btn-success"><i class="fa fa-eye" aria-hidden="true"></i> View Partnership</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-md-8">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <div class="form-group">
                                        <label>Input Partnership Name</label>
                                        <input name="nama" value="<?php echo $roww['nama']; ?>" type="text" class="form-control" placeholder="Enter name...">
                                    </div>
                                </div>

                                <div class="card-header">
                                    <div class="form-group">
                                        <label for="exampleInputFile">Select Partnership Logo<span style="color:red;"> (only compressed)</span></label>
                                        <p style="color:red;">Logo size 800px x 800px</p>
                                        <input name="gambar" type="file">
                                        <?php echo $roww['logo']; ?>
                                    </div>
                                </div>

                                <div class="card-header">
                                    <div class="form-group">
                                        <button type="submit" name="publise" class="btn btn-primary btn-lg">Publish Post</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <?php include "footer.php"; ?>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="dist/js/demo.js"></script>
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(function() {
            $('.textarea').summernote()
        })
    </script>
</body>

</html>
