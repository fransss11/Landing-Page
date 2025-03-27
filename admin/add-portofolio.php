<?php
error_reporting(0);
include 'conn.php';
include 'auth.php';
date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d H:i:s");

// Check if a record already exists in the 'proposal' table
$query  = "SELECT * FROM proposal LIMIT 1";
$result = mysqli_query($con, $query);
$row    = mysqli_fetch_assoc($result);
$dataExists = ($row) ? true : false;

// If form is submitted
if (isset($_POST['save'])) {
    // Use existing pdf filename if available
    $proposal_pdf = isset($row['pdf']) ? $row['pdf'] : '';
    
    // If a new PDF file is uploaded, process it
    if (!empty($_FILES['proposal_pdf']['name'])) {
        $newFileName = rand() . '_' . $_FILES['proposal_pdf']['name'];
        $tempFile    = $_FILES['proposal_pdf']['tmp_name'];
        $folder      = "../pdf/" . $newFileName;
        // Allow only pdf extension
        $valid_ext = ['pdf'];
        $file_ext  = strtolower(pathinfo($newFileName, PATHINFO_EXTENSION));
        if (in_array($file_ext, $valid_ext)) {
            move_uploaded_file($tempFile, $folder);
            $proposal_pdf = $newFileName;
        }
    }
    
    // If no record exists, insert new record; otherwise update the record
    if (!$dataExists) {
        $sql = "INSERT INTO proposal (pdf, date) 
                VALUES ('$proposal_pdf', '$today')";
        $exec = mysqli_query($con, $sql);
        if ($exec) {
            $_SESSION['msg'] = "PDF berhasil ditambahkan.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat menambahkan PDF.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    } else {
        $sql = "UPDATE proposal SET 
                    pdf  = '$proposal_pdf',
                    date = '$today'
                WHERE id_pro = '".$row['id_pro']."'";
        $exec = mysqli_query($con, $sql);
        if ($exec) {
            $_SESSION['msg'] = "PDF berhasil diperbarui.";
            $_SESSION['msgClass'] = "alert-success";
        } else {
            $_SESSION['msg'] = "Terjadi kesalahan saat memperbarui PDF.";
            $_SESSION['msgClass'] = "alert-danger";
        }
    }
    // Redirect to avoid resubmission
    header("Location: add-portofolio.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "title.php"; ?>
    <!-- Bootstrap & AdminLTE CSS -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Portofolio (PDF)</title>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include "topbar.php"; ?>
    <?php include "sidebar.php"; ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Edit Portofolio (PDF)</h1>
        </section>
        <section class="content">
            <div class="container">
                <!-- Display session alerts if set -->
                <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])): ?>
                    <div class="alert <?php echo $_SESSION['msgClass']; ?> alert-dismissible fade show" role="alert">
                        <?php 
                            echo $_SESSION['msg'];
                            unset($_SESSION['msg']); 
                            unset($_SESSION['msgClass']);
                        ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Display existing PDF if available -->
                <?php if ($dataExists && !empty($row['pdf'])): ?>
                    <?php $pdfPath = "../pdf/" . $row['pdf']; ?>
                    <div class="existing-pdf mb-4">
                        <iframe src="<?php echo $pdfPath; ?>" 
                                style="width:100%; height:600px;" data-aos="fade-up" data-aos-delay="500" frameborder="0"></iframe>
                    </div>
                <?php endif; ?>


                <!-- Form to upload/update PDF -->
                <form action="" method="post" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate style="margin:0;">
                    <!-- PDF Upload -->
                    <div class="col-md-12">
                        <label for="validationPDF" class="form-label">Upload Portofolio</label><br>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="validationPDF" name="proposal_pdf" accept=".pdf">
                            <label class="custom-file-label" for="validationPDF">Pilih Portofolio</label>
                            <div class="invalid-feedback">
                                Mohon unggah file Portofolio.
                            </div>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div style="padding-top: 3%;" class="col-12">
                        <button type="submit" name="save" class="btn btn-primary">Perbarui</button>
                        <a href="add-portofolio.php" class="btn btn-danger">Kembali</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <?php include "footer.php"; ?>
</div>
<!-- jQuery, Bootstrap, AdminLTE JS -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script>
document.getElementById("validationPDF").addEventListener("change", function () {
    var fileName = this.files[0] ? this.files[0].name : "Tidak ada file yang dipilih";
    this.nextElementSibling.innerText = fileName;
});
</script>
</body>
</html>