<?php
if (!isset($_SESSION)) {
    session_start();
}
define('TITLE', 'Applications');
define('PAGE', 'applications');
include('./adminInclude/header.php');
require "../config.php";



?>

<div class="col-sm-9 mt-5">
    <!-- Table -->
    <p class="bg-dark text-white p-2">List of Applications</p>
    <?php
    $sql = "SELECT job_applications.*, jobs.title AS job_title, jobs.company_name, users.firstname, users.lastname 
          FROM job_applications 
          LEFT JOIN jobs ON job_applications.job_id = jobs.job_id
          LEFT JOIN users ON job_applications.user_id = users.user_id";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo '<table id="tableid" class="table">
          <thead>
            <tr>
              <th scope="col">Application ID</th>
              <th scope="col">Job Title</th>
              <th scope="col">Company</th>
              <th scope="col">Applicant Name</th>
              <th scope="col">Resume</th>
              <th scope="col">Applied At</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>';
        while ($row = $result->fetch_assoc()) {
            $fullName = $row["firstname"] . " " . $row["lastname"];
            echo '<tr>';
            echo '<th scope="row">' . $row["id"] . '</th>';
            echo '<td>' . $row["job_title"] . '</td>';
            echo '<td>' . $row["company_name"] . '</td>';
            echo '<td>' . $fullName . '</td>';
            echo '<td><a href="' . $row["resume_file"] . '" target="_blank">View Resume</a></td>';
            echo '<td>' . $row["applied_at"] . '</td>';
            echo '<td>
            <select class="form-control status-dropdown" data-id="' . $row["id"] . '">
                <option value="Pending"' . ($row['status'] == 'Pending' ? ' selected' : '') . '>Pending</option>
                <option value="Scheduled for interview"' . ($row['status'] == 'Scheduled for interview' ? ' selected' : '') . '>Scheduled for interview</option>
                <option value="Rejected"' . ($row['status'] == 'Rejected' ? ' selected' : '') . '>Rejected</option>
            </select>
            </td>';
            echo '<td>
            <form action="" method="POST" class="d-inline">
              <input type="hidden" name="id" value="' . $row["id"] . '">
              <button type="submit" class="btn btn-danger delete-btn" name="delete" value="Delete">
                <i class="far fa-trash-alt"></i>
              </button>
            </form>
            </td>';
            echo '</tr>';
        }
        echo '</tbody>
          </table>';
    } else {
        echo "No applications found.";
    }

    // Handle delete request
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $delete_sql = "DELETE FROM job_applications WHERE id = $id";
        if ($conn->query($delete_sql) === TRUE) {
            // Refresh the page after deletion
            echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
        } else {
            echo "Unable to delete application.";
        }
    }
    ?>
</div>
</div> <!-- div Row close from header -->

</div> <!-- div Container-fluid close from header -->
<?php
include('./adminInclude/footer.php');
?>

<script>
    $(document).ready(function () {
        $('#tableid').DataTable();

        // Update status via AJAX when dropdown value changes
        $('.status-dropdown').change(function () {
            var status = $(this).val();
            var id = $(this).data('id');

            $.ajax({
                url: 'updateStatus.php',
                method: 'POST',
                data: { id: id, status: status },
                success: function (response) {
                    if (response == 'success') {
                        alert('Status updated successfully');
                    } else {
                        alert('Failed to update status');
                    }
                }
            });
        });

        // Confirmation for delete button
        $('.delete-btn').click(function () {
            return confirm("Are you sure you want to delete this application?");
        });
    });
</script>