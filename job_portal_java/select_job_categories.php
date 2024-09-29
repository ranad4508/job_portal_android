<?php
// Database connection
require 'config.php';

// SQL query to fetch all job categories
$sql = "SELECT category_id, category_name FROM job_categories";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $categories = array();

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode(['success' => true, 'categories' => $categories]);
} else {
    echo json_encode(['success' => false, 'message' => 'No categories found']);
}

// Close the connection
$conn->close();
?>