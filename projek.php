<?php
include 'database.php';

// Number of projects to display per page
$projectsPerPage = 10;

// Get the current page from the URL, default to page 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startFrom = ($page - 1) * $projectsPerPage; // Calculate the starting point

// Fetch data from the 'projek' table
$sql = "SELECT * FROM projek ORDER BY tahun DESC LIMIT $startFrom, $projectsPerPage"; // Limit results per page
$result = $conn->query($sql);

$projectsByYear = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Group projects by year
        $projectsByYear[$row['tahun']][] = $row;
    }
}

// Get the total number of projects to calculate the total pages
$totalProjectsResult = $conn->query("SELECT COUNT(*) AS total FROM projek");
$totalProjects = $totalProjectsResult->fetch_assoc()['total'];
$totalPages = ceil($totalProjects / $projectsPerPage); // Calculate total number of pages

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
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- jQuery (for toggling) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body >

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
    $pageTitle = "Projek Kami";
    include 'includes/header.php'; 
    ?>
    <!-- Header End -->

    <div class="container">
        <div class="row">
            <!-- Sidebar with Year Navigation -->
            <div class="col-md-3">
                <div class="sidebar">
                    <h3>Proyek Kami</h3>
                    <ul class="year-list">
                        <?php foreach ($projectsByYear as $year => $projects): ?>
                            <li>
                                <a href="?year=<?php echo $year; ?>" 
                                   class="<?php echo (isset($_GET['year']) && $_GET['year'] == $year) ? 'active' : ''; ?>">
                                    <?php echo $year; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-md-9">
                <!-- Message for selected year -->
                <h2 id="selected-year-message">
                    <?php
                    // Display the selected year message
                    if (isset($_GET['year'])) {
                        $selectedYear = $_GET['year'];
                        echo "Proyek Kami di Tahun " . $selectedYear;
                    } else {
                        echo "Proyek Kami di Tahun ...";
                    }
                    ?>
                </h2>

                <?php
                // If a year is selected from the URL, show the projects for that year
                if (isset($_GET['year']) && isset($projectsByYear[$_GET['year']])) {
                    $year = $_GET['year'];
                    $projects = $projectsByYear[$year];
                ?>

                    <!-- Table to Display Projects -->
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $index => $project): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo $project['judul']; ?></td>
                                    <td><?php echo $project['deskrip']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a href="?year=<?php echo $year; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>

                <?php
                } else {
                    echo "<p>Please select a year to view projects.</p>";
                }
                ?>
            </div>
        </div>
    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    
    <!-- Inisialisasi WOW.js -->
    <script>
        new WOW().init();
    </script>
</body>

</html>
