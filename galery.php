<?php
include 'database.php';
// Fetch data from the 'media' table
$sql = "SELECT id, galery, foto, kategori, uploaded_on FROM media WHERE status = '1'";
$result = $conn->query($sql);
$images = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $images[$row['kategori']][] = $row;
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
    <!-- Lightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
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
        .text-white {
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .sub-title{
            background-color: #ffffffba !important;
            border-radius: 8px;
        }
        .kategori-header:hover {
            color:rgb(255, 255, 255);
            cursor: pointer;
            background-color: #000000 !important;
        }
        .client-card {
            max-width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .instructions {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 30px;
            border-left: 5px solid #007bff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .kategori-header {
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            border-radius: 25px;
            padding: 12px 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            border: none;
            font-weight: 600;
            width: 100%;
            display: block;
            text-align: center;
            font-size: 1.2rem;
        }
        
        .kategori-header:hover {
            color: #ffffff;
            background: linear-gradient(145deg, #333333, #000000) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        
        .kategori-header.active {
            color: #ffffff;
            background: linear-gradient(145deg, #333333, #000000) !important;
        }
        .pagination {
            margin-top: 30px;
            padding-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5px;
        }
        
        .pagination .btn {
            margin: 0 2px;
            padding: 6px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .pagination .btn:hover {
            transform: translateY(-2px);
        }
        
        .pagination .btn.active {
            background-color: #0d6efd !important;
            color: white !important;
        }
        
        .gallery-container {
            margin-top: 40px;
        }
        
        /* Improved image container styling */
        .image-container {
            position: relative;
            overflow: hidden;
            border-radius: 8px 8px 0 0;
            height: 180px;
        }
        
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        
        .client-card:hover .image-container img {
            transform: scale(1.05);
        }
        
        .image-container::after {
            content: "\f00e";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .client-card:hover .image-container::after {
            opacity: 1;
        }
        
        .client-card {
            transition: all 0.3s ease;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .client-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            transform: translateY(-5px);
        }
        
        .card-body {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .card-body h6 {
            font-size: 1rem;
            margin-bottom: 8px;
            line-height: 1.4;
            font-weight: 600;
        }
        
        /* Enhanced responsive design */
        @media (max-width: 1199px) {
            .image-container {
                height: 160px;
            }
        }
        
        @media (max-width: 991px) {
            .gallery-item {
                margin-bottom: 20px;
            }
            .image-container {
                height: 170px;
            }
            .kategori-header {
                font-size: 1.1rem;
                padding: 10px 18px;
            }
            .instructions {
                padding: 12px;
            }
        }
        
        @media (max-width: 767px) {
            .image-container {
                height: 200px;
            }
            .kategori-header {
                font-size: 1rem;
                padding: 10px 15px;
            }
            .pagination .btn {
                padding: 5px 10px;
                font-size: 0.9rem;
            }
            .instructions .col-auto {
                padding-right: 10px;
            }
            .instructions p {
                font-size: 14px !important;
            }
        }
        
        @media (max-width: 575px) {
            .container-fluid.about {
                padding-left: 10px;
                padding-right: 10px;
            }
            .image-container {
                height: 180px;
            }
            .kategori-header {
                font-size: 0.9rem;
                padding: 8px 12px;
            }
            .pagination {
                margin-top: 20px;
            }
            .pagination .btn {
                padding: 4px 8px;
                font-size: 0.8rem;
                margin-bottom: 5px;
            }
            .sub-title {
                font-size: 1.5rem;
                padding: 10px !important;
            }
            .card-body h6 {
                font-size: 0.9rem;
            }
            .card-body small {
                font-size: 0.8rem;
            }
            #lihat-semua {
                padding: 8px 15px;
                font-size: 0.9rem;
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
        $pageTitle = "Galeri";
        include 'includes/header.php';
        ?>
        <!-- Header End -->
        <!-- Gallery Start -->
        <div class="container-fluid about team py-5">
            <div class="container py-5">
                <div class="section-title mb-5">
                    <div class="sub-style">
                        <h1 class="sub-title px-9 mb-0">Galeri Kami</h1>
                    </div>
                </div>
                <!-- Petunjuk penggunaan galeri -->
                <div class="instructions" data-aos="fade-up" data-aos-delay="300">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-info-circle fa-2x text-primary"></i>
                        </div>
                        <div class="col">
                            <p class="mb-0" style="font-size: 16px;">
                                Klik pada <strong>nama kegiatan</strong> untuk membuka atau menutup gambar berdasarkan kegiatan, atau klik tombol <strong>"Lihat Semua Gambar"</strong> untuk membuka/menutup semua gambar sekaligus.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Tombol Lihat Semua Gambar (posisi diperbaiki) -->
                <div class="text-center mb-4" data-aos="fade-up" style="margin-top: 2%;">
                    <button class="btn btn-primary" id="lihat-semua" style="color: #000000; box-shadow: rgb(0 0 0) 0px 0px 10px 1px inset;">
                        <i class="fas fa-images me-2"></i>Lihat Semua Gambar
                    </button>
                </div>
                <?php foreach ($images as $kategori => $kategori_images): ?>
                    <div class="row" data-aos="fade-up" data-aos-delay="300">
                        <div class="col-12">
                            <h3 class="kategori-header" data-kategori="<?= htmlspecialchars($kategori); ?>">
                                <i class="fas fa-camera-retro me-2"></i><?= htmlspecialchars($kategori); ?>
                            </h3>
                        </div>
                    </div>
                    <div class="row kategori-content" id="kategori-<?= htmlspecialchars($kategori); ?>" style="display: none;">
                        <?php foreach ($kategori_images as $index => $image): ?>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4 gallery-item">
                                <div class="client-card h-100">
                                    <div class="image-container">
                                        <a href="admin/uploads/<?= $image['foto']; ?>" data-lightbox="gallery-<?= htmlspecialchars($kategori); ?>" data-title="<?= htmlspecialchars($image['galery']); ?>">
                                            <img src="admin/uploads/<?= $image['foto']; ?>" class="img-fluid" alt="<?= htmlspecialchars($image['galery']); ?>">
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="mt-2"><?= htmlspecialchars($image['galery']); ?></h6>
                                        <small style="color: #000000 !important;" class="text-muted">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            <?= date('d M Y', strtotime($image['uploaded_on'])); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Gallery End -->
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
    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
        
        // Lightbox configuration
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'showImageNumberLabel': false,
            'disableScrolling': false,
            'fadeDuration': 300,
            'imageFadeDuration': 300,
            'fitImagesInViewport': true,
            'maxWidth': Math.min(window.innerWidth * 0.9, 1200),
            'maxHeight': Math.min(window.innerHeight * 0.9, 900)
        });
        
        // Update lightbox size on window resize
        window.addEventListener('resize', function() {
            lightbox.option({
                'maxWidth': Math.min(window.innerWidth * 0.9, 1200),
                'maxHeight': Math.min(window.innerHeight * 0.9, 900)
            });
        });
    </script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <!-- Script JS untuk Toggle Kategori dan Tombol Lihat Semua -->
    <script>
        document.querySelectorAll('.kategori-header').forEach(function(header) {
            header.addEventListener('click', function() {
                const kategori = this.getAttribute('data-kategori');
                const konten = document.getElementById('kategori-' + kategori);
                const items = konten.querySelectorAll('.gallery-item');
                const itemsPerPage = 8;
                let currentPage = 1;

                function renderPage(page) {
                    items.forEach((item, index) => {
                        item.style.display = (index >= (page - 1) * itemsPerPage && index < page * itemsPerPage) ? 'block' : 'none';
                    });
                }

                if (konten.style.display === 'none' || konten.style.display === '') {
                    // Collapse all other open categories
                    document.querySelectorAll('.kategori-content').forEach(function(content) {
                        if (content !== konten && content.style.display !== 'none') {
                            content.style.display = 'none';
                            const otherHeader = document.querySelector(`[data-kategori="${content.id.replace('kategori-', '')}"]`);
                            if (otherHeader) otherHeader.classList.remove('active');
                        }
                    });
                    
                    // Open this category
                    konten.style.display = 'flex';
                    konten.style.flexWrap = 'wrap';
                    renderPage(currentPage);
                    
                    // Add active class to header
                    this.classList.add('active');

                    // Create pagination if needed
                    let pagination = konten.querySelector('.pagination');
                    if (!pagination) {
                        pagination = document.createElement('div');
                        pagination.className = 'pagination';
                        pagination.style.width = '100%';
                        pagination.style.display = 'flex';
                        pagination.style.justifyContent = 'center';
                        konten.appendChild(pagination);

                        const totalPages = Math.ceil(items.length / itemsPerPage);
                        
                        // Only show pagination if we have more than one page
                        if (totalPages > 1) {
                            // Previous button
                            if (totalPages > 2) {
                                const prevButton = document.createElement('button');
                                prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
                                prevButton.className = 'btn';
                                prevButton.disabled = true;
                                prevButton.addEventListener('click', function() {
                                    if (currentPage > 1) {
                                        currentPage--;
                                        renderPage(currentPage);
                                        updatePagination();
                                    }
                                });
                                pagination.appendChild(prevButton);
                            }
                            
                            // Page buttons - limit to 5 buttons on mobile
                            const maxVisibleButtons = window.innerWidth < 576 ? 3 : (window.innerWidth < 768 ? 5 : totalPages);
                            const showEllipsis = totalPages > maxVisibleButtons;
                            
                            for (let i = 1; i <= totalPages; i++) {
                                // Skip some buttons if we need ellipsis
                                if (showEllipsis && i > 1 && i < totalPages && 
                                   (i < currentPage - Math.floor((maxVisibleButtons-3)/2) || 
                                    i > currentPage + Math.floor((maxVisibleButtons-3)/2))) {
                                    // If we're at the position right after the first button or right before the last
                                    // and we haven't added an ellipsis yet
                                    if ((i === 2 && !pagination.querySelector('.ellipsis-start')) || 
                                        (i === totalPages - 1 && !pagination.querySelector('.ellipsis-end'))) {
                                        const ellipsis = document.createElement('span');
                                        ellipsis.textContent = '...';
                                        ellipsis.className = i === 2 ? 'ellipsis-start' : 'ellipsis-end';
                                        ellipsis.style.margin = '0 5px';
                                        ellipsis.style.alignSelf = 'center';
                                        pagination.appendChild(ellipsis);
                                    }
                                    continue;
                                }
                                
                                const pageButton = document.createElement('button');
                                pageButton.textContent = i;
                                pageButton.className = 'btn';
                                pageButton.dataset.page = i;
                                
                                if (i === currentPage) {
                                    pageButton.classList.add('active');
                                }
                                
                                pageButton.addEventListener('click', function() {
                                    currentPage = i;
                                    renderPage(currentPage);
                                    updatePagination();
                                });
                                pagination.appendChild(pageButton);
                            }
                            
                            // Next button
                            if (totalPages > 2) {
                                const nextButton = document.createElement('button');
                                nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
                                nextButton.className = 'btn';
                                nextButton.addEventListener('click', function() {
                                    if (currentPage < totalPages) {
                                        currentPage++;
                                        renderPage(currentPage);
                                        updatePagination();
                                    }
                                });
                                pagination.appendChild(nextButton);
                            }
                            
                            // Function to update pagination state
                            function updatePagination() {
                                // Remove any existing ellipsis
                                const existingEllipsis = pagination.querySelectorAll('.ellipsis-start, .ellipsis-end');
                                existingEllipsis.forEach(el => el.remove());
                                
                                // Update page buttons visibility based on current page
                                if (showEllipsis) {
                                    pagination.querySelectorAll('.btn[data-page]').forEach(btn => {
                                        const page = parseInt(btn.dataset.page);
                                        const isFirstOrLast = page === 1 || page === totalPages;
                                        const isNearCurrent = Math.abs(page - currentPage) <= Math.floor((maxVisibleButtons-3)/2);
                                        
                                        btn.style.display = isFirstOrLast || isNearCurrent ? '' : 'none';
                                    });
                                    
                                    // Re-add ellipsis if needed
                                    if (currentPage > Math.floor((maxVisibleButtons-3)/2) + 1) {
                                        const ellipsisStart = document.createElement('span');
                                        ellipsisStart.textContent = '...';
                                        ellipsisStart.className = 'ellipsis-start';
                                        ellipsisStart.style.margin = '0 5px';
                                        ellipsisStart.style.alignSelf = 'center';
                                        
                                        // Insert after the first button
                                        pagination.insertBefore(ellipsisStart, pagination.querySelector('.btn[data-page="1"]').nextSibling);
                                    }
                                    
                                    if (currentPage < totalPages - Math.floor((maxVisibleButtons-3)/2)) {
                                        const ellipsisEnd = document.createElement('span');
                                        ellipsisEnd.textContent = '...';
                                        ellipsisEnd.className = 'ellipsis-end';
                                        ellipsisEnd.style.margin = '0 5px';
                                        ellipsisEnd.style.alignSelf = 'center';
                                        
                                        // Insert before the last button
                                        const lastButton = pagination.querySelector(`.btn[data-page="${totalPages}"]`);
                                        pagination.insertBefore(ellipsisEnd, lastButton);
                                    }
                                }
                                
                                // Update active state
                                pagination.querySelectorAll('.btn[data-page]').forEach(btn => {
                                    if (parseInt(btn.dataset.page) === currentPage) {
                                        btn.classList.add('active');
                                    } else {
                                        btn.classList.remove('active');
                                    }
                                });
                                
                                // Update prev/next buttons if they exist
                                if (totalPages > 2) {
                                    const prevButton = pagination.querySelector('.btn:first-child');
                                    const nextButton = pagination.querySelector('.btn:last-child');
                                    
                                    prevButton.disabled = currentPage === 1;
                                    nextButton.disabled = currentPage === totalPages;
                                }
                            }
                        }
                    }
                } else {
                    konten.style.display = 'none';
                    this.classList.remove('active');
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const openCategory = document.querySelector('.kategori-content[style*="display: flex"]');
            if (openCategory) {
                const header = document.querySelector(`[data-kategori="${openCategory.id.replace('kategori-', '')}"]`);
                if (header) {
                    // Trigger a click to refresh the pagination
                    header.click();
                    header.click();
                }
            }
        });
        
        let semuaTerbuka = false;
        document.getElementById('lihat-semua').addEventListener('click', function() {
            semuaTerbuka = !semuaTerbuka;
            
            document.querySelectorAll('.kategori-content').forEach(function(konten) {
                konten.style.display = semuaTerbuka ? 'flex' : 'none';
                konten.style.flexWrap = 'wrap';
                
                // Show all items when "View All" is clicked
                if (semuaTerbuka) {
                    konten.querySelectorAll('.gallery-item').forEach(item => {
                        item.style.display = 'block';
                    });
                }
                
                // Update header active states
                const kategori = konten.id.replace('kategori-', '');
                const header = document.querySelector(`[data-kategori="${kategori}"]`);
                if (header) {
                    semuaTerbuka ? header.classList.add('active') : header.classList.remove('active');
                }
                
                // Hide pagination when showing all
                const pagination = konten.querySelector('.pagination');
                if (pagination) {
                    pagination.style.display = semuaTerbuka ? 'none' : 'flex';
                }
            });
            
            // Update button text and icon
            this.innerHTML = semuaTerbuka ? 
                '<i class="fas fa-compress-alt me-2"></i>Tutup Semua Gambar' : 
                '<i class="fas fa-images me-2"></i>Lihat Semua Gambar';
        });
    </script>
</body>
</html>