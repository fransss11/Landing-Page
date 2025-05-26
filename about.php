<?php
include 'database.php';
// Fetch data from the 'about' table
$sql = "SELECT * FROM about ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$about = $result->fetch_assoc();
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
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="libr/animate/animate.min.css" rel="stylesheet">
    <link href="libr/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <?php include 'includes/logo.php'; ?>
    <style>
        /* Prevent horizontal scrolling on mobile */
        html, body {
            overflow-x: hidden;
            width: 100%;
            position: relative;
            margin: 0;
            padding: 0;
        }
        
        .text-white {
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .row {
            justify-content: space-around;
            width: 100%;
            margin-left: 0;
            margin-right: 0;
        }
        /* --- Letters Box --- */
        .letters-row {
            display: flex; justify-content: center; align-items: center; gap: .5rem; margin-bottom: 2rem;
            flex-wrap: wrap;
            max-width: 100%;
        }
        .letters-row .letter {
            width: 60px; height: 60px;
            display: flex; justify-content: center; align-items: center;
            font-size: 1.5rem; font-weight: 700; color: #fff;
            border-radius: 4px;
        }
        .letters-row.vision .letter { background: #8e44ad; }
        .letters-row.vision .letter.first { background: #c0392b; }
        .letters-row.mission .letter { background: #8e44ad; }
        .letters-row.mission .letter.first { background: #c0392b; }
        .letters-row.mission .letter.icon {
            background: #8e44ad;
            display: flex; justify-content: center; align-items: center;
        }

        /* --- Card Styles --- */
        .vm-card {
            position: relative;
            background: #e0f7fa;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            margin-bottom: 3rem;
        }
        .vm-card .icon-circle {
            position: absolute;
            top: -20px; left: 20px;
            width: 40px; height: 40px;
            background: #29b6f6;
            color: #fff;
            border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            font-size: 1.2rem;
        }
        .vm-card h4 {
            font-size: 2rem;
            color: #436b9b;
            margin-bottom: 1rem;
        }

        /* --- Mission List --- */
        .mission-list {
            counter-reset: step;
            list-style: none;
            padding-left: 0;
        }
        .mission-list li {
            position: relative;
            padding-left: 3rem;
            margin-bottom: 1rem;
        }
        .mission-list li::before {
            counter-increment: step;
            content: counter(step);
            position: absolute;
            left: 0; top: 0;
            width: 2rem; height: 2rem;
            border: 2px solid #29b6f6;
            border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            font-weight: 600;
            color: #29b6f6;
        }

        /* --- History Section --- */
        .history-card {
            position: relative;
            background: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            padding: 2rem 1.5rem;
            margin-bottom: 3rem;
        }
        .history-card .icon-circle {
            position: absolute;
            top: -25px; left: 50%;
            transform: translateX(-50%);
            width: 50px; height: 50px;
            background: #436b9b;
            color: #fff;
            border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            font-size: 1.5rem;
        }
        .history-card .quote-marks {
            position: absolute;
            opacity: 0.25;
            font-size: 4rem;
            color: #436b9b;
        }
        .history-card .quote-marks.left {
            top: 20px; left: 20px;
        }
        .history-card .quote-marks.right {
            bottom: 20px; right: 20px;
        }
        .history-card h4 {
            font-size: 2rem;
            color: #436b9b;
            margin-bottom: 1rem;
        }
        .history-timeline {
            text-align: center;
            margin-top: 2rem;
        }
        .history-timeline .established-badge {
            width: fit-content;
            background: #436b9b;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Responsive adjustments for mobile */
        @media (max-width: 768px) {
            .letters-row .letter {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }
            
            .vm-card, .history-card {
                max-width: 100%;
                box-sizing: border-box;
            }
            
            .container, .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
                max-width: 100%;
                overflow-x: hidden;
            }
        }
        /* Add this to your existing <style> section */
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .bounce-animation {
            animation-name: bounce;
            animation-duration: 1.5s;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
            transform: translateY(0); /* Reset any existing transform */
        }

        /* Add some hover effect for extra interactivity */
        .letter:hover {
            animation-play-state: paused;
            cursor: pointer;
            filter: brightness(1.2);
            transition: filter 0.3s ease;
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
        $pageTitle = "Tentang Kami";
        include 'includes/header.php'; 
        ?>
        <!-- Header End -->
        <!-- About Start -->
        <div class="container-fluid about bg-light py-5">
            <div class="container py-5" style="background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85)); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="row g-5 align-items-center">
                    <?php if (!empty($about['img']) && file_exists("admin/images/about/" . $about['img'])): ?>
                        <div class="col-lg-5" data-aos="fade-right" data-aos-delay="300">
                            <div class="about-img position-relative">
                                <div class="bg-primary position-absolute" style="width: 100%; height: 100%; top: 10px; left: 10px; border-radius: 15px; z-index: 1;"></div>
                                <img src="admin/images/about/<?php echo htmlspecialchars($about['img']); ?>" class="img-fluid rounded w-100 position-relative" style="object-fit: cover; border-radius: 15px; z-index: 2; box-shadow: 0 5px 15px rgba(0,0,0,0.1);" alt="Image">
                            </div>
                        </div>
                        <div class="col-lg-7" data-aos="fade-left" data-aos-delay="400">
                            <div class="section-title text-start mb-5">
                                <h4 class="display-3 mb-4" style="font-weight: 700; color: #333;"><?php echo $about['title']; ?></h4>
                                <div class="divider mb-4" style="width: 70px; height: 3px; background: #436b9b;"></div>
                                <p class="mb-4 lead" style="line-height: 1.8;"><?php echo $about['descrip']; ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-lg-12 text-center" data-aos="fade-up" data-aos-delay="400">
                            <div class="section-title mb-5">
                                <h4 class="display-3 mb-4" style="font-weight: 700; color: #333;"><?php echo $about['title']; ?></h4>
                                <div class="divider mx-auto mb-4" style="width: 70px; height: 3px; background: #436b9b;"></div>
                                <p class="mb-4 lead" style="line-height: 1.8;"><?php echo $about['descrip']; ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Vision & Mission Section -->
                <div class="row mt-5">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                        <?php if (!empty($about['visi'])): ?>
                            <div class="letters-row vision" style="transform: translateY(10px);">
                                <div class="letter first bounce-animation" style="box-shadow: 0 5px 15px rgba(192, 57, 43, 0.3); animation-delay: 0s;">V</div>
                                <div class="letter bounce-animation" style="box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3); animation-delay: 0.1s;">I</div>
                                <div class="letter bounce-animation" style="box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3); animation-delay: 0.2s;">S</div>
                                <div class="letter bounce-animation" style="box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3); animation-delay: 0.3s;">I</div>
                            </div>
                            <div class="vm-card" style="background: linear-gradient(135deg, #e0f7fa, #f5f5f5); border-left: 5px solid #c0392b; transform: translateZ(0); transition: all 0.3s ease;">
                                <div class="icon-circle" style="background: linear-gradient(135deg, #c0392b, #e74c3c);">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <h4 class="mb-3" style="font-weight: 600;">Visi Kami</h4>
                                <div class="text-dark text-justify" style="line-height: 1.8; font-size: 1.05rem;">
                                    <?php echo nl2br(($about['visi'])); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="600">
                        <?php if (!empty($about['misi'])): ?>
                            <div class="letters-row mission" style="transform: translateY(10px);">
                                <div class="letter first bounce-animation" style="box-shadow: 0 5px 15px rgba(192, 57, 43, 0.3); transform: translateY(-5px);">M</div>
                                <div class="letter bounce-animation" style="box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3); transform: translateY(2px);">I</div>
                                <div class="letter bounce-animation" style="box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3); transform: translateY(-3px);">S</div>
                                <div class="letter bounce-animation" style="box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3); transform: translateY(0px);">I</div>
                            </div>
                            <div class="vm-card" style="background: linear-gradient(135deg, #e0f7fa, #f5f5f5); border-left: 5px solid #8e44ad; transform: translateZ(0); transition: all 0.3s ease;">
                                <div class="icon-circle" style="background: linear-gradient(135deg, #8e44ad, #9b59b6);">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <h4 class="mb-3" style="font-weight: 600;">Misi Kami</h4>
                                <div class="text-dark text-justify" style="line-height: 1.8; font-size: 1.05rem;">
                                    <?php 
                                    // Display mission text with line breaks but no numbering
                                    $missionText = $about['misi'];
                                    if (strpos($missionText, "\n") !== false) {
                                        $missionPoints = explode("\n", $missionText);
                                        foreach ($missionPoints as $point) {
                                            if (trim($point) != '') {
                                                echo '<p class="mb-2">' . trim($point) . '</p>';
                                            }
                                        }
                                    } else {
                                        echo $missionText;
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sejarah Section -->
                <div class="row g-5 align-items-center mt-5">
                    <div class="col-lg-12 text-center" data-aos="fade-up" data-aos-delay="400">
                        <div class="section-title mb-5 position-relative">
                            <h4 class="display-4 mb-4 text-center" style="font-weight: 700; color: #333;">
                                <?php echo $about['history_title']; ?>
                            </h4>
                            <div class="divider mx-auto mb-4" style="width: 70px; height: 3px; background: linear-gradient(to right, #436b9b, #8e44ad);"></div>
                        </div>
                    </div>
                    
                    <div class="col-lg-10 mx-auto" data-aos="fade-up" data-aos-delay="600">
                        <div class="history-card p-5 position-relative" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 15px; box-shadow: 0 15px 30px rgba(0,0,0,0.1); border-top: 5px solid #436b9b;">
                            <div class="icon-circle position-absolute" style="top: -25px; left: 50%; transform: translateX(-50%); width: 50px; height: 50px; background: linear-gradient(135deg, #436b9b, #3498db); color: #fff; border-radius: 50%; display: flex; justify-content: center; align-items: center; box-shadow: 0 5px 15px rgba(0,0,0,0.2); font-size: 1.5rem;">
                                <i class="fas fa-history"></i>
                            </div>
                            
                            <div class="quote-marks position-absolute opacity-25" style="top: 20px; left: 20px; font-size: 4rem; color: #436b9b;">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <div class="quote-marks position-absolute opacity-25" style="bottom: 20px; right: 20px; font-size: 4rem; color: #436b9b;">
                                <i class="fas fa-quote-right"></i>
                            </div>
                            
                            <div class="text-dark text-justify px-md-4" style="line-height: 1.9; font-size: 1.05rem; position: relative; z-index: 2;">
                                <?php 
                                // Preserve HTML formatting from database
                                $historyText = $about['history'];
                                $paragraphs = explode("\n\n", $historyText);
                                
                                if (count($paragraphs) > 0 && !empty($paragraphs[0])) {
                                    // For the first paragraph, we need to handle HTML tags carefully
                                    $firstPara = $paragraphs[0];
                                    
                                    // Check if it starts with a tag
                                    if (substr(trim($firstPara), 0, 1) === '<') {
                                        // If it starts with HTML, output as is
                                        echo '<p>' . $firstPara . '</p>';
                                    } else {
                                        // If it's plain text, we can still do the fancy first letter
                                        $firstChar = mb_substr($firstPara, 0, 1);
                                        $restOfPara = mb_substr($firstPara, 1);
                                        echo '<p><span style="float: left; font-size: 3.5rem; line-height: 1; font-weight: 700; padding: 0.1em 0.1em 0 0; color: #436b9b;">' . $firstChar . '</span>' . $restOfPara . '</p>';
                                    }
                                    
                                    // Output remaining paragraphs with HTML preserved
                                    for ($i = 1; $i < count($paragraphs); $i++) {
                                        if (!empty(trim($paragraphs[$i]))) {
                                            echo '<p>' . trim($paragraphs[$i]) . '</p>';
                                        }
                                    }
                                } else {
                                    echo nl2br($historyText);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->
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
        AOS.init();
    </script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script>
    // Hilangkan spinner setelah halaman sepenuhnya dimuat
    window.addEventListener("load", function() {
        var spinner = document.getElementById("spinner");
        if (spinner) {
            spinner.classList.remove("show"); // Menghilangkan spinner
        }
    });
    </script>
</body>
</html>