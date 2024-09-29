<?php
header('Content-Type: application/json');

// Database connection details
require 'config.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve and sanitize input data
    $firstName = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
    $lastName = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password)) {
        echo json_encode(["message" => "All fields are required."]);
        exit();
    }

    // Encrypt the password using SHA-1
    $encryptedPassword = sha1($password);

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["message" => "Email already exists."]);
        $stmt->close();
        exit();
    }

    // Prepare SQL query to insert the user
    $sql = "INSERT INTO users (firstname, lastname, email, phone, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters and execute the query
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $encryptedPassword);

        if ($stmt->execute()) {
            echo json_encode(["message" => "User registered successfully."]);
        } else {
            echo json_encode(["message" => "Error registering user."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["message" => "Failed to prepare statement."]);
    }

    // Close the connection
    $conn->close();
} else {
    echo json_encode(["message" => "Invalid request method."]);
}

?>