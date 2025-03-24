<?php
ob_start();
require_once '../includes/header.php';

if (isset($_POST['edit_project']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $startdate = date('Y-m-d', strtotime($_POST['start_date']));
    $duedate = !empty($_POST['due_date']) ? date('Y-m-d', strtotime($_POST['due_date'])) : NULL;
    $currencycode = $_POST['currencycode'];
    $status = $_POST['status'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $hourly_rate = ($type === 'hourly' && isset($_POST['hourly_rate'])) ? $_POST['hourly_rate'] : NULL;
    $client = $_POST['client'];
    $team_leader = $_POST['team_leader'] ?? NULL;
    $employees = $_POST['employees'];

    if (!empty($duedate) && $duedate < $startdate) {
        echo "<script>alert('Due Date cannot be earlier than Start Date.'); window.history.back();</script>";
        exit();
    }
    $sql = "UPDATE projects 
            SET name = '$name', start_date = '$startdate', due_date = '$duedate', currency_code = '$currencycode', 
                status = '$status', type = '$type', description = '$description', hourly_rate = '$hourly_rate', 
                client_id = '$client', team_leader_id = " . ($team_leader ? "'$team_leader'" : "NULL") . "
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        mysqli_query($conn, "DELETE FROM employee_projects WHERE project_id = '$id'");

        foreach ($employees as $employee_id) {
            $assignEmployee = "INSERT INTO employee_projects (employee_id, project_id, assigned_date) 
                               VALUES ('$employee_id', '$id', NOW())";
            mysqli_query($conn, $assignEmployee);
        }

        header('Location: ' . BASE_URL . './projects/index.php');
        exit();
    } else {
        echo "Error updating project: " . mysqli_error($conn);
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlquery = "SELECT * FROM projects WHERE id = '$id'";
    $result = mysqli_query($conn, $sqlquery);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $existingClientId = $row['client_id'];
        $existingTeamLeaderId = $row['team_leader_id'];

        $empQuery = mysqli_query($conn, "SELECT employee_id FROM employee_projects WHERE project_id = '$id'");
        $existingEmployeeIds = [];
        while ($employeeData = mysqli_fetch_assoc($empQuery)) {
            $existingEmployeeIds[] = $employeeData['employee_id'];
        }

?>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Edit Project</h4>
                    <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>
                </div>
            </div>
        </div>
        <?php include './form.php'; ?>
<?php
    } else {
        echo "Project not found.";
    }
}

require_once '../includes/footer.php';
?>