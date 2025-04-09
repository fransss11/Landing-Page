<?php
include 'database.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $designation = $_POST['designation'];
    $descrip = $_POST['descrip'];
    $img = $_FILES['img']['name'];
    $target_dir = "admin/images/testimonial/";
    $target_file = $target_dir . basename($img);
    // Upload file
    if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
        // Insert data into database
        $sql = "INSERT INTO testimonials (title, designation, descrip, img, date, status) 
                VALUES ('$title', '$designation', '$descrip', '$img', NOW(), '1')";
        if ($conn->query($sql) === TRUE) {
            // Alihkan ke service.php pada bagian testimonial (misalnya dengan anchor #testimoni)
            header("Location: service.php#testimoni");
            exit();
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $error = "Sorry, there was an error uploading your file.";
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
        <h1 class="text-center mb-4">Berikan Testimoni Anda</h1>
        <!-- Jika ada error, tampilkan alert Bootstrap -->
        <?php if (isset($error) && !empty($error)) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form action="testimoni.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="title" class="form-label">Nama</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter your name" required>
                <div class="invalid-feedback">
                    Please enter your name.
                </div>
            </div>
            <div class="mb-3">
                <label for="designation" class="form-label">Jabatan</label>
                <input type="text" class="form-control" id="designation" name="designation" placeholder="Enter your designation" required>
                <div class="invalid-feedback">
                    Please enter your designation.
                </div>
            </div>
            <div class="mb-3">
                <label for="descrip" class="form-label">Testimoni</label>
                <textarea class="form-control" id="descrip" name="descrip" rows="4" placeholder="Enter your testimonial" required></textarea>
                <div class="invalid-feedback">
                    Please enter your testimonial.
                </div>
            </div>
            <div class="mb-3">
                <label for="img" class="form-label">Foto</label>
                <input type="file" class="form-control" id="img" name="img" accept="image/*" required>
                <div class="invalid-feedback">
                    Please upload a valid image file (max 500KB).
                </div>
                <div id="file-error" class="text-danger mt-2" style="display: none;"></div>
                <div class="mt-3" id="image-preview" style="display: none;">
                    <p>Preview:</p>
                    <img id="preview-img" src="#" alt="Image Preview" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px;">
                </div>
            </div>

            <script>
            document.getElementById('img').addEventListener('change', function(event) {
                const file = event.target.files[0];
                const errorBox = document.getElementById('file-error');
                const previewContainer = document.getElementById('image-preview');
                const previewImage = document.getElementById('preview-img');

                errorBox.style.display = 'none';
                errorBox.textContent = '';
                previewContainer.style.display = 'none';
                previewImage.src = '';

                if (file) {
                    const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!validImageTypes.includes(file.type)) {
                        errorBox.textContent = 'Only image files (JPEG, PNG, GIF) are allowed.';
                        errorBox.style.display = 'block';
                        event.target.value = '';
                    } else if (file.size > 500 * 1024) {
                        errorBox.textContent = 'File size must not exceed 500KB.';
                        errorBox.style.display = 'block';
                        event.target.value = '';
                    } else {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewContainer.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
            </script>
            <button type="submit" class="btn btn-primary">Kirim Testimoni</button>
            <a href="service.php#testimoni" class="btn btn-danger">Kembali</a>
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
    </script>
</body>
</html>