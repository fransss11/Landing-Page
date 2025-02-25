<?php
include 'database.php';

// Fetch data from the 'social_table'
$sql = "SELECT facebook, twitter, instagram, whatsapp, linkedin FROM social ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$social = $result->fetch_assoc();

// Fetch data from the 'info' table
$sql = "SELECT lokasi, gmail FROM info ORDER BY id_info DESC LIMIT 1";
$result = $conn->query($sql);
$info = $result->fetch_assoc();
?>

<div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="text-white mb-4">Lisa Mitra Mandiri</h4>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-share fa-2x text-white me-2"></i>
                        <?php if (!empty($social['facebook'])): ?>
                            <a class="btn-square btn btn-primary text-white rounded-circle mx-1" href="<?php echo $social['facebook']; ?>"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($social['twitter'])): ?>
                            <a class="btn-square btn btn-primary text-white rounded-circle mx-1" href="<?php echo $social['twitter']; ?>"><i class="fab fa-twitter"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($social['instagram'])): ?>
                            <a class="btn-square btn btn-primary text-white rounded-circle mx-1" href="<?php echo $social['instagram']; ?>"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($social['linkedin'])): ?>
                            <a class="btn-square btn btn-primary text-white rounded-circle mx-1" href="<?php echo $social['linkedin']; ?>"><i class="fab fa-linkedin-in"></i></a>
                            <?php endif; ?>
                        <?php if (!empty($social['whatsapp'])): ?>
                            <a class="btn-square btn btn-primary text-white rounded-circle mx-1" href="https://wa.me/<?php echo $social['whatsapp']; ?>"><i class="fab fa-whatsapp"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-4 text-white">Link Cepat</h4>
                    <a href="index.php"><i class="fas fa-angle-right me-2"></i> Beranda</a>
                    <a href="about.php"><i class="fas fa-angle-right me-2"></i> Tentang Kami</a>
                    <a href="service.php"><i class="fas fa-angle-right me-2"></i> Layanan</a>
                    <a href="portofolio.php"><i class="fas fa-angle-right me-2"></i> Portofolio</a>
                    <a href="galery.php"><i class="fas fa-angle-right me-2"></i> Galeri</a>
                    <a href="contact.php"><i class="fas fa-angle-right me-2"></i> Kontak</a>
                    <a href="berita.php"><i class="fas fa-angle-right me-2"></i> Berita</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-4 text-white">Informasi</h4>
                    <a href="klien.php"><i class="fas fa-angle-right me-2"></i> Klien Kami</a>
                    <a href="team.php"><i class="fas fa-angle-right me-2"></i> Tim Kami</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-4 text-white">Info Kontak</h4>
                    <?php if (!empty($info['lokasi'])): ?>
                        <a href="<?php echo $info['lokasi']; ?>"><i class="fa fa-map-marker-alt me-2"></i>Lokasi</a>
                    <?php endif; ?>
                    <?php if (!empty($info['gmail'])): ?>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo urlencode($info['gmail']); ?>" 
                    target="_blank">
                    <i class="fas fa-envelope me-2"></i>Email</a>
                    <?php endif; ?>
                    <a href=""><i class="fas fa-phone me-2"></i>031 843 7854</a>
                    <!-- <a href="admin/index.php"><i class="fas fa-user-shield me-2"></i>admin</a> -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
?>