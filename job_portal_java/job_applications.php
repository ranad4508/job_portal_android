<?php
// Include database connection
require 'config.php';

// Set the response header to application/json
header('Content-Type: application/json');

// Prepare response array
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the raw POST data
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE); // Convert JSON into an associative array

    // Check if all required fields are available
    if (isset($input['job_id'], $input['company_name'], $input['job_title'], $input['user_id'], $input['resume_file'])) {

        // Extract data from input array
        $job_id = $input['job_id'];
        $company_name = $input['company_name'];
        $job_title = $input['job_title'];
        $user_id = $input['user_id'];
        $resume_file = $input['resume_file'];  // This is the URI or file path, depending on what is sent

        // Validate file format (this is just a placeholder if needed)
        if (!empty($resume_file)) {
            // Assuming you're storing the resume URI as it is (this can change if you upload the file in a different way)
            // You can store the resume path (as a URL or path on the file system) in the database

            // Insert into job_applications table
            $query = "INSERT INTO job_applications (job_id, company_name, job_title, resume_file, applied_at, status, user_id) 
                      VALUES (?, ?, ?, ?, NOW(), 'pending', ?)";

            // Prepare the statement
            if ($stmt = $conn->prepare($query)) {
                // Bind parameters
                $stmt->bind_param("isssi", $job_id, $company_name, $job_title, $resume_file, $user_id);

                // Execute the query
                if ($stmt->execute()) {
                    // Success
                    $response['success'] = true;
                    $response['message'] = "Application submitted successfully";
                } else {
                    // Failed to insert into database
                    $response['success'] = false;
                    $response['message'] = "Failed to submit application";
                }

                // Close the statement
                $stmt->close();
            } else {
                // Database preparation error
                $response['success'] = false;
                $response['message'] = "Database error: Unable to prepare statement";
            }
        } else {
            // Missing file
            $response['success'] = false;
            $response['message'] = "Resume file is required";
        }
    } else {
        // Missing required fields
        $response['success'] = false;
        $response['message'] = "Required fields are missing";
    }
} else {
    // Invalid request method
    $response['success'] = false;
    $response['message'] = "Invalid request method";
}

// Close the database connection
$conn->close();

// Return the JSON response
echo json_encode($response);
?>