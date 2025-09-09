<?php
// ***** แก้ไขให้เรียกใช้ connectdb.php *****
require_once 'connectdb.php';

$sql = "SELECT category_id, category_name FROM categories ORDER BY category_name ASC";
$result = $conn->query($sql);

$categories = array();
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

echo json_encode($categories);
$conn->close();
?>