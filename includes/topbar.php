<?php
include 'database.php';

// Fetch data from the 'info' table
$sql = "SELECT lokasi, gmail FROM info ORDER BY id_info DESC LIMIT 1";
$result = $conn->query($sql);
$info = $result->fetch_assoc();

// Fetch data from the 'social_table'
$sql = "SELECT * FROM social ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$social = $result->fetch_assoc();
?>

<div class="container-fluid bg-dark px-5 d-none d-lg-block">
    <div class="row gx-0 align-items-center" style="height: 45px;">
        <div class="col-lg-8 text-center text-lg-start mb-lg-0">
            <div class="d-flex flex-wrap">
                <?php if (!empty($info['lokasi'])): ?>
                    <a href="<?php echo $info['lokasi']; ?>" class="text-light me-4 hover-effect">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>Lokasi
                    </a>
                <?php endif; ?>
                <?php if (!empty($social['phone'])): ?>
                    <a class="text-light me-4 hover-effect">
                        <i class="fas fa-phone-alt text-primary me-2"></i><?php echo $social['phone']; ?>
                    </a>
                <?php endif; ?>
                <?php if (!empty($info['gmail'])): ?>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo urlencode($info['gmail']); ?>" 
                    target="_blank" 
                    class="text-light me-0 hover-effect">
                        <i class="fas fa-envelope text-primary me-2"></i>Email
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4 text-center text-lg-end">
            <div class="d-flex align-items-center justify-content-end">
                <?php if (!empty($social['facebook'])): ?>
                    <a href="<?php echo $social['facebook']; ?>" class="btn btn-light btn-square border rounded-circle nav-fill me-3 hover-effect"><i class="fab fa-facebook-f"></i></a>
                <?php endif; ?>
                <?php if (!empty($social['twitter'])): ?>
                    <a href="<?php echo $social['twitter']; ?>" class="btn btn-light btn-square border rounded-circle nav-fill me-3 hover-effect"><i class="fab fa-twitter"></i></a>
                <?php endif; ?>
                <?php if (!empty($social['instagram'])): ?>
                    <a href="<?php echo $social['instagram']; ?>" class="btn btn-light btn-square border rounded-circle nav-fill me-3 hover-effect"><i class="fab fa-instagram"></i></a>
                <?php endif; ?>
                <?php if (!empty($social['linkedin'])): ?>
                    <a href="<?php echo $social['linkedin']; ?>" class="btn btn-light btn-square border rounded-circle nav-fill me-3 hover-effect"><i class="fab fa-linkedin-in"></i></a>
                <?php endif; ?>
                <?php if (!empty($social['whatsapp'])): ?>
                    <a href="https://wa.me/<?php echo $social['whatsapp']; ?>" class="btn btn-light btn-square border rounded-circle nav-fill me-0 hover-effect"><i class="fab fa-whatsapp"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* Adding hover effect for the anchor tag and icon */
    .hover-effect {
        transition: all 0.3s ease-in-out;
    }

    /* Hover effect for the anchor tag */
    .hover-effect:hover {
        transform: scale(1.3); /* Slightly increase the size */
    }
</style>

<?php
$conn->close();
?>
