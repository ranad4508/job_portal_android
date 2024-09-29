<?php

// Database connection setup
require "config.php";

// Get the request payload
$data = json_decode(file_get_contents("php://input"), true);

// Extract data
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

// Validate input
if (empty($email) || empty($password)) {
    echo json_encode(array("success" => false, "message" => "All fields are required."));
    exit();
}

// Check if the email exists
$sql = "SELECT user_id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(array("success" => false, "message" => "Email not found."));
    exit();
}

// Update the password using SHA-1
$passwordHash = sha1($password);
$sql = "UPDATE users SET password = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $passwordHash, $email);
if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Password updated successfully."));
} else {
    echo json_encode(array("success" => false, "message" => "Failed to update password."));
}

$conn->close();
?>