<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?php echo TITLE ?>
    </title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="../assets/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/fontawesome.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/a_style.css">
    <!-- data table css -->
    <link rel="stylesheet" href="../css/datatables.min.css">

</head>

<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-dark fixed-top p-0 shadow" style="background-color: #225470;">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="adminDashboard.php">JobSathi<small
                class="text-white px-2">Admin
                Area</small></a>
    </nav>

    <!-- Side Bar -->
    <div class="container-fluid mb-5" style="margin-top:40px;">
        <div class="row">
            <nav class="col-sm-3 col-md-2 bg-light sidebar py-5 d-print-none">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'dashboard') {
                                echo 'active';
                            } ?>" href="adminDashboard.php">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'jobs') {
                                echo 'active';
                            } ?>" href="jobs.php">
                                <i class="fab fa-accessible-icon"></i>
                                Jobs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'jobDetail') {
                                echo 'active';
                            } ?>" href="jobDetail.php">
                                <i class="fab fa-accessible-icon"></i>
                                Job Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'applications') {
                                echo 'active';
                            } ?>" href="applications.php">
                                <i class="fab fa-accessible-icon"></i>
                                Applications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'users') {
                                echo 'active';
                            } ?>" href="users.php">
                                <i class="fas fa-users"></i>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if (PAGE == 'changepass') {
                                echo 'active';
                            } ?>" href="adminChangePass.php">
                                <i class="fas fa-users"></i>
                                Change Password
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>