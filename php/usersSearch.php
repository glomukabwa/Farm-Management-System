<?php

include 'config.php';

$searchInput = trim($_GET['searchInput'] ?? '');
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
        ORDER BY id ASC
        LIMIT ?, ?");

/*Above, you need to know that WHERE can work with either = or LIKE. However, if you use = the user has to type the
  exact word. If they type An and the name in the DB is Ann, it will return "No records found" because the search
  word has to be exact. However, with LIKE, you don't have to type the exact thing. An will return Ann, Annastasia,
  Analisa etc Basiaclly any word that contains An. Plz note that noth = and LIKE are not case sensitive so you can
  still type with small letters with both so even with = if you type ann, it'll return Ann. However, in the DB if
  you've made the columns in your table case sensitive, this won't work. How do you check? Open the table in 
  PHPmyAdmin, go to structure and check if the columns have utf8mb4_general_ci. This means they are case insensitive
  and this is the default. If they have utf8mb4_general_cs OR utf8mb4_general_bin, they are case sensitive */
$searchTerm = "%$searchInput%";/*LIKE works with %..% so you must do this. If you put $searchInput directly, it'll
                                 work like = so it'll need the exact word. LIKE plus % checks patterns */
$stmt->bind_param("ssssssii",
                   $searchTerm, $searchTerm, $searchTerm, $searchTerm,
                   $searchTerm, $searchTerm,
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