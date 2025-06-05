<?php
include 'database.php';

// Fetch data from the 'teams' table with category field
$sql = "SELECT title, designation, descrip, img, facebook, twitter, instagram, linkedin, whatsapp, url, category FROM teams";
$result = $conn->query($sql);
$teamList = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teamList[] = $row;
    }
}
// Define the specific order for categories
$orderedCategories = ['Internal', 'Auditor', 'Assesor Associate', 'Psikolog'];
$activeCategories = [];
foreach ($teamList as $member) {
    if (!empty($member['category']) && !in_array($member['category'], $activeCategories)) {
        $activeCategories[] = $member['category'];
    }
}
$activeCategory = isset($_GET['category']) ? $_GET['category'] : null;
// Filter by category if one is selected, otherwise use all
if ($activeCategory) {
    $filteredTeam = array_filter($teamList, function($m) use ($activeCategory) {
        return $m['category'] === $activeCategory;
    });
} else {
    $filteredTeam = $teamList;
}

// Sort $filteredTeam by designation: Direktur → Manajer → Others
usort($filteredTeam, function($a, $b) {
    // Priority map
    $priority = [
        'direktur' => 1,
        'manajer'  => 2,
    ];
    // Determine priority for $a
    $pa = 3;
    foreach ($priority as $key => $val) {
        if (stripos($a['designation'], $key) !== false) {
            $pa = $val;
            break;
        }
    }
    // Determine priority for $b
    $pb = 3;
    foreach ($priority as $key => $val) {
        if (stripos($b['designation'], $key) !== false) {
            $pb = $val;
            break;
        }
    }
    // If same priority, sort alphabetically by id
    if ($pa === $pb) {
        return strcmp($a['id'], $b['id']);
    }
    return $pa - $pb;
});

// Pagination settings
$itemsPerPage = 8;
$currentPage  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$totalItems   = count($filteredTeam);
$totalPages   = (int)ceil($totalItems / $itemsPerPage);
$offset       = ($currentPage - 1) * $itemsPerPage;

