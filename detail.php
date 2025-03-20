<?php
include 'database.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// Fetch data from the 'media' table
$sql = "SELECT galery, uploaded_on FROM media WHERE id = $id AND status = '1'";
$result = $conn->query($sql);
$imageDetail = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Detail Gambar</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* General Styling */
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        margin: 0;
        padding: 0;
    }
    /* Container Styling */
    .container {
        max-width: 800px;
        margin: auto;
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    /* Heading */
    h3 {
        font-size: 28px;
        font-weight: bold;
        color: #007bff;
    }
    /* Image Styling */
    #image-container img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }
    /* Description */
    p {
        font-size: 18px;
        line-height: 1.6;
        margin-top: 10px;
    }
    /* Back Button */
    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        text-decoration: none;
        transition: 0.3s;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    /* Animasi Fade In */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    /* Terapkan animasi ke elemen */
    #image-container img, h5, p {
        animation: fadeIn 1s ease-in-out;
    }
    /* Tambahkan efek hover ke gambar */
    #image-container img:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease-in-out;
    }
    /* #image-container img {
    animation-delay: 0.3s;
    } */
    h5 {
        animation-delay: 0.5s;
    }
    p {
        animation-delay: 0.7s;
    }
    </style>
</head>
<body>
    <div class="container text-center py-5">
        <h3 class="mb-4">Detail Gambar</h3>
        <div id="image-container">
            <?php if ($imageDetail): ?>
                <img src="admin/uploads/?php echo $imageDetail['galery']; ?>" class="img-fluid mb-3" alt="<?php echo $imageDetail['id']; ?>">
                <h5 class="mt-3"><?php echo $imageDetail['id']; ?></h5>
                <p><?php echo $imageDetail['deskrip']; ?></p>
                <h6 class="mt-2"><?php echo $imageDetail['uploaded_on']; ?></h6>
            <?php else: ?>
                <p>Gambar tidak ditemukan.</p>
            <?php endif; ?>
        </div>
        <a href="galery.php" class="btn btn-primary mt-3">Kembali ke Galeri</a>
    </div>
</body>
</html>