<?php
require 'auth.php';
include 'config.php';

$userID = $_SESSION['user_id'];

$stmt = $conn->query("SELECT * FROM events WHERE user_id = $userID");

while($row = $stmt->fetch_assoc()){
    $events[] = [
        'title' => $row['title'],
        'start' => $row['event_date'] . 'T' . $row['startTime'],
        /*Remember the calendar expects start and edn in the format: dateTtime */
        'end' => $row['event_date'] . 'T' . $row['endTime'],
        'description' => $row['description']
    ];
}

echo json_encode($events);/*FullCalendar expects JSON data so that's why we do this. Also, u know JS doesn't understand the normal PHP output */
?>