<?php
require "config.php";

// Get the user ID from the request
$userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

if ($userId === null) {
    echo json_encode(array('error' => 'User ID is required.'));
    exit;
}

// Prepare SQL query with search term and user_id
$sql = "SELECT ja.job_id, ja.company_name, ja.job_title, ja.resume_file, ja.applied_at, ja.status, 
               j.title AS job_title, j.company_name AS company_name, j.job_img AS job_img,
               u.firstname AS user_firstname, u.lastname AS user_lastname 
        FROM job_applications ja
        JOIN jobs j ON ja.job_id = j.job_id
        JOIN users u ON ja.user_id = u.user_id
        WHERE ja.user_id = ? AND (j.title LIKE ? OR j.company_name LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ?)";

// Prepare statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    error_log('Prepare failed: ' . $conn->error);
    echo json_encode(array('error' => 'Database query preparation failed.'));
    exit;
}

$stmt->bind_param("sssss", $userId, $searchTerm, $searchTerm, $searchTerm, $searchTerm);

$result = $stmt->execute();
if ($result === false) {
    error_log('Execute failed: ' . $stmt->error);
    echo json_encode(array('error' => 'Database query execution failed.'));
    exit;
}

$data = $stmt->get_result();
$applications = array();
while ($row = $data->fetch_assoc()) {
    $applications[] = $row;
}

// Fetch distinct status values for categories
$statusSql = "SELECT DISTINCT status FROM job_applications WHERE user_id = ?";
$statusStmt = $conn->prepare($statusSql);
if ($statusStmt === false) {
    error_log('Prepare failed: ' . $conn->error);
    echo json_encode(array('error' => 'Database query preparation failed.'));
    exit;
}

$statusStmt->bind_param("s", $userId);

$statusResult = $statusStmt->execute();
if ($statusResult === false) {
    error_log('Execute failed: ' . $statusStmt->error);
    echo json_encode(array('error' => 'Database query execution failed.'));
    exit;
}

$statusData = $statusStmt->get_result();
$statuses = array();
while ($statusRow = $statusData->fetch_assoc()) {
    $statuses[] = $statusRow['status'];
}

echo json_encode(array('applications' => $applications, 'statuses' => $statuses));

$stmt->close();
$statusStmt->close();
$conn->close();

?>