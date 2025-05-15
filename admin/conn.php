<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lmm";
$con = mysqli_connect($servername, $username, $password, $dbname);
if($con){
    //echo "Successfully connected";
} else {
    die("Connection failed: " . mysqli_connect_error());
}
?>