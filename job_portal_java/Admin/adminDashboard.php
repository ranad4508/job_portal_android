<?php
if (!isset($_SESSION)) {
  session_start();
}
define('TITLE', 'Dashboard');
define('PAGE', 'dashboard');
include('./adminInclude/header.php');
require "../config.php";

if (isset($_SESSION['is_admin_login'])) {
  $adminEmail = $_SESSION['adminLogEmail'];
}

// Fetch total jobs count
$sql = "SELECT * FROM jobs";
$result = $conn->query($sql);
$totalJobs = $result->num_rows;

// Fetch total users count
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
$totalUsers = $result->num_rows;

// Fetch total applications count
$sql = "SELECT * FROM job_applications";
$result = $conn->query($sql);
$totalApplications = $result->num_rows;
?>
<div class="col-sm-9 mt-5">
  <div class="row mx-5 text-center">
    <div class="col-sm-4 mt-5">
      <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
        <div class="card-header">Jobs</div>
        <div class="card-body">
          <h4 class="card-title">
            <?php echo $totalJobs; ?>
          </h4>
          <a class="btn text-white" href="jobs.php">View</a>
        </div>
      </div>
    </div>
    <div class="col-sm-4 mt-5">
      <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
        <div class="card-header">Users</div>
        <div class="card-body">
          <h4 class="card-title">
            <?php echo $totalUsers; ?>
          </h4>
          <a class="btn text-white" href="users.php">View</a>
        </div>
      </div>
    </div>
    <div class="col-sm-4 mt-5">
      <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
        <div class="card-header">Applications</div>
        <div class="card-body">
          <h4 class="card-title">
            <?php echo $totalApplications; ?>
          </h4>
          <a class="btn text-white" href="applications.php">View</a>
        </div>
      </div>
    </div>
  </div>
  <div class="mx-5 mt-5 text-center">
    <!-- Table for Jobs -->
    <p class="bg-dark text-white p-2">Total Jobs</p>
    <?php
    $sql = "SELECT * FROM jobs";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      echo '<table id="tableid" class="table">
    <thead>
    <tr>
      <th scope="col">Job ID</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Category ID</th>
      <th scope="col">Location</th>
      <th scope="col">Salary</th>
      <th scope="col">Job Type</th>
      <th scope="col">Posted At</th>
      <th scope="col">Image</th>
      <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>';
      while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<th scope="row">' . $row["job_id"] . '</th>';
        echo '<td>' . $row["title"] . '</td>';
        echo '<td>' . $row["description"] . '</td>';
        echo '<td>' . $row["category_id"] . '</td>';
        echo '<td>' . $row["location"] . '</td>';
        echo '<td>' . $row["salary"] . '</td>';
        echo '<td>' . $row["job_type"] . '</td>';
        echo '<td>' . $row["posted_at"] . '</td>';
        echo '<td><img src="' . $row["job_img"] . '" alt="Job Image" class="img-thumbnail" style="max-width: 100px;"></td>';
        echo '<td><form action="" method="POST" class="d-inline"><input type="hidden" name="id" value=' . $row["job_id"] . '><button type="submit" class="btn btn-secondary" name="delete" value="Delete"><i class="far fa-trash-alt"></i></button></form></td>';
        echo '</tr>';
      }
      echo '</tbody>
  </table>';
    } else {
      echo "0 Result";
    }

    if (isset($_REQUEST['delete'])) {
      $sql = "DELETE FROM jobs WHERE job_id = {$_REQUEST['id']}";
      if ($conn->query($sql) === TRUE) {
        // Refresh the page after deleting the record
        echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
      } else {
        echo "Unable to Delete Data";
      }
    }
    ?>
  </div>
</div>
</div>
</div>
</div> <!-- div Row close from header -->
</div> <!-- div Container-fluid close from header -->
<?php
include('./adminInclude/footer.php');
?>
<script>
  $(document).ready(function () {
    $('#tableid').DataTable();
  });
</script>