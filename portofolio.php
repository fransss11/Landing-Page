<?php
include 'database.php';
// Fetch data from the 'proposal' table
$sql = "SELECT pdf, name FROM proposal";
$result = $conn->query($sql);
$portfolios = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $portfolios[] = $row;
    }
}
$conn->close();
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="libr/animate/animate.min.css" rel="stylesheet">
    <link href="libr/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <style>
        .portfolio-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(to bottom, rgba(255,255,255,0.6), transparent);
            z-index: 1;
        }
        
        .section-title h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: 1px;
            position: relative;
            display: inline-block;
        }
        
        .section-title h1::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #6b11cb, #2575fc);
            border-radius: 10px;
        }
        
        .section-title p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
            color: #666;
        }
        
        .portfolio-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .portfolio-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .portfolio-header {
            background: linear-gradient(135deg, #6b11cb, #2575fc);
            padding: 25px;
            position: relative;
            overflow: hidden;
        }
        
        .portfolio-header::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 60%);
            opacity: 0.5;
        }
        
        .pdf-title {
            margin: 0;
            font-size: 1.5rem;
            color: white;
            text-transform: uppercase;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            position: relative;
        }
        
        .portfolio-content {
            padding: 15px;
        }
        
        .pdf-frame {
            width: 100%;
            height: 550px;
            border: none;
            border-radius: 0 0 10px 10px;
        }
        
        .portfolio-actions {
            display: flex;
            justify-content: center;
            padding: 20px;
            background: #f9f9f9;
            border-top: 1px solid #eaeaea;
        }
        
        .action-btn {
            background: linear-gradient(135deg, #6b11cb, #2575fc);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 500;
            margin: 0 10px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(107, 17, 203, 0.3);
        }
        
        .action-btn i {
            margin-right: 8px;
        }
        
        @media (max-width: 768px) {
            .section-title h1 {
                font-size: 2.5rem;
            }
            
            .pdf-frame {
                height: 400px;
            }
            
            .portfolio-actions {
                flex-direction: column;
            }
            
            .action-btn {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <div class="bckg">
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
        $pageTitle = "Portofolio";
        include 'includes/header.php'; 
        ?>
        <!-- Header End -->
        <!-- Portofolio Start -->
        <div class="container-fluid about bg-light py-5">
            <div class="container">
                <div class="section-title mb-5 text-center" data-aos="fade-up">
                    <h1 class="display-4">Proposal Kami</h1>
                </div>
                <div class="row">
                    <?php foreach ($portfolios as $index => $portfolio): ?>
                        <?php if (!empty($portfolio['pdf'])): ?>
                            <?php $file = $portfolio['pdf']; ?>
                            <div class="col-12" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                                <div class="portfolio-item">
                                    <div class="portfolio-header">
                                        <h3 class="pdf-title">
                                            <i class="far fa-file-pdf me-2"></i>
                                            <?php echo $portfolio['name']; ?>
                                        </h3>
                                    </div>
                                    <div class="portfolio-content">
                                        <iframe src="pdf/<?php echo $file; ?>" class="pdf-frame" allowfullscreen></iframe>
                                    </div>
                                    <div class="portfolio-actions">
                                        <a href="pdf/<?php echo $file; ?>" class="action-btn" target="_blank">
                                            <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                                        </a>
                                        <a href="pdf/<?php echo $file; ?>" class="action-btn" download>
                                            <i class="fas fa-download"></i> Unduh PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- Portofolio End -->
        
        <!-- Footer Start -->
        <?php include 'includes/footer.php'; ?>
        <!-- Footer End -->
        <!-- Copyright Start -->
        <?php include 'includes/copyright.php'; ?>
        <!-- Copyright End -->
        <!-- Back to Top -->
        <?php include 'includes/back_to_top.php'; ?>
        <!-- Back to Top End -->
    </div>
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="libr/wow/wow.min.js"></script>
    <script src="libr/easing/easing.min.js"></script>
    <script src="libr/waypoints/waypoints.min.js"></script>
    <script src="libr/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    </script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>