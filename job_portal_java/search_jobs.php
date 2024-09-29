<?php
// Database connection setup
require "config.php";
// Get the search query from the URL
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

if (!empty($searchQuery)) {
    // Prepare SQL query to search jobs by title or description (you can adjust this to match your schema)
    $sql = "SELECT * FROM jobs WHERE title LIKE ? OR description LIKE ?";

    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $conn->real_escape_string($searchQuery) . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $jobs = array();

        // Fetch each job and add to the jobs array
        while ($row = $result->fetch_assoc()) {
            $jobs[] = array(
                "job_id" => $row['job_id'],
                "title" => $row['title'],
                "description" => $row['description'],
                "location" => $row['location'],
                "salary" => $row['salary'],
                "job_img" => $row['job_img']
            );
        }

        // Return jobs as a success response
        echo json_encode(array("success" => true, "jobs" => $jobs));
    } else {
        // No jobs found
        echo json_encode(array("success" => true, "jobs" => array()));  // Return an empty jobs array
    }
} else {
    echo json_encode(array("success" => false, "message" => "Invalid search query"));
}

$stmt->close();
$conn->close();
?>