<?php
// Start session
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to admin login page
header("Location: adminLogin.php");
exit();
?>