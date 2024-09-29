<?php

// Database connection setup
require "config.php";

// Get the search query from the URL
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

if (!empty($searchQuery)) {
    // Prepare SQL query to search job applications based on job title, company name, or applicant name
    $sql = "SELECT ja.job_id, ja.company_name, ja.job_title, ja.resume_file, ja.applied_at, ja.status, 
                   j.title AS job_title, j.company_name AS company_name, 
                   u.firstname AS user_firstname, u.lastname AS user_lastname 
            FROM job_applications ja
            JOIN jobs j ON ja.job_id = j.job_id
            JOIN users u ON ja.user_id = u.user_id
            WHERE j.title LIKE ? OR j.company_name LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ?";

    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $conn->real_escape_string($searchQuery) . "%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $applications = array();

        // Fetch each application and add to the applications array
        while ($row = $result->fetch_assoc()) {
            $applications[] = array(
                "job_id" => $row['job_id'],
                "company_name" => $row['company_name'],
                "job_title" => $row['job_title'],
                "resume_file" => $row['resume_file'],
                "applied_at" => $row['applied_at'],
                "status" => $row['status'],
                "user_firstname" => $row['user_firstname'],
                "user_lastname" => $row['user_lastname']
            );
        }

        // Return search results as a success response
        echo json_encode(array("success" => true, "applications" => $applications));
    } else {
        // No applications found
        echo json_encode(array("success" => true, "applications" => array()));  // Return an empty applications array
    }

    $stmt->close();
} else {
    // Invalid search query
    echo json_encode(array("success" => false, "message" => "Invalid search query"));
}

$conn->close();
?>