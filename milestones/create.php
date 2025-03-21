<?php
ob_start();
require_once '../includes/header.php';
$plugins = ['datepicker', 'select2'];

if (isset($_POST['add_milestone'])) {
    $project_id = $_POST['project_id'];
    $milestone_name = $_POST['milestone_name'];
    $description = $_POST['description'];
    $due_date = date('Y-m-d', strtotime($_POST['due_date']));
    $amount = $_POST['amount'] ? $_POST['amount'] : NULL;
    $currency_code = $_POST['currency_code'];
    $status = $_POST['status'];

    $projectCheckQuery = "SELECT id FROM projects WHERE id = '$project_id' AND type = 'fixed'";
    $result = mysqli_query($conn, $projectCheckQuery);

    if (mysqli_num_rows($result) > 0) {
        $insertquery = "INSERT INTO project_milestones (project_id, milestone_name, due_date, amount, currency_code, description, status) 
                        VALUES ('$project_id', '$milestone_name', '$due_date', '$amount', '$currency_code', '$status','$description')";

        if (mysqli_query($conn, $insertquery)) {
            header('Location: ' . BASE_URL . './milestones/index.php');
            exit();
        } else {
            $errorMessage = "Database Error: " . mysqli_error($conn);
        }
    } else {
        $errorMessage = "Invalid project selection. Please select a valid fixed project.";
    }
}
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add New Milestone</h4>
            <a href="./index.php" class="btn btn-primary d-flex"><i class="bx bx-left-arrow-alt me-1 fs-4"></i>Go Back</a>
        </div>
    </div>
</div>
<div class="card">
    <?php include './form.php' ?>
</div>

<?php require_once '../includes/footer.php'; ?>