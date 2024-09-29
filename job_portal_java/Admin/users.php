<?php
if (!isset($_SESSION)) {
  session_start();
}
define('TITLE', 'Users');
define('PAGE', 'users');
include('./adminInclude/header.php');
require "../config.php";

?>
<div class="col-sm-9 mt-5">
  <!-- Table -->
  <p class="bg-dark text-white p-2">List of Users</p>
  <?php
  $sql = "SELECT * FROM users";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    echo '<table class="table" id="tableid">
        <thead>
          <tr>
            <th scope="col">User ID</th>
            <th scope="col">User Image</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Address</th>
            <th scope="col">Occupation</th>
            <th scope="col">Date of Birth</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>';
    while ($row = $result->fetch_assoc()) {
      echo '<tr>';
      echo '<th scope="row">' . $row["user_id"] . '</th>';
      // Replace image path with actual image tag
      if (!empty($row["user_image"])) {
        $imageUrl = "http://localhost/job_portal_java/Admin/" . $row["user_image"]; // Update the URL as per your image storage path
        // Debug line to check image URL
        // Uncomment the line below to debug
        // echo '<td>' . $imageUrl . '</td>'; // Check the constructed URL
  
        // Check if the image file exists
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/job_portal_java/Admin/' . $row["user_image"])) {
          echo '<td><img src="' . $imageUrl . '" alt="User Image" style="width: 50px; height: 50px; border-radius: 50%;"></td>';
        } else {
          echo '<td>NO Image Found</td>';
        }
      } else {
        // Fallback in case there's no image
        echo '<td>NO Image Found</td>';
      }
      echo '<td>' . $row["firstname"] . '</td>';
      echo '<td>' . $row["lastname"] . '</td>';
      echo '<td>' . $row["email"] . '</td>';
      echo '<td>' . $row["phone"] . '</td>';
      echo '<td>' . $row["address"] . '</td>';
      echo '<td>' . $row["occupation"] . '</td>';
      echo '<td>' . $row["date_of_birth"] . '</td>';
      echo '<td>
          <form action="editUsers.php" method="POST" class="d-inline">
            <input type="hidden" name="id" value="' . $row["user_id"] . '">
            <button type="submit" class="btn btn-info mr-3" name="view" value="View">
              <i class="fas fa-pen"></i>
            </button>
          </form>
          <form action="" method="POST" class="d-inline">
            <input type="hidden" name="id" value="' . $row["user_id"] . '">
            <button type="submit" class="btn btn-secondary" name="delete" value="Delete">
              <i class="far fa-trash-alt"></i>
            </button>
          </form>
          </td>';
      echo '</tr>';
    }
    echo '</tbody>
        </table>';
  } else {
    echo "0 Result";
  }

  // Handle delete request
  if (isset($_REQUEST['delete'])) {
    $sql = "DELETE FROM users WHERE user_id = {$_REQUEST['id']}";
    if ($conn->query($sql) === TRUE) {
      echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
    } else {
      echo "Unable to Delete Data";
    }
  }
  ?>
</div>
</div> <!-- div Row close from header -->
<div>
  <a class="btn btn-danger box" href="addNewUsers.php"><i class="fas fa-plus fa-2x"></i></a>
</div>
</div> <!-- div Container-fluid close from header -->
<?php
include('./adminInclude/footer.php');
?>
<script>
  $(document).ready(function () {
    $('#tableid').DataTable();
  });
</script>