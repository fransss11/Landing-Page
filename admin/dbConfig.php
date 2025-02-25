
<?php
// Database configuration
$host = "145.14.154.207";
$user = "u454747069_first374";
$password = "374@First374";
$dbname = "u454747069_first";

// Create database connection
$db = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
