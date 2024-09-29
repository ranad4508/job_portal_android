<?php
if (!isset($_SESSION)) {
  session_start();
}
define('TITLE', 'Edit Job');
define('PAGE', 'jobs');
include('./adminInclude/header.php');
require "../config.php";

if (isset($_SESSION['is_admin_login'])) {
  $adminEmail = $_SESSION['adminLogEmail'];
}

// Update
if (isset($_REQUEST['jobUpdate'])) {
  // Checking for Empty Fields
  if (
    ($_REQUEST['job_id'] == "") ||
    ($_REQUEST['title'] == "") ||
    ($_REQUEST['description'] == "") ||
    ($_REQUEST['company_name'] == "") ||
    ($_REQUEST['salary'] == "") ||
    ($_REQUEST['job_type'] == "") ||
    ($_REQUEST['location'] == "") ||
    ($_REQUEST['category_id'] == "")
  ) {
    // msg displayed if required field missing
    $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fields </div>';
  } else {
    // Assigning User Values to Variables
    $jid = $_REQUEST['job_id'];
    $title = $_REQUEST['title'];
    $description = $_REQUEST['description'];
    $company = $_REQUEST['company_name'];
    $salary = abs($_REQUEST['salary']);
    $job_type = $_REQUEST['job_type'];
    $location = $_REQUEST['location'];
    $category_id = $_REQUEST['category_id'];

    // Handle image upload
    $job_img = '';
    if ($_FILES['job_img']['name'] != '') {
      $file_name = $_FILES['job_img']['name'];
      $file_size = $_FILES['job_img']['size'];
      $file_tmp = $_FILES['job_img']['tmp_name'];
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $extensions = array("jpeg", "jpg", "png", "webp");

      if (!in_array($file_ext, $extensions)) {
        $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert">Extension not allowed, please choose a JPEG, JPG, PNG, or WEBP file.</div>';
      } elseif ($file_size > 20971520) { // 20MB limit
        $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert">File size must be less than 20 MB.</div>';
      } else {
        move_uploaded_file($file_tmp, "jobImage/" . $file_name);
        // Assign the image path to $job_img
        $job_img = 'jobImage/' . $file_name;
      }
    }

    $sql = "UPDATE jobs SET 
            title = '$title', 
            description = '$description', 
            company_name = '$company', 
            salary = '$salary', 
            job_type = '$job_type', 
            location = '$location', 
            category_id = '$category_id'";

    // Append the image field to the update query only if a new image was uploaded
    if (!empty($job_img)) {
      $sql .= ", job_img = '$job_img'";
    }

    $sql .= " WHERE job_id = '$jid'";

    if ($conn->query($sql) == TRUE) {
      // Display success message
      $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert">Updated Successfully</div>';
    } else {
      // Display error message
      $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert">Unable to Update</div>';
    }
  }
}
?>
<div class="col-sm-6 mt-5  mx-3 jumbotron">
  <h3 class="text-center">Update Job Details</h3>
  <?php
  if (isset($_REQUEST['view'])) {
    $sql = "SELECT * FROM jobs WHERE job_id = {$_REQUEST['id']}";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
  }
  ?>
  <form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="job_id">Job ID</label>
      <input type="text" class="form-control" id="job_id" name="job_id" value="<?php if (isset($row['job_id'])) {
        echo $row['job_id'];
      } ?>" readonly>
    </div>
    <div class="form-group">
      <label for="title">Job Title</label>
      <input type="text" class="form-control" id="title" name="title" value="<?php if (isset($row['title'])) {
        echo $row['title'];
      } ?>">
    </div>

    <div class="form-group">
      <label for="description">Job Description</label>
      <textarea class="form-control" id="description" name="description" row=2><?php if (isset($row['description'])) {
        echo $row['description'];
      } ?></textarea>
    </div>
    <div class="form-group">
      <label for="company_name">Company Name</label>
      <input type="text" class="form-control" id="company_name" name="company_name" value="<?php if (isset($row['company_name'])) {
        echo $row['company_name'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="salary">Salary</label>
      <input type="text" class="form-control" id="salary" name="salary" onkeypress="isInputNumber(event)" value="<?php if (isset($row['salary'])) {
        echo $row['salary'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="job_type">Job Type</label>
      <input type="text" class="form-control" id="job_type" name="job_type" value="<?php if (isset($row['job_type'])) {
        echo $row['job_type'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="location">Location</label>
      <input type="text" class="form-control" id="location" name="location" value="<?php if (isset($row['location'])) {
        echo $row['location'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="category_id">Category ID</label>
      <input type="text" class="form-control" id="category_id" name="category_id" value="<?php if (isset($row['category_id'])) {
        echo $row['category_id'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="job_img">Job Image</label>
      <img src="<?php if (isset($row['job_img'])) {
        echo $row['job_img'];
      } ?>" alt="jobimage" class="img-thumbnail">
      <input type="file" class="form-control-file" id="job_img" name="job_img">
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-danger" id="jobUpdate" name="jobUpdate">Update</button>
      <a href="jobs.php" class="btn btn-secondary">Close</a>
    </div>
    <?php if (isset($msg)) {
      echo $msg;
    } ?>
  </form>
</div>
</div> <!-- div Row close from header -->
</div> <!-- div Container-fluid close from header -->
<!-- Only Number for input fields -->
<script>
  function isInputNumber(evt) {
    var ch = String.fromCharCode(evt.which);
    if (!(/[0-9]/.test(ch))) {
      evt.preventDefault();
    }
  }

  // Client-side validation
  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("jobUpdate").addEventListener("click", function (event) {
      var title = document.getElementById("title").value.trim();
      var description = document.getElementById("description").value.trim();
      var company = document.getElementById("company_name").value.trim();
      var salary = document.getElementById("salary").value.trim();
      var job_type = document.getElementById("job_type").value.trim();
      var location = document.getElementById("location").value.trim();
      var category_id = document.getElementById("category_id").value.trim();
      var job_img = document.getElementById("job_img").value.trim();

      var priceRegex = /^\d+(\.\d{1,2})?$/;
      var imgRegex = /\.(jpg|jpeg|png|webp)$/i;

      var isValid = true;


      if (!priceRegex.test(salary)) {
        displayErrorMessage("salary", "Please enter a valid salary (e.g., 5000 or 5000.00).");
        isValid = false;
      }

      if (job_img && !imgRegex.test(job_img)) {
        displayErrorMessage("job_img", "Please upload a valid image file (JPG, JPEG, PNG, or WebP).");
        isValid = false;
      }

      if (!isValid) {
        event.preventDefault();
      }

      function displayErrorMessage(id, message) {
        var errorDiv = document.createElement("div");
        errorDiv.classList.add("text-danger");
        errorDiv.innerHTML = message;
        document.getElementById(id).parentElement.appendChild(errorDiv);
      }
    });
  });
</script>
<?php
include('./adminInclude/footer.php');
?>