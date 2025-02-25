<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $designation = $_POST['designation'];
    $descrip = $_POST['descrip'];
    $img = $_FILES['img']['name'];
    $target_dir = "img/";
    $target_file = $target_dir . basename($img);

    // Upload file
    if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
        // Insert data into database
        $sql = "INSERT INTO testimonials (title, designation, descrip, img, date, status) VALUES ('$title', '$designation', '$descrip', '$img', NOW(), '1')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Testimoni berhasil dikirim.');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Lisa Mitra Mandiri</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Spinner Start -->
    <?php include 'includes/spinner.php'; ?>
    <!-- Spinner End -->

    <!-- Topbar Start -->
    <?php include 'includes/topbar.php'; ?>
    <!-- Topbar End -->

    <!-- Navbar & Hero Start -->
    <?php include 'includes/navbar.php'; ?>
    <!-- Navbar End -->

    <!-- Header Start -->
    <?php
    $pageTitle = "Berikan Testimoni";
    include 'includes/header.php';
    ?>
    <!-- Header End -->

    <!-- Form Testimoni Start -->
    <div class="container py-5">
        <h2 class="text-center mb-4">Berikan Testimoni Anda</h2>
        <form action="testimoni.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Nama</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="designation" class="form-label">Jabatan</label>
                <input type="text" class="form-control" id="designation" name="designation" required>
            </div>
            <div class="mb-3">
                <label for="descrip" class="form-label">Testimoni</label>
                <textarea class="form-control" id="descrip" name="descrip" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="img" class="form-label">Foto</label>
                <input type="file" class="form-control" id="img" name="img" required>
            </div>
            <button type="submit" class="btn btn-primary">Kirim Testimoni</button>
        </form>
    </div>
    <!-- Form Testimoni End -->

    <!-- Footer Start -->
    <?php include 'includes/footer.php'; ?>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <?php include 'includes/copyright.php'; ?>
    <!-- Copyright End -->

    <!-- Back to Top -->
    <?php include 'includes/back_to_top.php'; ?>
    <!-- Back to Top End -->

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

</body>

</html>