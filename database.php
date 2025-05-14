<?php
// filepath: /c:/Project-Magang/database.php
$servername = "145.14.154.207";
$username = "u972146602_Lmmdummy";
$password = "321DummyLmm@123";
$dbname = "u972146602_Lmmdummy";
// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);
// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>