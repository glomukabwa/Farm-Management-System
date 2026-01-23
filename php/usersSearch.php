<?php

include 'config.php';

$searchInput = trim($_GET['searchInput'] ?? '');
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit ;

$stmt = $conn->prepare("SELECT * FROM users 
        WHERE id = ? 
        OR first_name LIKE ? 
        OR second_name LIKE ? 
        OR email LIKE ? 
        OR phone_number LIKE ? 
        OR role LIKE ? 
        OR status LIKE ? 
        OR created_at LIKE ?
        ORDER BY id ASC
        LIMIT ?, ?");

/*Above, you need to know that WHERE can work with either = or LIKE. However, if you use = the user has to type the
  exact word. If they type An and the name in the DB is Ann, it will return "No records found" because the search
  word has to be exact. However, with LIKE, you don't have to type the exact thing. An will return Ann, Annastasia,
  Analisa etc Basiaclly any word that contains An. Plz note that both = and LIKE are not case sensitive so you can
  still type with small letters with both so even with = if you type ann, it'll return Ann. However, in the DB if
  you've made the columns in your table case sensitive, this won't work. How do you check? Open the table in 
  PHPmyAdmin, go to structure and check if the columns have utf8mb4_general_ci. This means they are case insensitive
  and this is the default. If they have utf8mb4_general_cs OR utf8mb4_general_bin, they are case sensitive. For the
  id we are not using LIKE cz a lot of times when you are searching id and you type 15, you are not searching for 
  any number with 15, you want that exact id so that's why I am using = for id. Notice the variable I will bind with
  for id below. However, in case you change ur mind and want to use LIKE for id in future, plz note that u'll bind
  it with a string not an int in bind_param() cz LIKE is a string operator. It'll treat id as a string so that with
  input 15, you can get 150, 155, 1500 etc
  
  OR DATE_FORMAT(created_at, '%Y-%m-%d') : This statement Converts DATETIME → string (YYYY-MM-DD) and allows 
  partial matching so someone could type 2026 OR 2026-01 or 2026-01-15 and they would all work(it works well with
  LIKE and %). I've removed it though cz I've removed time in the created_at column so it is no longer necessary*/

$idSearchTerm = is_numeric($searchInput) ? (int)$searchInput : 0;
/*The above means that if the input entered is a number then make it an int cz what is usually entered by the user
  is a string. If it is not an number, make it a zero. This is a safe measure cz u've binded the condition 
  below with int so u don't want an occurence where the query is comparing id with a word or u'll get an error. */

$searchTerm = "%$searchInput%";/*LIKE works with %..% so you must do this. If you put $searchInput directly, it'll
                                 work like = so it'll need the exact word. LIKE plus % checks patterns */
$stmt->bind_param("isssssssii",
                   $idSearchTerm, $searchTerm, $searchTerm, $searchTerm,
                   $searchTerm, $searchTerm, $searchTerm, $searchTerm,
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