<?php
if (!isset($_SESSION)) {
  session_start();
}

define('TITLE', 'Change Password');
define('PAGE', 'changepass');
include('./adminInclude/header.php');
require "../config.php";

// Initialize $adminEmail to avoid undefined variable warning
$adminEmail = ''; // Default value

if (isset($_SESSION['is_admin_login'])) {
  $adminEmail = $_SESSION['adminLogEmail'];
}

// Check if the form is submitted
if (isset($_REQUEST['adminPassUpdatebtn'])) {
  if (empty($_REQUEST['adminPass'])) {
    // Display message if required field is missing
    $passmsg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert">Fill All Fields</div>';
  } else {
    // Hash the new password
    $adminPass = sha1($_REQUEST['adminPass']);
    // Prepare the SQL statement
    $sql = "UPDATE admin SET a_Password = '$adminPass' WHERE a_Email = '$adminEmail'";
    // Execute the query
    if ($conn->query($sql) === TRUE) {
      // Success message
      $passmsg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert">Updated Successfully</div>';
    } else {
      // Failure message
      $passmsg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert">Unable to Update</div>';
    }
  }
}
?>

<div class="col-sm-9 mt-5">
  <div class="row">
    <div class="col-sm-6">
      <form class="mt-5 mx-5" method="POST" action="">
        <div class="form-group">
          <label for="inputEmail">Email</label>
          <input type="email" class="form-control" id="inputEmail" value="<?php echo htmlspecialchars($adminEmail); ?>"
            readonly>
        </div>
        <div class="form-group">
          <label for="inputnewpassword">New Password</label>
          <input type="password" class="form-control" id="inputnewpassword" placeholder="New Password" name="adminPass">
        </div>
        <button type="submit" class="btn btn-danger mr-4 mt-4" name="adminPassUpdatebtn">Update</button>
        <button type="reset" class="btn btn-secondary mt-4">Reset</button>
        <?php if (isset($passmsg)) {
          echo $passmsg;
        } ?>
      </form>
    </div>
  </div>
</div>

</div> <!-- div Row close from header -->
</div> <!-- div Container-fluid close from header -->

<?php
include('./adminInclude/footer.php');
?>