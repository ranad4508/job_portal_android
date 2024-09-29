<?php
if (!isset($_SESSION)) {
  session_start();
}
define('TITLE', 'Edit Job Details');
define('PAGE', 'jobs');
include('./adminInclude/header.php');
require "../config.php";

if (isset($_SESSION['is_admin_login'])) {
  $adminEmail = $_SESSION['adminLogEmail'];
}


// Update
if (isset($_REQUEST['jobUpdateBtn'])) {
  // Checking for Empty Fields
  if (
    ($_REQUEST['job_id'] == "") ||
    ($_REQUEST['job_title'] == "") ||
    ($_REQUEST['job_description'] == "") ||
    ($_REQUEST['job_requirements'] == "") ||
    ($_REQUEST['job_type'] == "") ||
    ($_REQUEST['salary'] == "") ||
    ($_REQUEST['location'] == "") ||
    ($_REQUEST['posted_date'] == "") ||
    ($_REQUEST['deadline_date'] == "")
  ) {
    // Message displayed if required field missing
    $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert">Fill All Fields</div>';
  } else {
    // Assigning User Values to Variables
    $job_id = $_REQUEST['job_id'];
    $job_title = $_REQUEST['job_title'];
    $job_description = $_REQUEST['job_description'];
    $job_requirements = $_REQUEST['job_requirements'];
    $job_type = $_REQUEST['job_type'];
    $salary = $_REQUEST['salary'];
    $location = $_REQUEST['location'];
    $posted_date = $_REQUEST['posted_date'];
    $deadline_date = $_REQUEST['deadline_date'];

    // Handle image upload
    $job_img = '';
    if ($_FILES['job_img']['name'] != '') {
      $file_name = $_FILES['job_img']['name'];
      $file_size = $_FILES['job_img']['size'];
      $file_tmp = $_FILES['job_img']['tmp_name'];
      $file_type = $_FILES['job_img']['type'];
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
      $max_file_size = 500 * 1024 * 1024; // 200MB

      if (in_array($file_ext, $allowed_extensions)) {
        if ($file_size <= $max_file_size) {
          $upload_path = '../jobImage/' . $file_name;
          move_uploaded_file($file_tmp, $upload_path);
          $job_img = '../jobImage/' . $file_name;
        } else {
          // File size exceeded
          throw new FileSizeException();
        }
      } else {
        // Invalid file extension
        throw new Exception('Invalid file. Allowed extensions: jpg, jpeg, png, gif.');
      }
    }

    $sql = "UPDATE job_details SET 
                job_title = '$job_title',
                job_description = '$job_description',
                job_requirements = '$job_requirements',
                job_type = '$job_type',
                salary = '$salary',
                location = '$location',
                posted_date = '$posted_date',
                deadline_date = '$deadline_date'";

    // Append the image field to the update query only if a new image was uploaded
    if (!empty($job_img)) {
      $sql .= ", job_img = '$job_img'";
    }

    $sql .= " WHERE job_id = '$job_id'";

    if ($conn->query($sql) === TRUE) {
      // Display success message
      $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert">Updated Successfully</div>';
    } else {
      // Display error message
      $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert">Unable to Update</div>';
    }
  }
}

