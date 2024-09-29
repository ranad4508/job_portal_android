<?php
require "../config.php";

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $update_sql = "UPDATE job_applications SET status = '$status' WHERE id = $id";
    if ($conn->query($update_sql) === TRUE) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>