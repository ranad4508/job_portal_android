<?php
if (!isset($_SESSION)) {
  session_start();
}
define('TITLE', 'Add Job');
define('PAGE', 'jobs');
include('./adminInclude/header.php');
require "../config.php";

if (isset($_SESSION['is_admin_login'])) {
  $adminEmail = $_SESSION['adminLogEmail'];
}

if (isset($_REQUEST['jobSubmitBtn'])) {
  // Checking for empty fields
  if (
    empty($_REQUEST['title']) || empty($_REQUEST['description']) || empty($_REQUEST['company_name']) ||
    empty($_REQUEST['salary']) || empty($_REQUEST['job_type']) || empty($_REQUEST['location']) || empty($_REQUEST['category_id'])
  ) {
    $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fields </div>';
  } else {
    // Assigning User Values to Variables
    $title = $_REQUEST['title'];
    $description = $_REQUEST['description'];
    $company_name = $_REQUEST['company_name'];
    $salary = abs($_REQUEST['salary']);
    $job_type = $_REQUEST['job_type'];
    $location = $_REQUEST['location'];
    $category_id = $_REQUEST['category_id'];
    $posted_at = date('Y-m-d H:i:s'); // setting the current timestamp for posted_at
    $job_image = $_FILES['job_img']['name'];
    $job_image_temp = $_FILES['job_img']['tmp_name'];
    $img_folder = './jobImage/' . $job_image;
    move_uploaded_file($job_image_temp, $img_folder);

    $sql = "INSERT INTO jobs (title, description, company_name, salary, job_type, location, category_id, posted_at, job_img) 
            VALUES ('$title', '$description', '$company_name', '$salary', '$job_type', '$location', '$category_id', '$posted_at', '$img_folder')";

    if ($conn->query($sql) === TRUE) {
      $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Job Added Successfully </div>';
    } else {
      $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Add Job </div>';
    }
  }
}
?>
<div class="col-sm-6 mt-5  mx-3 jumbotron">
  <h3 class="text-center">Add New Job</h3>
  <form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="title">Job Title</label>
      <input type="text" class="form-control" id="title" name="title">
    </div>
    <div class="form-group">
      <label for="description">Job Description</label>
      <textarea class="form-control" id="description" name="description" rows="2"></textarea>
    </div>
    <div class="form-group">
      <label for="company_name">Company Name</label>
      <input type="text" class="form-control" id="company_name" name="company_name">
    </div>
    <div class="form-group">
      <label for="salary">Salary</label>
      <input type="text" class="form-control" id="salary" name="salary" onkeypress="isInputNumber(event)">
    </div>
    <div class="form-group">
      <label for="job_type">Job Type</label>
      <input type="text" class="form-control" id="job_type" name="job_type">
    </div>
    <div class="form-group">
      <label for="location">Location</label>
      <input type="text" class="form-control" id="location" name="location">
    </div>
    <div class="form-group">
      <label for="category_id">Category ID</label>
      <input type="text" class="form-control" id="category_id" name="category_id">
    </div>
    <div class="form-group">
      <label for="job_img">Job Image</label>
      <input type="file" class="form-control-file" id="job_img" name="job_img">
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-danger" id="jobSubmitBtn" name="jobSubmitBtn">Submit</button>
      <a href="jobs.php" class="btn btn-secondary">Close</a>
    </div>
    <?php if (isset($msg)) {
      echo $msg;
    } ?>
  </form>
</div>
<!-- Only Number for input fields -->
<script>
  function isInputNumber(evt) {
    var ch = String.fromCharCode(evt.which);
    if (!(/[0-9]/.test(ch))) {
      evt.preventDefault();
    }
  }

  // Client-side validation for fields
  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("jobSubmitBtn").addEventListener("click", function (event) {
      var title = document.getElementById("title").value.trim();
      var description = document.getElementById("description").value.trim();
      var company = document.getElementById("company_name").value.trim();
      var salary = document.getElementById("salary").value.trim();
      var job_type = document.getElementById("job_type").value.trim();
      var location = document.getElementById("location").value.trim();
      var category_id = document.getElementById("category_id").value.trim();
      var job_img = document.getElementById("job_img").value.trim();

      var nameRegex = /^[A-Za-z\s]+$/;
      var descRegex = /^[A-Za-z\s]+$/;
      var companyRegex = /^[A-Za-z\s]+$/;
      var priceRegex = /^\d+(\.\d{1,2})?$/;
      var imgRegex = /\.(jpg|jpeg|png)$/i;

      var isValid = true;

      if (!nameRegex.test(title)) {
        displayErrorMessage("title", "Job title must contain only alphabets and spaces.");
        isValid = false;
      }

      if (!descRegex.test(description)) {
        displayErrorMessage("description", "Job description must contain only alphabets.");
        isValid = false;
      }

      if (!companyRegex.test(company)) {
        displayErrorMessage("company_name", "Company name must contain only alphabets.");
        isValid = false;
      }

      if (!priceRegex.test(salary)) {
        displayErrorMessage("salary", "Salary must be a valid number.");
        isValid = false;
      }

      if (!imgRegex.test(job_img)) {
        displayErrorMessage("job_img", "Invalid image format. Supported formats: jpg, jpeg, png.");
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
  });
</script>

<?php
include('./adminInclude/footer.php');
?>