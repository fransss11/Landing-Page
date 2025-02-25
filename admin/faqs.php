<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';

$a = 10;
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

    <?php
    if (isset($_GET['delete_id'])) {
        $delete_id = mysqli_real_escape_string($con, $_GET['delete_id']);
        $query_delete = "DELETE FROM faqs WHERE id = '$delete_id'";
        if (mysqli_query($con, $query_delete)) {
            echo "<script>alert('Deleted Successfully');</script>
                  <script>window.location.href = 'faqs.php'</script>";
        } else {
            echo "<script>alert('Error while deleting the FAQ.');</script>";
        }
    }

    // Edit FAQ
    $edit = isset($_GET['edit']) ? mysqli_real_escape_string($con, $_GET['edit']) : '';
    $roww = [];
    if ($edit != '') {
        $resultt = mysqli_query($con, "SELECT * FROM faqs WHERE id = '$edit'");
        $roww = mysqli_fetch_array($resultt);
    }

    if (isset($_POST['add'])) {
        // Sanitize inputs
        $name = mysqli_real_escape_string($con, $_POST['title']);
        $desc = mysqli_real_escape_string($con, $_POST['descc']);

        if ($edit == '') {
            // Insert new FAQ
            $insertdata = mysqli_query($con, "INSERT INTO faqs (title, descc, status) VALUES ('$name', '$desc', '0')");
            if ($insertdata) {
                echo "<script>alert('Added Successfully');</script>
                      <script>window.location.href = 'faqs.php'</script>";
            } else {
                echo "<script>alert('Error while adding the FAQ.');</script>";
            }
        } else {
            // Update existing FAQ
            $insertdata = mysqli_query($con, "UPDATE faqs SET title = '$name', descc = '$desc' WHERE id = '$edit'");
            if ($insertdata) {
                echo "<script>alert('Updated Successfully');</script>
                      <script>window.location.href = 'faqs.php'</script>";
            } else {
                echo "<script>alert('Error while updating the FAQ.');</script>";
            }
        }
    }
    ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add New FAQ</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-5">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <div class="form-group">
                                    <label>Enter Title</label>
                                    <input type="text" name="title" value="<?php echo isset($roww["title"]) ? $roww["title"] : ''; ?>" class="form-control" placeholder="Enter title...">
                                </div>
                            </div>

                            <div class="card-header">
                                <div class="form-group">
                                    <label>Enter Description</label>
                                    <textarea name="descc" class="form-control" placeholder="Enter description..."><?php echo isset($roww["descc"]) ? $roww["descc"] : ''; ?></textarea>
                                </div>
                            </div>

                            <button type="submit" name="add" class="btn btn-block btn-primary btn-lg">Add</button>
                        </div>
                    </form>
                </div>

                <div class="col-md-7">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <div class="form-group">
                                    <label>All FAQs</label>
                                </div>
                            </div>

                            <div class="card-header">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $location = mysqli_query($con, "SELECT * FROM faqs ORDER BY id DESC");
                                        while ($location_ft = mysqli_fetch_array($location)) { ?>
                                            <tr>
                                                <td><?php echo $location_ft["title"]; ?></td>
                                                <td class="text-right py-0 align-middle">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="faqs.php?edit=<?php echo $location_ft["id"]; ?>" class="btn btn-info"><i class="fas fa-edit"></i></a>
                                                        <a href="faqs.php?delete_id=<?php echo $location_ft["id"]; ?>" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <?php include "footer.php"; ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark"></aside>

</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script>
    $(function () {
        $('.textarea').summernote();
    });
</script>
</body>
</html>
