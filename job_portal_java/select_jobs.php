<?php
// Database connection
require "config.php";

// Check if category is passed as a parameter
$category_id = isset($_GET['category']) ? (int) $_GET['category'] : null;

// Prepare SQL query based on category
if ($category_id) {
    $sql = "SELECT job_id, title, description, location, salary, job_img 
            FROM jobs 
            WHERE category_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare SQL statement']);
        exit;
    }
    $stmt->bind_param("i", $category_id);
} else {
    $sql = "SELECT job_id, title, description, location, salary, job_img 
            FROM jobs";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare SQL statement']);
        exit;
    }
}

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

// Check if there are rows in the result
if ($result->num_rows > 0) {
    $jobs = array();

    while ($row = $result->fetch_assoc()) {
        // Check if the 'job_img' field contains '../' and remove it
        if (strpos($row['job_img'], '../') === 0) {
            $row['job_img'] = substr($row['job_img'], 3); // Remove the first three characters ('../')
        }

        $jobs[] = $row;
    }

    echo json_encode(['success' => true, 'jobs' => $jobs]);
} else {
    echo json_encode(['success' => false, 'message' => 'No jobs found']);
}

// Close the connection
$stmt->close();
$conn->close();
?>