?>
<div class="col-sm-6 mt-5 mx-3 jumbotron">
  <h3 class="text-center">Update Job Details</h3>
  <?php
  if (isset($_REQUEST['view'])) {
    $sql = "SELECT * FROM job_details WHERE detail_id = {$_REQUEST['id']}";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
  }
  ?>
  <form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="job_id">Job Detail ID</label>
      <input type="text" class="form-control" id="job_id" name="job_id" value="<?php if (isset($row['detail_id'])) {
        echo $row['detail_id'];
      } ?>" readonly>
    </div>
    <div class="form-group">
      <label for="job_title">Job Title</label>
      <input type="text" class="form-control" id="job_title" name="job_title" value="<?php if (isset($row['job_title'])) {
        echo $row['job_title'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="job_description">Job Description</label>
      <textarea class="form-control" id="job_description" name="job_description" row=2><?php if (isset($row['job_description'])) {
        echo $row['job_description'];
      } ?></textarea>
    </div>
    <div class="form-group">
      <label for="job_requirements">Job Requirements</label>
      <textarea class="form-control" id="job_requirements" name="job_requirements" row=2><?php if (isset($row['job_requirements'])) {
        echo $row['job_requirements'];
      } ?></textarea>
    </div>
    <div class="form-group">
      <label for="job_type">Job Type</label>
      <input type="text" class="form-control" id="job_type" name="job_type" value="<?php if (isset($row['job_type'])) {
        echo $row['job_type'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="salary">Salary</label>
      <input type="text" class="form-control" id="salary" name="salary" value="<?php if (isset($row['salary'])) {
        echo $row['salary'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="location">Location</label>
      <input type="text" class="form-control" id="location" name="location" value="<?php if (isset($row['location'])) {
        echo $row['location'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="posted_date">Posted Date</label>
      <input type="date" class="form-control" id="posted_date" name="posted_date" value="<?php if (isset($row['posted_date'])) {
        echo $row['posted_date'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="deadline_date">Deadline Date</label>
      <input type="date" class="form-control" id="deadline_date" name="deadline_date" value="<?php if (isset($row['deadline_date'])) {
        echo $row['deadline_date'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="job_img">Job Image</label>
      <?php if (isset($row['job_img']) && !empty($row['job_img'])): ?>
        <img src="<?php echo $row['job_img']; ?>" alt="Job Image" class="img-thumbnail" style="max-width: 200px;">
      <?php endif; ?>
      <input type="file" class="form-control-file" id="job_img" name="job_img">
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-danger" id="jobUpdateBtn" name="jobUpdateBtn">Update</button>
      <a href="jobs.php" class="btn btn-secondary">Close</a>
    </div>
    <?php if (isset($msg)) {
      echo $msg;
    } ?>
  </form>
</div>
</div> <!-- div Row close from header -->
</div> <!-- div Container-fluid close from header -->

<!-- JavaScript for form validation -->
<script>
  function isInputNumber(evt) {
    var ch = String.fromCharCode(evt.which);
    if (!(/[0-9]/.test(ch))) {
      evt.preventDefault();
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("jobUpdateBtn").addEventListener("click", function (event) {
      var jobTitle = document.getElementById("job_title").value.trim();
      var jobDescription = document.getElementById("job_description").value.trim();
      var jobRequirements = document.getElementById("job_requirements").value.trim();
      var jobType = document.getElementById("job_type").value.trim();
      var salary = document.getElementById("salary").value.trim();
      var location = document.getElementById("location").value.trim();
      var postedDate = document.getElementById("posted_date").value.trim();
      var deadlineDate = document.getElementById("deadline_date").value.trim();
      var jobImg = document.getElementById("job_img").value.trim();

      var isValid = true;

      // Remove previous error messages
      removeErrorMessage("job_title");
      removeErrorMessage("job_description");
      removeErrorMessage("job_requirements");
      removeErrorMessage("job_type");
      removeErrorMessage("salary");
      removeErrorMessage("location");
      removeErrorMessage("posted_date");
      removeErrorMessage("deadline_date");
      removeErrorMessage("job_img");

      if (jobTitle === "") {
        displayErrorMessage("job_title", "Job title is required.");
        isValid = false;
      }

      if (jobDescription === "") {
        displayErrorMessage("job_description", "Job description is required.");
        isValid = false;
      }

      if (jobRequirements === "") {
        displayErrorMessage("job_requirements", "Job requirements are required.");
        isValid = false;
      }

      if (jobType === "") {
        displayErrorMessage("job_type", "Job type is required.");
        isValid = false;
      }

      if (salary === "") {
        displayErrorMessage("salary", "Salary is required.");
        isValid = false;
      }

      if (location === "") {
        displayErrorMessage("location", "Location is required.");
        isValid = false;
      }

      if (postedDate === "") {
        displayErrorMessage("posted_date", "Posted date is required.");
        isValid = false;
      }

      if (deadlineDate === "") {
        displayErrorMessage("deadline_date", "Deadline date is required.");
        isValid = false;
      }

      if (jobImg !== '' && !/\.(jpg|jpeg|png|gif)$/i.test(jobImg)) {
        displayErrorMessage("job_img", "Invalid image format. Allowed formats: jpg, jpeg, png, gif.");
        isValid = false;
      }

      if (!isValid) {
        event.preventDefault();
      }
    });

    function displayErrorMessage(elementId, message) {
      var errorMessage = document.getElementById(elementId + "_error");
      if (errorMessage === null) {
        errorMessage = document.createElement("div");
        errorMessage.className = "alert alert-danger mt-2";
        errorMessage.id = elementId + "_error";
        document.getElementById(elementId).parentNode.appendChild(errorMessage);
      }
      errorMessage.innerHTML = message;
    }

    function removeErrorMessage(elementId) {
      var errorMessage = document.getElementById(elementId + "_error");
      if (errorMessage !== null) {
        errorMessage.parentNode.removeChild(errorMessage);
      }
    }
  });
</script>

<?php
include('./adminInclude/footer.php');
?>