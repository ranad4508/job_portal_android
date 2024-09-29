<?php
if (!isset($_SESSION)) {
  session_start();
}
define('TITLE', 'Add New User');
define('PAGE', 'User');
include('./adminInclude/header.php');
require "../config.php";



if (isset($_REQUEST['newu_SubmitBtn'])) {
  // Checking for Empty Fields
  if (empty($_REQUEST['firstname']) || empty($_REQUEST['lastname']) || empty($_REQUEST['email']) || empty($_REQUEST['password']) || empty($_REQUEST['phone']) || empty($_REQUEST['address']) || empty($_REQUEST['occupation']) || empty($_REQUEST['date_of_birth'])) {
    $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert">Fill All Fields</div>';
  } else {
    $firstname = $_REQUEST['firstname'];
    $lastname = $_REQUEST['lastname'];
    $email = $_REQUEST['email'];
    // Use sha1 for password hashing instead of password_hash
    $password = sha1($_REQUEST['password']);
    $phone = $_REQUEST['phone'];
    $address = $_REQUEST['address'];
    $occupation = $_REQUEST['occupation'];
    $date_of_birth = $_REQUEST['date_of_birth'];

    // Handle File Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
      $imageName = $_FILES['image']['name'];
      $imageTmpName = $_FILES['image']['tmp_name'];
      $imagePath = 'profileImage/' . basename($imageName);

      if (move_uploaded_file($imageTmpName, $imagePath)) {
        $image = $imagePath;
      } else {
        $image = null;
      }
    } else {
      $image = null;
    }

    $sql = "INSERT INTO users (firstname, lastname, email, password, phone, address, occupation, date_of_birth, user_image) VALUES ('$firstname', '$lastname', '$email', '$password', '$phone', '$address', '$occupation', '$date_of_birth', '$image')";

    if ($conn->query($sql) === TRUE) {
      $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert">User Added Successfully</div>';
    } else {
      $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert">Unable to Add User</div>';
    }
  }
}
?>
<div class="col-sm-6 mt-5 mx-3 jumbotron">
  <h3 class="text-center">Add New User</h3>
  <form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="firstname">First Name</label>
      <input type="text" class="form-control" id="firstname" name="firstname">
    </div>
    <div class="form-group">
      <label for="lastname">Last Name</label>
      <input type="text" class="form-control" id="lastname" name="lastname">
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="text" class="form-control" id="email" name="email">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password">
    </div>
    <div class="form-group">
      <label for="phone">Phone</label>
      <input type="text" class="form-control" id="phone" name="phone">
    </div>
    <div class="form-group">
      <label for="address">Address</label>
      <input type="text" class="form-control" id="address" name="address">
    </div>
    <div class="form-group">
      <label for="occupation">Occupation</label>
      <input type="text" class="form-control" id="occupation" name="occupation">
    </div>
    <div class="form-group">
      <label for="date_of_birth">Date of Birth</label>
      <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
    </div>
    <div class="form-group">
      <label for="image">Profile Image</label>
      <input type="file" class="form-control" id="image" name="image">
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-danger" id="newu_SubmitBtn" name="newu_SubmitBtn">Submit</button>
      <a href="users.php" class="btn btn-secondary">Close</a>
    </div>
    <?php if (isset($msg)) {
      echo $msg;
    } ?>
  </form>
</div>
</div> <!-- div Row close from header -->
</div> <!-- div Container-fluid close from header -->

<!-- Validation Script -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("newu_SubmitBtn").addEventListener("click", function (event) {
      var firstname = document.getElementById("firstname").value.trim();
      var lastname = document.getElementById("lastname").value.trim();
      var email = document.getElementById("email").value.trim();
      var password = document.getElementById("password").value.trim();
      var phone = document.getElementById("phone").value.trim();
      var address = document.getElementById("address").value.trim();
      var occupation = document.getElementById("occupation").value.trim();
      var date_of_birth = document.getElementById("date_of_birth").value.trim();

      var nameRegex = /^[A-Za-z][A-Za-z\s]*$/;
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      var passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/; // Minimum 8 characters, at least one uppercase letter, one lowercase letter, and one number
      var phoneRegex = /^[0-9]{10}$/; // Assuming 10-digit phone number
      var addressRegex = /^[A-Za-z0-9\s,]+$/;
      var occRegex = /^[A-Za-z\s]*$/;

      var isValid = true;

      // Remove previous error messages
      removeErrorMessage("firstname");
      removeErrorMessage("lastname");
      removeErrorMessage("email");
      removeErrorMessage("password");
      removeErrorMessage("phone");
      removeErrorMessage("address");
      removeErrorMessage("occupation");
      removeErrorMessage("date_of_birth");

      if (!nameRegex.test(firstname)) {
        displayErrorMessage("firstname", "First name must start with an alphabet.");
        isValid = false;
      }

      if (!nameRegex.test(lastname)) {
        displayErrorMessage("lastname", "Last name must start with an alphabet.");
        isValid = false;
      }

      if (!emailRegex.test(email)) {
        displayErrorMessage("email", "Invalid email format.");
        isValid = false;
      }

      if (!passRegex.test(password)) {
        displayErrorMessage("password", "Password must be at least 8 characters long, including one uppercase letter, one lowercase letter, and one number.");
        isValid = false;
      }

      if (!phoneRegex.test(phone)) {
        displayErrorMessage("phone", "Phone number must be 10 digits.");
        isValid = false;
      }

      if (!addressRegex.test(address)) {
        displayErrorMessage("address", "Address should only contain alphabets, numbers, and commas.");
        isValid = false;
      }

      if (!occRegex.test(occupation)) {
        displayErrorMessage("occupation", "Occupation should only contain alphabets and spaces.");
        isValid = false;
      }

      if (date_of_birth === "") {
        displayErrorMessage("date_of_birth", "Date of birth is required.");
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