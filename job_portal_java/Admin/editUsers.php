<?php
if (!isset($_SESSION)) {
  session_start();
}
define('TITLE', 'Edit User');
define('PAGE', 'Users');
include('./adminInclude/header.php');
require "../config.php";

// Update
if (isset($_REQUEST['requpdate'])) {
  // Checking for Empty Fields
  if (empty($_REQUEST['user_id'])) {
    // Message displayed if required fields are missing
    $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert">User ID is required</div>';
  } else {
    // Assigning User Values to Variable
    $user_id = $_REQUEST['user_id'];

    // Start building the update query
    $fields = [];

    if (!empty($_REQUEST['firstname'])) {
      $firstname = $_REQUEST['firstname'];
      $fields[] = "firstname = '$firstname'";
    }
    if (!empty($_REQUEST['lastname'])) {
      $lastname = $_REQUEST['lastname'];
      $fields[] = "lastname = '$lastname'";
    }
    if (!empty($_REQUEST['email'])) {
      $email = $_REQUEST['email'];
      $fields[] = "email = '$email'";
    }
    if (!empty($_REQUEST['password'])) {
      $password = sha1($_REQUEST['password']); // Encrypt password using sha1
      $fields[] = "password = '$password'";
    }
    if (!empty($_REQUEST['phone'])) {
      $phone = $_REQUEST['phone'];
      $fields[] = "phone = '$phone'";
    }
    if (!empty($_REQUEST['address'])) {
      $address = $_REQUEST['address'];
      $fields[] = "address = '$address'";
    }
    if (!empty($_REQUEST['occupation'])) {
      $occupation = $_REQUEST['occupation'];
      $fields[] = "occupation = '$occupation'";
    }
    if (!empty($_REQUEST['date_of_birth'])) {
      $date_of_birth = $_REQUEST['date_of_birth'];
      $fields[] = "date_of_birth = '$date_of_birth'";
    }

    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
      $image = 'profileImage/' . basename($_FILES['image']['name']);
      if (move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
        $fields[] = "user_image = '$image'"; // Add image to fields if upload is successful
      } else {
        // Failed to upload image
        $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert">Image upload failed</div>';
      }
    }

    // Combine fields into the SQL update string
    if (!empty($fields)) {
      $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE user_id = '$user_id'";

      if ($conn->query($sql) === TRUE) {
        // Success message
        $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert">Updated Successfully</div>';
      } else {
        // Failure message
        $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert">Unable to Update</div>';
      }
    } else {
      $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert">No fields to update</div>';
    }
  }
}

// Fetch User Details
if (isset($_REQUEST['view'])) {
  $sql = "SELECT * FROM users WHERE user_id = {$_REQUEST['id']}";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
}
?>
<div class="col-sm-6 mt-5 mx-3 jumbotron">
  <h3 class="text-center">Update User Details</h3>
  <form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="user_id">ID</label>
      <input type="text" class="form-control" id="user_id" name="user_id" value="<?php if (isset($row['user_id'])) {
        echo $row['user_id'];
      } ?>" readonly>
    </div>
    <div class="form-group">
      <label for="firstname">First Name</label>
      <input type="text" class="form-control" id="firstname" name="firstname" value="<?php if (isset($row['firstname'])) {
        echo $row['firstname'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="lastname">Last Name</label>
      <input type="text" class="form-control" id="lastname" name="lastname" value="<?php if (isset($row['lastname'])) {
        echo $row['lastname'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="text" class="form-control" id="email" name="email" value="<?php if (isset($row['email'])) {
        echo $row['email'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password" value="">
    </div>
    <div class="form-group">
      <label for="phone">Phone</label>
      <input type="text" class="form-control" id="phone" name="phone" value="<?php if (isset($row['phone'])) {
        echo $row['phone'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="address">Address</label>
      <input type="text" class="form-control" id="address" name="address" value="<?php if (isset($row['address'])) {
        echo $row['address'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="occupation">Occupation</label>
      <input type="text" class="form-control" id="occupation" name="occupation" value="<?php if (isset($row['occupation'])) {
        echo $row['occupation'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="date_of_birth">Date of Birth</label>
      <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php if (isset($row['date_of_birth'])) {
        echo $row['date_of_birth'];
      } ?>">
    </div>
    <div class="form-group">
      <label for="image">Profile Image</label>
      <input type="file" class="form-control" id="image" name="image">
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-danger" id="requpdate" name="requpdate">Update</button>
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
    document.getElementById("requpdate").addEventListener("click", function (event) {
      var firstname = document.getElementById("firstname").value.trim();
      var lastname = document.getElementById("lastname").value.trim();
      var email = document.getElementById("email").value.trim();
      var password = document.getElementById("password").value.trim();
      var phone = document.getElementById("phone").value.trim();
      var address = document.getElementById("address").value.trim();
      var occupation = document.getElementById("occupation").value.trim();
      var date_of_birth = document.getElementById("date_of_birth").value.trim();

      var nameRegex = /^[A-Za-z][A-Za-z\s]*$/;
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Minimum 8 characters, at least one uppercase letter, one lowercase letter, and one number
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
        displayErrorMessage("email", "Please enter a valid email address.");
        isValid = false;
      }
      if (password && password.length < 6) { // Validate password only if it's provided
        displayErrorMessage("password", "Password must be at least 6 characters long.");
        isValid = false;
      }
      if (!phoneRegex.test(phone)) {
        displayErrorMessage("phone", "Phone number must be 10 digits.");
        isValid = false;
      }
      if (!addressRegex.test(address)) {
        displayErrorMessage("address", "Address can only contain letters, numbers, and commas.");
        isValid = false;
      }
      if (!occRegex.test(occupation)) {
        displayErrorMessage("occupation", "Occupation must only contain letters.");
        isValid = false;
      }
      if (!date_of_birth) {
        displayErrorMessage("date_of_birth", "Please select a date of birth.");
        isValid = false;
      }

      if (!isValid) {
        event.preventDefault();
      }
    });
  });

  function displayErrorMessage(fieldId, message) {
    var field = document.getElementById(fieldId);
    var errorDiv = document.createElement("div");
    errorDiv.className = "text-danger";
    errorDiv.innerText = message;
    field.parentNode.insertBefore(errorDiv, field.nextSibling);
  }

  function removeErrorMessage(fieldId) {
    var field = document.getElementById(fieldId);
    var errorDiv = field.nextSibling;
    if (errorDiv && errorDiv.className === "text-danger") {
      errorDiv.remove();
    }
  }
</script>
<?php
include('./adminInclude/footer.php');
?>