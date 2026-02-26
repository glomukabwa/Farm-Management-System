<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['EventTitle'] ?? '');
    $date = trim($_POST['EventDate'] ?? '');

    $startTime = trim($_POST['EventStart'] ?? '');
    $formattedStart = $startTime . ':00';

    $endTime = trim($_POST['EventEnd'] ?? '');
    $formattedEnd = $endTime . ':00';

    $description = trim($_POST['Eventdesc'] ?? '');
    $userID = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, user_id, startTime, endTime)
                            VALUES (?, ?, ?, ?, ?, ?) ");
    $stmt->bind_param("sssiss", $title, $description, $date, $userID, $formattedStart, $formattedEnd);
    $stmt->execute();
}
?>