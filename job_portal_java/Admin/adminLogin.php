<?php
// Start session
session_start();
require "../config.php";

// Initialize message variable
$msg = '';

// Check if the user is already logged in
if (isset($_SESSION['is_admin_login'])) {
    header("Location: adminDashboard.php");
    exit();
}

if (isset($_POST['login'])) {
    // Fetch and sanitize input
    $adminEmail = trim($_POST['a_Email']);
    $adminPassword = trim($_POST['a_Password']);

    // Validation: Check for empty fields
    if (empty($adminEmail) || empty($adminPassword)) {
        $msg = '<div class="alert alert-warning">All fields are required.</div>';
    } else {
        // Encrypt password using sha1
        $encryptedPassword = sha1($adminPassword);

        // Prepare the SQL query to match with a_Email and encrypted a_Password
        $sql = "SELECT * FROM admin WHERE a_Email = ? AND a_Password = ?";
        $stmt = $conn->prepare($sql);

        // Bind parameters and execute the statement
        if ($stmt) {
            $stmt->bind_param("ss", $adminEmail, $encryptedPassword);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Admin login successful, fetch user data
                $admin = $result->fetch_assoc();

                // Set session variables for the logged-in admin
                $_SESSION['is_admin_login'] = true;
                $_SESSION['adminLogEmail'] = $admin['a_Email']; // Store admin email in session
                $_SESSION['adminName'] = $admin['a_Name']; // Store admin name for personalization

                header("Location: adminDashboard.php");
                exit();
            } else {
                $msg = '<div class="alert alert-danger">Invalid Email or Password.</div>';
            }
        } else {
            $msg = '<div class="alert alert-danger">Database error: Could not prepare statement.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: white;
            min-width: 350px;
            max-width: 400px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            margin-bottom: 15px;
        }

        .login-btn {
            background-color: #28a745;
            color: white;
        }

        .login-btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2 class="text-center">Admin Login</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="a_Email">Email</label>
                <input type="email" name="a_Email" class="form-control" id="a_Email" placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <label for="a_Password">Password</label>
                <input type="password" name="a_Password" class="form-control" id="a_Password"
                    placeholder="Enter password" required>
            </div>
            <button type="submit" name="login" class="btn btn-success login-btn btn-block">Login</button>
            <?php if ($msg != '')
                echo $msg; ?>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>