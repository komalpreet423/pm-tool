<?php
ob_start();
require_once '../includes/header.php';
$plugins = ['datepicker', 'select2'];
if (isset($_POST['add_project'])) {
    $name = $_POST['name'];
    $startdate = date('Y-m-d', strtotime($_POST['start_date']));
    $duedate = date('Y-m-d', strtotime($_POST['due_date']));
    $currencycode = $_POST['currencycode'];
    $status = $_POST['status'];
    $type = $_POST['type'];
    $hourly_rate = $_POST['hourly_rate'];
    $description = $_POST['description'];
    $client = $_POST['client'];
    $team_leader = isset($_POST['team_leader']) && $_POST['team_leader'] !== '' ? $_POST['team_leader'] : "NULL";
    $employee_id = $_POST['employee'];

    $insertProject = "INSERT INTO projects (name, start_date, due_date, currency_code, status, type, hourly_rate, description, client_id, team_leader_id) 
    VALUES ('$name', '$startdate', '$duedate', '$currencycode', '$status', '$type', '$hourly_rate', '$description', '$client', 
            " . ($team_leader === "NULL" ? "NULL" : "'$team_leader'") . ")";

    if (mysqli_query($conn, $insertProject)) {
        $project_id = mysqli_insert_id($conn);
        $assignEmployee = "INSERT INTO employee_projects (employee_id, project_id, assigned_date) 
                           VALUES ('$employee_id', '$project_id', NOW())";
        mysqli_query($conn, $assignEmployee);

        header('Location: ' . BASE_URL . './projects/index.php');
    } else {
        $errorMessage = mysqli_error($conn);
    }
}

?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box  pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add New Project </h4>
            <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>
        </div>
    </div>
</div>
<?php include './form.php' ?>

<?php require_once '../includes/footer.php'; ?>