<?php
$host = "localhost:3307";
$user = "root";
$password = "b@bad1ana";
$dbname = "farm_management";

$conn = new mysqli($host, $user, $password, $dbname);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
?>