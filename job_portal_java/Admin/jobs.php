<?php
if (!isset($_SESSION)) {
  session_start();
}
define('TITLE', 'Jobs');
define('PAGE', 'jobs');
include('./adminInclude/header.php');
require "../config.php";

if (isset($_SESSION['is_admin_login'])) {
  $adminEmail = $_SESSION['adminLogEmail'];
}
// else {
//   echo "<script> location.href='../index.php'; </script>";
//  }
?>

<div class="col-sm-9 mt-5">
  <!--Table-->
  <p class=" bg-dark text-white p-2">List of Jobs</p>
  <?php
  $sql = "SELECT jobs.*, job_categories.category_name FROM jobs 
  LEFT JOIN job_categories ON jobs.category_id = job_categories.category_id";

  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    echo '<table id="tableid" class="table">
       <thead>
        <tr>
         <th scope="col">Job ID</th>
         <th scope="col">Title</th>
         <th scope="col">Company</th>
         <th scope="col">Salary</th>
         <th scope="col">Job Type</th>
         <th scope="col">Location</th>
         <th scope="col">Category</th>
         <th scope="col">Action</th>
        </tr>
       </thead>
       <tbody>';
    while ($row = $result->fetch_assoc()) {
      echo '<tr>';
      echo '<th scope="row">' . $row["job_id"] . '</th>';
      echo '<td>' . $row["title"] . '</td>';
      echo '<td>' . $row["company_name"] . '</td>';
      echo '<td>' . $row["salary"] . '</td>';
      echo '<td>' . $row["job_type"] . '</td>';
      echo '<td>' . $row["location"] . '</td>';
      echo '<td>' . $row["category_name"] . '</td>';
      echo '<td><form action="editJobs.php" method="POST" class="d-inline">
           <input type="hidden" name="id" value=' . $row["job_id"] . '>
           <button type="submit" class="btn btn-info mr-3" name="view" value="View"><i class="fas fa-pen"></i></button>
           </form> 
           <form action="" method="POST" class="d-inline"><input type="hidden" name="id" value=' . $row["job_id"] . '>
          <button type="submit" class="btn btn-secondary" name="approve" value="Approve"><i class="fas fa-check" style ="color: #49c606"></i>
          </button>
          </form> &nbsp;
          <form action="" method="POST" class="d-inline"><input type="hidden" name="id" value=' . $row["job_id"] . '>
          <button type="submit" class="btn btn-secondary" name="delete" value="Delete"><i class="far fa-trash-alt"></i></button>
          </form></td>
         </tr>';
    }

    echo '</tbody>
        </table>';
  } else {
    echo "0 Result";
  }

  // Approve job functionality
  if (isset($_POST['approve'])) {
    $select = "UPDATE jobs SET status = 'Approved' WHERE job_id = {$_REQUEST['id']}";
    if ($conn->query($select) === TRUE) {
      // Refresh the page after approving the job
      echo '<meta http-equiv="refresh" content= "0;URL=?approved" />';
    } else {
      echo "Unable to approve job.";
    }
  }

  // Delete job functionality
  if (isset($_REQUEST['delete'])) {
    $sql = "DELETE FROM jobs WHERE job_id = {$_REQUEST['id']}";
    if ($conn->query($sql) === TRUE) {
      // Refresh the page after deleting the job
      echo '<meta http-equiv="refresh" content= "0;URL=?deleted" />';
    } else {
      echo "Unable to delete job.";
    }
  }
  ?>
</div>
</div> <!-- div Row close from header -->
<div><a class="btn btn-danger box" href="./addJobs.php"><i class="fas fa-plus fa-2x"></i></a></div>
</div> <!-- div Container-fluid close from header -->
<?php
include('./adminInclude/footer.php');
?>
<script>
  $(document).ready(function () {
    $('#tableid').DataTable();
  });
</script>