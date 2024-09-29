<?php
if (!isset($_SESSION)) {
  session_start();
}
define('TITLE', 'Add Job Details');
define('PAGE', 'jobdetails');
include('./adminInclude/header.php');
require "../config.php";

// Check if form is submitted
if (isset($_REQUEST['jobSubmitBtn'])) {
  // Check for empty fields
  if (
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
    $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fields </div>';
  } else {
    // Assigning User Values to Variables
    $job_title = $_REQUEST['job_title'];
    $job_description = $_REQUEST['job_description'];
    $job_requirements = $_REQUEST['job_requirements'];
    $job_type = $_REQUEST['job_type'];
    $salary = $_REQUEST['salary'];
    $location = $_REQUEST['location'];
    $posted_date = $_REQUEST['posted_date'];
    $deadline_date = $_REQUEST['deadline_date'];
    $is_active = isset($_REQUEST['is_active']) ? 1 : 0;

    // Insert into job_details table
    $sql = "INSERT INTO job_details (job_title, job_description, job_requirements, job_type, salary, location, posted_date, deadline_date, is_active) 
            VALUES ('$job_title', '$job_description', '$job_requirements', '$job_type', '$salary', '$location', '$posted_date', '$deadline_date', '$is_active')";

    if ($conn->query($sql) === TRUE) {
      // Success message
      $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Job Details Added Successfully </div>';
    } else {
      // Error message for database insertion failure
      $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Add Job Details </div>';
    }
  }
}
?>

<div class="col-sm-6 mt-5 mx-3 jumbotron">
  <h3 class="text-center">Add New Job Details</h3>
  <form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="job_title">Job Title</label>
      <input type="text" class="form-control" id="job_title" name="job_title">
    </div>
    <div class="form-group">
      <label for="job_description">Job Description</label>
      <textarea class="form-control" id="job_description" name="job_description" rows="3"></textarea>
    </div>
    <div class="form-group">
      <label for="job_requirements">Job Requirements</label>
      <textarea class="form-control" id="job_requirements" name="job_requirements" rows="3"></textarea>
    </div>
    <div class="form-group">
      <label for="job_type">Job Type</label>
      <input type="text" class="form-control" id="job_type" name="job_type">
    </div>
    <div class="form-group">
      <label for="salary">Salary</label>
      <input type="text" class="form-control" id="salary" name="salary">
    </div>
    <div class="form-group">
      <label for="location">Location</label>
      <input type="text" class="form-control" id="location" name="location">
    </div>
    <div class="form-group">
      <label for="posted_date">Posted Date</label>
      <input type="date" class="form-control" id="posted_date" name="posted_date">
    </div>
    <div class="form-group">
      <label for="deadline_date">Deadline Date</label>
      <input type="date" class="form-control" id="deadline_date" name="deadline_date">
    </div>
    <div class="form-group">
      <label for="is_active">Active</label>
      <input type="checkbox" id="is_active" name="is_active" value="1">
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-danger" id="jobSubmitBtn" name="jobSubmitBtn">Submit</button>
      <a href="jobDetails.php" class="btn btn-secondary">Close</a>
    </div>
    <?php if (isset($msg)) {
      echo $msg;
    } ?>
  </form>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("jobSubmitBtn").addEventListener("click", function (event) {
      var jobTitle = document.getElementById("job_title").value.trim();
      var jobDesc = document.getElementById("job_description").value.trim();
      var jobReq = document.getElementById("job_requirements").value.trim();
      var jobType = document.getElementById("job_type").value.trim();
      var salary = document.getElementById("salary").value.trim();
      var location = document.getElementById("location").value.trim();
      var postedDate = document.getElementById("posted_date").value.trim();
      var deadlineDate = document.getElementById("deadline_date").value.trim();

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

      // Validate fields
      if (jobTitle === "") {
        displayErrorMessage("job_title", "Job title is required.");
        isValid = false;
      }
      if (jobDesc === "") {
        displayErrorMessage("job_description", "Job description is required.");
        isValid = false;
      }
      if (jobReq === "") {
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