// Slice the sorted array for the current page
$limitedTeamList = array_slice($filteredTeam, $offset, $itemsPerPage);

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
        .btn.btn-primary {
            box-shadow: inset 1px 0px 5px 8px rgb(0, 255, 162);
        }
        .text-white {
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .sub-title{
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .team .team-item .team-content {
            height: auto;
        }
            .cursor-pointer {
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .cursor-pointer:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Make sure social links work independently of the card click */
        .team-icon a {
            position: relative;
            z-index: 2;
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
        $pageTitle = "Tim Kami";
        include 'includes/header.php';
        ?>
        <!-- Header End -->
        <!-- Team Start -->
        <!-- Category Filter Buttons -->
        <div class="container-fluid team py-5">
            <div class="container py-5">
                <div class="section-title mb-5" data-aos="flip-left" data-aos-delay="100">
                    <div class="sub-style">
                        <h1 class="sub-title px-3 mb-0">Tim Kami</h1>
                    </div>
                </div>
                <!-- Tampilkan tim dengan paging: 8 per halaman -->
                <div class="row mb-4 justify-content-center">
                    <div class="col-12 text-center">
                        <!-- <a href="team.php" class="btn <?php echo empty($activeCategory) ? 'btn-primary' : 'btn-outline-primary'; ?> m-2" style="border-radius: 30px; padding: 8px 20px;">
                            Semua
                        </a> -->
                        
                        <?php 
                        // Only display the ordered categories that actually have members
                        foreach ($orderedCategories as $category): 
                            // Skip this category if no team members belong to it
                            if (!in_array($category, $activeCategories)) {
                                continue;
                            }
                            
                            // Define styling based on category name
                            $btnClass = 'btn-outline-primary';
                            $icon = '<i class="fas fa-users mr-2"></i>';
                            
                            switch(strtolower($category)) {
                                case 'internal':
                                    $icon = '<i class="fas fa-users mr-2"></i>';
                                    break;
                                case 'auditor':
                                    $icon = '<i class="fas fa-clipboard-check mr-2"></i>';
                                    break;
                                case 'assesor associate':
                                    $icon = '<i class="fas fa-user-tie mr-2"></i>';
                                    break;
                                case 'psikolog':
                                    $icon = '<i class="fas fa-brain mr-2"></i>';
                                    break;
                                default:
                                    $btnClass = 'btn-outline-primary';
                                    $icon = '<i class="fas fa-tag mr-2"></i>';
                            }
                            
                            // If this category is active, use solid button style
                            if ($activeCategory == $category) {
                                if ($btnClass == 'btn-outline-primary') {
                                    $btnClass = 'btn-primary';
                                } else if ($btnClass == 'btn-outline-success') {
                                    $btnClass = 'btn-success';
                                }
                            }
                        ?>
                            
                            <a href="?category=<?php echo urlencode($category); ?>" 
                            class="btn <?php echo $btnClass; ?> m-2" style="border-radius: 30px; padding: 8px 20px; color: black; color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset; background-color: #00ff78b5;">
                                <?php echo $icon . ' ' . htmlspecialchars($category); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="row g-4 justify-content-center align-items-stretch">
                    <?php foreach ($limitedTeamList as $index => $team): ?>
                    <div class="col-md-6 col-lg-6 col-xl-3"
                        data-aos="<?php echo $index % 2 == 0 ? 'fade-up' : 'fade-down'; ?>"
                        data-aos-delay="<?php echo $index * 300; ?>">
                        <?php if (!empty($team['url'])): ?>
                        <a href="<?php echo htmlspecialchars($team['url']); ?>" class="text-decoration-none" target="_blank">
                        <?php endif; ?>
                        <div class="team-item rounded h-100 d-flex flex-column <?php echo !empty($team['url']) ? 'cursor-pointer' : ''; ?>">
                            <div class="team-img rounded-top" style="background-color:rgba(255, 255, 255, 0.93);">
                                <img src="admin/images/team/<?php echo htmlspecialchars($team['img']); ?>"
                                    class="img-fluid team-image"
                                    alt="<?php echo htmlspecialchars($team['title']); ?>"
                                    style="width: 100%; height: 250px; object-fit: contain;">
                                <div class="team-icon d-flex justify-content-center">
                                    <?php if (!empty($team['facebook'])): ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="<?php echo $team['facebook']; ?>" onclick="event.stopPropagation();"><i class="fab fa-facebook-f"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($team['twitter'])): ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="<?php echo $team['twitter']; ?>" onclick="event.stopPropagation();"><i class="fab fa-twitter"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($team['instagram'])): ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="<?php echo $team['instagram']; ?>" onclick="event.stopPropagation();"><i class="fab fa-instagram"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($team['linkedin'])): ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="<?php echo $team['linkedin']; ?>" onclick="event.stopPropagation();"><i class="fab fa-linkedin-in"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($team['whatsapp'])): ?>
                                        <a class="btn btn-square btn-primary text-white rounded-circle mx-1" href="https://wa.me/<?php echo $team['whatsapp']; ?>" onclick="event.stopPropagation();"><i class="fab fa-whatsapp"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="team-content text-center border border-primary border-top-0 rounded-bottom p-4 d-flex flex-column justify-content-between flex-grow-1">
                                <h5 class="team-title"><?php echo htmlspecialchars($team['title']); ?></h5>
                                <p class="team-designation mb-0" style="background: #ffffffbf;"><?php echo htmlspecialchars($team['designation']); ?></p>
                                <p class="team-description mb-0" style="font-style: italic; background: #ffffffbf;"><?php echo htmlspecialchars($team['descrip']); ?></p>
                            </div>
                        </div>
                        <?php if (!empty($team['url'])): ?>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Pagination -->
                <div class="pagination text-center mt-4">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?php echo $currentPage - 1; ?><?php echo !empty($activeCategory) ? '&category=' . urlencode($activeCategory) : ''; ?>" class="btn btn-outline-primary mx-1">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span style="color: #000000;box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset;" class="btn btn-primary mx-1"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?><?php echo !empty($activeCategory) ? '&category=' . urlencode($activeCategory) : ''; ?>" class="btn btn-outline-primary mx-1"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?><?php echo !empty($activeCategory) ? '&category=' . urlencode($activeCategory) : ''; ?>" class="btn btn-outline-primary mx-1">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Team End -->
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
    <!-- Inisialisasi WOW.js -->
    <script>
        new WOW().init();
    </script>
</body>
</html>