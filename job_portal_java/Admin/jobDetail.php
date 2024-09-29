<?php
if (!isset($_SESSION)) {
  session_start();
}
define('TITLE', 'Job Details');
define('PAGE', 'jobDetail');
include('./adminInclude/header.php');
require "../config.php";

if (isset($_SESSION['is_admin_login'])) {
  $adminEmail = $_SESSION['adminLogEmail'];
}
?>
<div class="col-sm-9 mt-5 mx-3">
  <form action="" class="mt-3 form-inline d-print-none">
    <div class="form-group mr-3">
      <label for="checkid">Enter Job ID: </label>
      <input type="text" class="form-control ml-3" id="checkid" name="checkid" onkeypress="isInputNumber(event)">
    </div>
    <button type="submit" class="btn btn-danger">Search</button>
  </form>

  <?php
  // Check if the form is submitted with a job detail ID
  if (isset($_REQUEST['checkid']) && !empty($_REQUEST['checkid'])) {
    $checkid = $_REQUEST['checkid'];
    $sql = "SELECT * FROM job_details WHERE detail_id = $checkid";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $_SESSION['job_id'] = $row['detail_id'];
      $_SESSION['job_title'] = $row['job_title'];
      ?>
      <h3 class="mt-5 bg-dark text-white p-2">
        Job Detail ID: <?php echo $row['detail_id']; ?>
        Job Title: <?php echo $row['job_title']; ?>
      </h3>
      <?php

      $sql = "SELECT * FROM job_details WHERE detail_id = $checkid";
      $result = $conn->query($sql);

      echo '<div class="table-responsive">'; // Start of scrollable container
      echo '<table class="table" id="tableid">
        <thead>
          <tr>
            <th scope="col">Detail ID</th>
            <th scope="col">Job Title</th>
            <th scope="col">Description</th>
            <th scope="col">Requirements</th>
            <th scope="col">Type</th>
            <th scope="col">Salary</th>
            <th scope="col">Location</th>
            <th scope="col">Posted Date</th>
            <th scope="col">Deadline Date</th>
            <th scope="col">Active</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>';

      while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<th scope="row">' . $row["detail_id"] . '</th>';
        echo '<td>' . $row["job_title"] . '</td>';
        echo '<td>' . substr($row["job_description"], 0, 100) . '</td>';
        echo '<td>' . substr($row["job_requirements"], 0, 100) . '</td>';
        echo '<td>' . $row["job_type"] . '</td>';
        echo '<td>' . $row["salary"] . '</td>';
        echo '<td>' . $row["location"] . '</td>';
        echo '<td>' . $row["posted_date"] . '</td>';
        echo '<td>' . $row["deadline_date"] . '</td>';
        echo '<td>' . ($row["is_active"] ? 'Active' : 'Inactive') . '</td>';
        echo '<td>
                <form action="editJobDetail.php" method="POST" class="d-inline">
                  <input type="hidden" name="id" value=' . $row["detail_id"] . '>
                  <button type="submit" class="btn btn-info mr-3" name="view" value="View"><i class="fas fa-pen"></i></button>
                </form>
                <form action="" method="POST" class="d-inline">
                  <input type="hidden" name="id" value=' . $row["detail_id"] . '>
                  <button type="submit" class="btn btn-secondary" name="delete" value="Delete"><i class="far fa-trash-alt"></i></button>
                </form>
              </td>';
        echo '</tr>';
      }
      echo '</tbody></table>';
      echo '</div>'; // End of scrollable container
  
    } else {
      echo '<div class="alert alert-dark mt-4" role="alert">Job Details Not Found!</div>';
    }

    if (isset($_REQUEST['delete'])) {
      $deleteId = $_REQUEST['id'];
      $sql = "DELETE FROM job_details WHERE detail_id = $deleteId";
      if ($conn->query($sql) === TRUE) {
        echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
      } else {
        echo "Unable to Delete Data";
      }
    }
  }
  ?>
</div>

<script>
  function isInputNumber(evt) {
    var ch = String.fromCharCode(evt.which);
    if (!(/[0-9]/.test(ch))) {
      evt.preventDefault();
    }
  }
</script>

<?php if (isset($_SESSION['job_id'])) {
  echo '<div><a class="btn btn-danger box" href="./addJobDetail.php"><i class="fas fa-plus fa-2x"></i></a></div>';
} ?>

</div> <!-- div Container-fluid close from header -->
<?php
include('./adminInclude/footer.php');
?>
<script>
  $(document).ready(function () {
    $('#tableid').DataTable();
  });
</script>