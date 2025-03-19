<?php
include 'database.php';

// Set zona waktu ke Asia/Kolkata dan dapatkan tanggal-waktu saat ini
date_default_timezone_set('Asia/Jakarta');
$today = date("Y-m-d H:i:s");

function getVisitorIP() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_array = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ip_array[0]); 
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP']; 
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];  
    }
    if ($ip == '::1') {
        $ip = '127.0.0.1'; 
    }
    return $ip;
}

function getBrowser() {
    $browser = "Unknown Browser";
    $browser_array = array(
        '/edg/i'        => 'Microsoft Edge', 
        '/msie/i'       => 'Internet Explorer',
        '/trident/i'    => 'Internet Explorer',
        '/firefox/i'    => 'Mozilla Firefox',
        '/chrome/i'     => 'Google Chrome',
        '/safari/i'     => 'Apple Safari',
        '/opera/i'      => 'Opera',
        '/netscape/i'   => 'Netscape',
        '/maxthon/i'    => 'Maxthon',
        '/konqueror/i'  => 'Konqueror',
        '/mobile/i'     => 'Mobile Browser',
        '/ucbrowser/i'  => 'UC Browser',
        '/vivaldi/i'    => 'Vivaldi',
        '/yabrowser/i'  => 'Yandex Browser',
        '/puffin/i'     => 'Puffin',
        '/brave/i'      => 'Brave',
        '/duckduckgo/i' => 'DuckDuckGo Privacy Browser',
        '/seamonkey/i'  => 'SeaMonkey',
        '/slimjet/i'    => 'Slimjet',
        '/comodo/i'     => 'Comodo Dragon',
        '/waterfox/i'   => 'Waterfox',
        '/palemoon/i'   => 'Pale Moon',
        '/lunascape/i'  => 'Lunascape',
        '/avant/i'      => 'Avant Browser',
        '/epic/i'       => 'Epic Privacy Browser',
        '/midori/i'     => 'Midori',
        '/torch/i'      => 'Torch Browser',
        '/sleipnir/i'   => 'Sleipnir',
        '/iridium/i'    => 'Iridium Browser',
        '/falkon/i'     => 'Falkon',
        '/otter/i'      => 'Otter Browser'
    );
    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) {
            $browser = $value;
            break;
        }
    }
    return $browser;
}

function getDevice() {
    $device = "Unknown Device";
    if (preg_match('/mobile/i', $_SERVER['HTTP_USER_AGENT'])) {
        $device = "Mobile";
    } elseif (preg_match('/tablet/i', $_SERVER['HTTP_USER_AGENT'])) {
        $device = "Tablet";
    } elseif (preg_match('/desktop|windows|macintosh|linux/i', $_SERVER['HTTP_USER_AGENT'])) {
        $device = "Desktop";
    }
    return $device;
}

$visitor_ip = getVisitorIP();
$browser = getBrowser();
$device = getDevice();

// Periksa apakah visitor sudah ada untuk hari ini berdasarkan IP, user agent, browser, dan device (bandingkan hanya tanggalnya)
$query_check = "SELECT * FROM visitor 
                WHERE DATE(visit_date) = CURDATE() 
                  AND ip_address = '$visitor_ip' 
                  AND user_agent = '{$_SERVER['HTTP_USER_AGENT']}' 
                  AND browser = '$browser' 
                  AND device = '$device'";
$result_check = mysqli_query($conn, $query_check);

if (mysqli_num_rows($result_check) == 0) {
    // Jika belum ada, insert record baru dengan current timestamp
    $query_insert = "INSERT INTO visitor (visit_date, ip_address, user_agent, browser, device) 
                     VALUES ('$today', '$visitor_ip', '{$_SERVER['HTTP_USER_AGENT']}', '$browser', '$device')";
    mysqli_query($conn, $query_insert);
} else {
    // Jika sudah ada, update record dengan current timestamp
    $row = mysqli_fetch_assoc($result_check);
    $id = $row['id_visitor'];
    $query_update = "UPDATE visitor SET visit_date = '$today' WHERE id_visitor = '$id'";
    mysqli_query($conn, $query_update);
}

// Ambil jumlah pengunjung hari ini (menggunakan DATE() agar hanya dihitung berdasarkan tanggal)
$query_today = "SELECT COUNT(*) AS today_visitors FROM visitor WHERE DATE(visit_date) = CURDATE()";
$result_today = mysqli_query($conn, $query_today);
$row_today = mysqli_fetch_assoc($result_today);
$today_visitors = $row_today['today_visitors'];

// Ambil jumlah total pengunjung
$query_total = "SELECT COUNT(*) AS total_visitors FROM visitor";
$result_total = mysqli_query($conn, $query_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_visitors = $row_total['total_visitors'];

// Fetch data from the 'social_table'
$sql = "SELECT * FROM social ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
$social = $result->fetch_assoc();

// Fetch data from the 'info' table
$sql = "SELECT * FROM info ORDER BY id_info DESC LIMIT 1";
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
                    <a href="berita.php"><i class="fas fa-angle-right me-2"></i> Berita</a>
                    <a href="contact.php"><i class="fas fa-angle-right me-2"></i> Kontak</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-4 text-white">Informasi</h4>
                    <a href="klien.php"><i class="fas fa-angle-right me-2"></i> Klien Kami</a>
                    <a href="team.php"><i class="fas fa-angle-right me-2"></i> Tim Kami</a>
                    <a href="projek.php"><i class="fas fa-angle-right me-2"></i> Projek Kami</a>
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
                    <?php if (!empty($social['phone'])): ?>
                        <a><i class="fas fa-phone me-2"></i><?php echo $social['phone']; ?></a>
                    <?php endif; ?>
                    <!-- <a href="admin/index.php"><i class="fas fa-user-shield me-2"></i>admin</a> -->
                </div>
                <br></br>
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-4 text-white">Jam Kerja</h4>
                    <p style="color: white;">Kami bekerja 5 hari dalam seminggu. </p>
                    <p style="color: white;">Senin - Jumat: 08:00 - 17:00</p>
                    <table style="width: auto" class="table text-center text-white" >
                        <thead>
                            <tr>
                                <th>Pengunjung Hari Ini</th>
                                <th>Total Pengunjung</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="color: rgb(42, 42, 42);"><?php echo $today_visitors; ?> Orang</td>
                                <td style="color: rgb(42, 42, 42);"><?php echo $total_visitors; ?> Orang</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
?>