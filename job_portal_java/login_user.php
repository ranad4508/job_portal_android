<?php
header('Content-Type: application/json');

// Database connection details
require 'config.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve and sanitize input data
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required."]);
        exit();
    }

    // Encrypt the password using SHA-1 for comparison
    $encryptedPassword = sha1($password);

    // Prepare SQL query to check user credentials
    $sql = "SELECT user_id FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute the query
        $stmt->bind_param("ss", $email, $encryptedPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a matching user is found
        if ($result->num_rows > 0) {
            // User found, fetch user_id
            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];

            // Return success and user_id
            echo json_encode([
                "success" => true,
                "message" => "Login successful.",
                "user_id" => $user_id // Include user_id in the response
            ]);
        } else {
            // User not found or incorrect password
            echo json_encode(["success" => false, "message" => "Invalid email or password."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Failed to prepare statement."]);
    }

    // Close the database connection
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
