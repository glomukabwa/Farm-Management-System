<?php
/*This will be used by js to actively check for duplicates as the user is typing the email*/

include 'config.php';

$email = trim($_GET['email'] ?? '');/*I am using GET method here cz email is not private info so its safe*/

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

echo $stmt->num_rows > 0 ? 'exists' : 'valid';

?>