<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $eventId = $_POST['EventId'];
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $eventId, $userId);
    $stmt->execute();
}
?>