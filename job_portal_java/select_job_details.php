<?php
header('Content-Type: application/json');

// Get job_id from query parameter
$jobId = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

if ($jobId <= 0) {
    echo json_encode(["error" => "Invalid job ID"]);
    exit();
}

// Database connection details
require 'config.php';

// Prepare and execute the query
$sql = "SELECT j.job_id, j.title AS job_title, j.company_name, j.description AS job_description, j.salary AS job_salary, 
               j.job_type AS job_type, j.location AS job_location, j.posted_at, j.job_img, 
               d.job_requirements, d.posted_date, d.deadline_date, d.is_active
        FROM jobs j
        JOIN job_details d ON j.job_id = d.detail_id
        WHERE j.job_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Failed to prepare statement"]);
    exit();
}

$stmt->bind_param("i", $jobId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch job detail
    $jobDetail = $result->fetch_assoc();

    // Check if the 'job_img' field contains '../' and remove it if it does
    if (strpos($jobDetail['job_img'], '../') === 0) {
        $jobDetail['job_img'] = substr($jobDetail['job_img'], 3); // Remove '../' prefix
    }

    echo json_encode($jobDetail);
} else {
    echo json_encode(["error" => "No details found for the specified job ID"]);
}

// Close the connection
$stmt->close();
$conn->close();
?>