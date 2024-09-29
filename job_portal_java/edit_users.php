<?php
require "config.php";

// Check if user_id is sent in the request
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // If request is for fetching data
    if (isset($_POST['action']) && $_POST['action'] == "fetch") {
        // Fetch user details
        $sql = "SELECT firstname, lastname, email, phone, address, occupation, date_of_birth FROM users WHERE user_id = '$user_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            $response = array("status" => "success", "data" => $userData);
        } else {
            $response = array("status" => "error", "message" => "User not found.");
        }
    }
    // If request is for updating data
    else {
        // Retrieve data from POST request
        $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : "";
        $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : "";
        $email = isset($_POST['email']) ? $_POST['email'] : "";
        $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
        $address = isset($_POST['address']) ? $_POST['address'] : "";
        $occupation = isset($_POST['occupation']) ? $_POST['occupation'] : "";
        $date_of_birth = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : "";

        // Prepare the SQL query for updating the user
        $sql = "UPDATE users SET 
                    firstname = IF('$firstname' != '', '$firstname', firstname),
                    lastname = IF('$lastname' != '', '$lastname', lastname),
                    email = IF('$email' != '', '$email', email),
                    phone = IF('$phone' != '', '$phone', phone),
                    address = IF('$address' != '', '$address', address),
                    occupation = IF('$occupation' != '', '$occupation', occupation),
                    date_of_birth = IF('$date_of_birth' != '', '$date_of_birth', date_of_birth)
                WHERE user_id = '$user_id'";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            $response = array("status" => "success", "message" => "Profile updated successfully.");
        } else {
            $response = array("status" => "error", "message" => "Error updating profile: " . $conn->error);
        }
    }
} else {
    $response = array("status" => "error", "message" => "User ID is missing.");
}

// Return the response as JSON
echo json_encode($response);

// Close connection
$conn->close();
?>