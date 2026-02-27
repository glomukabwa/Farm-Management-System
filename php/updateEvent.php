<?php
require 'auth.php';
include 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['EventId'];
    $title = trim($_POST['EventTitle'] ?? '');
    $date = trim($_POST['EventDate'] ?? '');

    $startTime = trim($_POST['EventStart'] ?? '');
    $formattedStart = $startTime . ':00';

    $endTime = trim($_POST['EventEnd'] ?? '');
    $formattedEnd = $endTime . ':00';

    $description = trim($_POST['Eventdesc'] ?? '');
    $userID = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE events
                            SET title = ?,
                                description = ?,
                                event_date = ?,
                                startTime = ?,
                                endTime = ?
                            WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sssssii", $title, $description, $date, $formattedStart, $formattedEnd, $id, $userID);
    $stmt->execute();
}
?>