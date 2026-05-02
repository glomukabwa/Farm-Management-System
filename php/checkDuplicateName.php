<?php
require 'admin_auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];

    $stmt = $conn->prepare("SELECT id from female_cows WHERE tag_name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    echo $stmt->num_rows() > 0 ? 'duplicate' : 'valid' ;
}
?>