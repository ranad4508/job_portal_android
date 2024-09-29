<?php

require 'config.php'; // Database connection file

// Check if user_id is provided
if (!isset($_GET['user_id'])) {
    echo json_encode(array('error' => 'No user_id provided'));
    exit;
}

$user_id = $_GET['user_id'];

// Prepare SQL query to fetch user data
$sql = "SELECT user_id, firstname, lastname, email, phone, address, password, occupation, date_of_birth, user_image
        FROM users
        WHERE user_id = ?";

// Prepare statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    error_log('Prepare failed: ' . $conn->error);
    echo json_encode(array('error' => 'Database query preparation failed.'));
    exit;
}

// Bind parameters and execute
$stmt->bind_param("i", $user_id);
$result = $stmt->execute();
if ($result === false) {
    error_log('Execute failed: ' . $stmt->error);
    echo json_encode(array('error' => 'Database query execution failed.'));
    exit;
}

// Fetch result
$data = $stmt->get_result();
if ($data->num_rows > 0) {
    $user = $data->fetch_assoc();
    echo json_encode(array('user' => $user));
} else {
    echo json_encode(array('error' => 'No user found with the provided user_id'));
}

// Close statement and connection
$stmt->close();
$conn->close();
?>