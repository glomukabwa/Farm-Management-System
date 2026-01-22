<?php

include 'config.php';

$searchInput = trim($_GET['searchInput']);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit ;

$stmt = $conn->prepare("SELECT * FROM users 
        WHERE first_name LIKE ? 
        OR second_name LIKE ? 
        OR email LIKE ? 
        OR phone_number LIKE ? 
        OR role LIKE ? 
        OR status LIKE ? 
        OR created_at LIKE ?
        ORDER BY id ASC
        LIMIT ?, ?");

$stmt->bind_param("sssssssii",
                   $searchInput, $searchInput, $searchInput, $searchInput,
                   $searchInput, $searchInput, $searchInput,
                   $offset, $limit);

$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['first_name']) ?></td>
            <td><?= htmlspecialchars($row['second_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone_number']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
        </tr>
        <?php
    }
}else{
    ?>
    <tr>
        <td colspan="8">No records found</td>
    </tr>
    <?php
}
?>